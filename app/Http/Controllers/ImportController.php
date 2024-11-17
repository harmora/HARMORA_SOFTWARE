<?php

namespace App\Http\Controllers;


use App\Models\Entreprise;
use App\Models\Achat;
use App\Models\mouvements_stock;
use App\Models\Product;
use App\Models\Client;
use App\Models\Forme_juridique;
use App\Models\fournisseur;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


use App\Models\Customer;
use App\Imports\CustomerImport;
use App\Imports\FournisseurImport;
use Maatwebsite\Excel\Facades\Excel;

use Throwable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Carbon\Carbon;


class ImportController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    public function showForm()
    {
        return view('import.form');
    }
    public function step1(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls', // Only allow Excel files (xlsx and xls) and limit the file size to 2MB
            'table' => 'required|string', // Ensure 'table' input is provided
        ],
        [
            'file.required' => 'Please upload a file',
            'file.mimes' => 'Only Excel files are allowed',
            'table.required' => 'Please select a table',
        ]);
        $file = $request->file('file');
        $table = $request->input('table');

        // Save the file to a temporary location in storage
        $path = $file->storeAs('temp', $file->getClientOriginalName());
    
        // Get the headings (first row) from the uploaded file
        $headings = Excel::toArray([], storage_path('app/' . $path))[0][0];
    
        // Retrieve all columns from the table
        $allColumns = Schema::getColumnListing($table); // Replace 'your_table_name' with the actual table name
        
        // Filter out 'id', 'created_at', and 'updated_at'
    
        // Get the required columns from the database
        $requiredColumns = Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails($table)->getColumns();

        if($table == 'fournisseurs'){
            $requiredColumns = array_filter($requiredColumns, function($column) {
                return $column->getNotnull() && !in_array($column->getName(), ['id']);
            });
            $requiredColumns = array_keys($requiredColumns);
            //remove column that ara in requiredColumns along with id, created_at, and updated_at
            $dbColumns = array_filter($allColumns, function($column) use ($requiredColumns) {
                return !in_array($column, array_merge($requiredColumns, ['id', 'created_at', 'updated_at','entreprise_id']));
            });

        }elseif($table == 'clients'){
            $requiredColumns = array_filter($requiredColumns, function($column) {
                return $column->getNotnull() && !in_array($column->getName(), ['id','acct_create_mail_sent','lang','status','internal_purpose','photo']);
            });
            $requiredColumns = array_keys($requiredColumns);
            //remove column that ara in requiredColumns along with id, created_at, and updated_at
            $dbColumns = array_filter($allColumns, function($column) use ($requiredColumns) {
                return !in_array($column, array_merge($requiredColumns, ['id', 'created_at', 'updated_at','email_verified_at','email_verification_mail_sent','entreprise_id','dob','doj','remember_token','acct_create_mail_sent','lang','status','internal_purpose']));
            });

        }elseif($table == 'products'){
            $requiredColumns = array_filter($requiredColumns, function($column) {
                return $column->getNotnull() && !in_array($column->getName(), ['id']);
            });
            $requiredColumns = array_keys($requiredColumns);
            //remove column that ara in requiredColumns along with id, created_at, and updated_at
            $dbColumns = array_filter($allColumns, function($column) use ($requiredColumns) {
                return !in_array($column, array_merge($requiredColumns, ['id', 'created_at', 'updated_at','product_category_id','photo']));
            });

        }elseif($table == 'achats'){
            $requiredColumns = array_filter($requiredColumns, function($column) {
                return $column->getNotnull() && !in_array($column->getName(), ['id']);
            });
            $requiredColumns = array_keys($requiredColumns);
            //remove column that ara in requiredColumns along with id, created_at, and updated_at
            $dbColumns = array_filter($allColumns, function($column) use ($requiredColumns) {
                return !in_array($column, array_merge($requiredColumns, ['id', 'created_at', 'updated_at']));
            });
        }
        


    
        return view('import.step2', compact('headings', 'dbColumns', 'path', 'requiredColumns','table'));
    }
    

    public function step2(Request $request)
    {
        $path = $request->input('path');
        $table = $request->input('table');
        $mappings = $request->input('mappings');
        $saveColumns = $request->input('save_columns', []);
    
        // Read all rows from the stored file
        $rows = Excel::toArray([], storage_path('app/' . $path))[0];
        $rows = array_slice($rows, 1); // Remove the header row
        $rows = array_filter($rows, function($row) {
            return array_filter($row); // Keep rows that have at least one non-empty value
        });
    
        return view('import.step3', compact('rows', 'mappings', 'saveColumns', 'path', 'table'));
    }
    
    public function save(Request $request)
    {
        $data = $request->input('data');
        $path = $request->input('path');
        $saveColumns = json_decode($request->input('save_columns', []), true);
        $table = $request->input('table');  // Get the table name from the request
        $count = 0;
        // Save data to the database
        foreach ($data as $row) {
            $dataToUpdate = array_intersect_key($row, array_flip($saveColumns));
            
            if($table == 'fournisseurs'){
                $fournisseur = Fournisseur::where('email', $row['email'])->first();
                if (!$fournisseur){
                    $dataToUpdate['email'] = $row['email']; // Make sure email is set for new entries
                    $dataToUpdate['entreprise_id'] = $this->user->entreprise_id;
                    Fournisseur::create($dataToUpdate);
                    $count++;
                };

            }elseif($table == 'clients'){
                $client = Client::where('email', $row['email'])->first();
                if (!$client){
                    $dataToUpdate['email'] = $row['email']; // Make sure email is set for new entries
                    $dataToUpdate['entreprise_id'] = $this->user->entreprise_id;
                    if (!in_array('first_name', $saveColumns)) {
                        if (!empty($row['first_name'])) {
                            $dataToUpdate['internal_purpose'] =1; 
                        } else {
                            $dataToUpdate['internal_purpose'] =0; 
                        }
                    }
                    else{
                        $dataToUpdate['internal_purpose'] =1; 
                    }
                    // Handle photo if selected
                    if (in_array('photo', $saveColumns)) {
                        if (!empty($row['photo'])) {
                            // Assuming the photo is a file path that needs to be stored
                            $photoPath = $row['photo'];
                            
                            // Handle the photo upload if it's a new file
                            if (Storage::exists($photoPath)) {
                                $dataToUpdate['photo'] = Storage::copy($photoPath, 'public/photos/' . basename($photoPath));
                            } else {
                                $dataToUpdate['photo'] = 'photos/no-image.jpg'; // Default image if file doesn't exist
                            }
                        } else {
                            $dataToUpdate['photo'] = 'photos/no-image.jpg'; // Default image if no photo provided
                        }
                    }
                    else{
                        $dataToUpdate['photo'] = 'photos/no-image.jpg'; // Default image if no photo provided
                    }
                    $dataToUpdate['ICE'] = $row['ice'];
                    Client::create($dataToUpdate);
                    $count++;
                };
            }elseif($table=='products'){
                $product = Product::where('name', $row['name'])->first();
                $dataToUpdate['name'] = $row['name']; // Make sure email is set for new entries
                $dataToUpdate['entreprise_id'] = $this->user->entreprise_id;
                $dataToUpdate['reference'] = $this->generateProductReference();
                if (!$product){
                    $prod=Product::create($dataToUpdate);
                    mouvements_stock::create([
                        'product_id'=>$prod->id,
                        'quantitéajoutée'=>$prod->stock,
                        'quantitéprecedente'=>0,
                        'date_mouvement'=>now(),
                        'type_mouvement'=>'entrée',
                        'reference'=>$prod->name.'-'.$prod->reference,
                    ]);
                    $count++;
                    // $product = Product::where('name', $row['name'])->first();
                }else{
                    $dataToUpdate['stock'] = $row['stock']+$product['stock']; // Make sure email is set for new entries
                    $product->update($dataToUpdate);
                    mouvements_stock::create([
                        'product_id'=>$product->id,
                        'quantitéajoutée'=>$row['stock'],
                        'quantitéprecedente'=>$product->stock - $row['stock'],
                        'date_mouvement'=>now(),
                        'type_mouvement'=>'entrée',
                        'reference'=>$product->name.'-'.$product->reference,
                    ]);
                    $count++;
                    
                }
                // mouvements_stock::create([
                //     'product_id'=>$product->id,
                //     // 'achat_id'=>$achat->id,
                //     'quantitéajoutée'=>$productd['quantity'],
                //     'quantitéprecedente'=>$product->stock,
                //     'date_mouvement'=>now(),
                //     'type_mouvement'=>'entrée',
                //     'reference'=>$product->id.'-'.,
                // ]);
            }
            
        }
    
        // Delete the temporary file
        Storage::delete($path);
        if($count == 0){
            Session::flash('error',$table.' already exists .');
        }else{
            if($table=='products')
                Session::flash('message',$count.' '.$table.' created/updated successfully.');
            else
                Session::flash('message',$count.' '.$table.' created successfully.');
        }
        return redirect()->route($table . '.index');
    }

    private function generateProductReference()
{
    // Get the last product with the highest reference
    $lastProduct = Product::orderBy('reference', 'desc')->first();

    // If there is no previous reference, start with Product_00000001
    if (!$lastProduct) {
        return 'Product_00000001';
    }

    // Extract the hexadecimal part of the last reference
    $lastHex = substr($lastProduct->reference, 8); // Skip the 'Product_' prefix

    // Convert the hexadecimal part to a decimal number, increment it, and then convert back to hexadecimal
    $nextHex = strtoupper(dechex(hexdec($lastHex) + 1));

    // Pad the new hex value to 8 characters (e.g., 00000001, 0000000A)
    $nextReference = str_pad($nextHex, 8, '0', STR_PAD_LEFT);

    // Return the new reference with the Product_ prefix
    return 'Product_' . $nextReference;
}
    

}
