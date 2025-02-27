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

        // Get the entire array from Excel file
        $excelData = Excel::toArray([], storage_path('app/' . $path))[0];

        // Filter out completely empty rows and get valid data
        $validData = array_filter($excelData, function($row) {
            return !empty(array_filter($row, function($cell) {
                return !is_null($cell) && trim($cell) !== '';
            }));
        });

        // Reset array keys after filtering
        $validData = array_values($validData);

        // If no valid data found
        if (empty($validData)) {
            Storage::delete($path);
            return redirect()->back()->withErrors(['file' => 'The Excel file contains no valid data']);
        }

        // Get headers from the first non-empty row
        $headings = array_map('trim', $validData[0]);

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
        
        session(['excel_data' => $validData]);
        return view('import.step2', compact('headings', 'dbColumns', 'path', 'requiredColumns','table'));
    }
    

    public function step2(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'table' => 'required|string',
            'mappings' => 'required|array',
            'save_columns' => 'array',
        ]);
    
        $path = $request->input('path');
        $table = $request->input('table');
        $mappings = $request->input('mappings');
        $saveColumns = $request->input('save_columns', []);
    
        // Get the filtered data from session that we stored in step1
        $validData = session('excel_data', []);
    
        if (empty($validData)) {
            // If somehow the session data is lost, try to read from file again
            $validData = Excel::toArray([], storage_path('app/' . $path))[0];
            $validData = array_filter($validData, function($row) {
                return !empty(array_filter($row, function($cell) {
                    return !is_null($cell) && trim($cell) !== '';
                }));
            });
            $validData = array_values($validData);
        }
    
        // Remove the header row and get only data rows
        $rows = array_slice($validData, 1);
    
        // Additional validation to ensure we have data to process
        if (empty($rows)) {
            Storage::delete($path);
            return redirect()->back()->withErrors(['file' => 'No valid data rows found in the Excel file']);
        }
    
        // Validate mappings against the actual columns
        $validMappings = [];
        foreach ($mappings as $excelColumn => $dbColumn) {
            if (!empty($dbColumn)) {
                $validMappings[$excelColumn] = $dbColumn;
            }
        }
    
        if (empty($validMappings)) {
            return redirect()->back()->withErrors(['mappings' => 'At least one column mapping is required']);
        }
    
        // Store mappings in session for use in next step
        session(['column_mappings' => $validMappings]);
        
        return view('import.step3', compact('rows', 'mappings', 'saveColumns', 'path', 'table'));
    }
    // In your ImportController.php

public function getHeaders(Request $request)
{
    $request->validate([
        'path' => 'required|string',
        'sheet' => 'required|numeric',
    ]);

    try {
        // Get all sheets from the Excel file
        $excelData = Excel::toArray([], storage_path('app/' . $request->path));
        
        // Get data from selected sheet
        $sheetData = $excelData[$request->sheet];

        // Filter out completely empty rows and get valid data
        $validData = array_filter($sheetData, function($row) {
            return !empty(array_filter($row, function($cell) {
                return !is_null($cell) && trim($cell) !== '';
            }));
        });

        // Reset array keys after filtering
        $validData = array_values($validData);

        if (empty($validData)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid data found in the selected sheet'
            ]);
        }

        // Get headers from the first non-empty row
        $headings = array_map('trim', $validData[0]);

        return response()->json([
            'success' => true,
            'headings' => $headings
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error reading sheet headers: ' . $e->getMessage()
        ]);
    }
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
