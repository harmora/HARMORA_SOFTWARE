<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Achat;
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
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Template;
use App\Models\Workspace;
use Illuminate\Validation\Rule;
use App\Services\DeletionService;
use App\Notifications\VerifyEmail;
use Spatie\Permission\Models\Role;
use App\Models\UserClientPreference;
use App\Notifications\AccountCreation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Carbon\Carbon;

class FournisseurController extends Controller
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
    public function index()
    {
        $fournisseurs = fournisseur::all();
        $entreprises = Entreprise::all();
        $formesjuridique= Forme_juridique::all();
        // $visibleColumns = getUserPreferences('entreprises'); // Adjust this based on how you get user preferences
        return view('fournisseurs.fournisseurs',['fournisseurs'=> $fournisseurs,'entreprises'=> $entreprises,'fomesJuridique'=> $formesjuridique]);
    }

    public function create()
    {
        $entreprises = Entreprise::all();
        return view('fournisseurs.create_fournisseurs', ['entreprises' => $entreprises]);
    }
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:fournisseurs,email',
            'phone' => 'nullable',
            'city' => 'nullable',
            'country' => 'nullable',
        ]);
        $formFields['entreprise_id'] = $this->user->entreprise_id;

        $fournisseur = Fournisseur::create($formFields);
        Session::flash('message', 'Fournisseur created successfully.');
        return response()->json(['error' => false, 'id' => $fournisseur->id]);
    }

        public function importExcelData(Request $request)
        {
            $file = $request->file('import_file');
            $headings = Excel::toArray([], $file)[0][0]; // Get the first row (column titles)
    
            // return view('fournisseurs.step2', compact('headings', 'file'));
            return view('fournisseurs.step2', ['headings' => $headings, 'file' => $file]);

            
        }
        // public function step2(Request $request)
        // {
        //     $file = $request->input('file');
        //     $mappings = $request->input('mappings');

        //     $rows = Excel::toArray([], $file)[0]; // Get all rows

        //     return view('fournisseurs.step3', compact('rows', 'mappings'));
        // }

        // public function save(Request $request)
        // {
        //     $data = $request->input('data');

        //     foreach ($data as $row) {
        //         // Assuming you are working with the User model
        //         Fournisseur::create($row);
        //     }
        //     Session::flash('message', 'Fournisseurs .');
        //     return response()->json(['error' => false]);
        // }
       
        // public function step1(Request $request)
        // {
        //     $file = $request->file('file');
        //     // Process the file and prepare data for the next step
            
        //     // Return JSON response with HTML for the next step
        //     return response()->json([
        //         'success' => true,
        //         'html' => view('import.step2', compact('data'))->render()
        //     ]);
        // }
        // public function step1(Request $request)
        // {
        //     $file = $request->file('file');
        //     // Save the file to a temporary location in storage
        //     $path = $file->storeAs('temp', $file->getClientOriginalName());
        //     // Get the headings (first row) from the uploaded file
        //     $headings = Excel::toArray([], storage_path('app/' . $path))[0][0];
        //     return view('import.step2', compact('headings', 'path'));
    
        // }
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
                    return !in_array($column, array_merge($requiredColumns, ['id', 'created_at', 'updated_at']));
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

            // Save data to the database
            foreach ($data as $row) {
                $dataToUpdate = array_intersect_key($row, array_flip($saveColumns));
                
                if($table == 'fournisseurs'){
                    $fournisseur = Fournisseur::where('email', $row['email'])->first();
                    if (!$fournisseur){
                        $dataToUpdate['email'] = $row['email']; // Make sure email is set for new entries
                        $dataToUpdate['entreprise_id'] = $this->user->entreprise_id;
                        Fournisseur::create($dataToUpdate);
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

                        Client::create($dataToUpdate);
                    };
                }elseif($table=='products'){
                    $product = Product::where('name', $row['name'])->first();
                    $dataToUpdate['name'] = $row['name']; // Make sure email is set for new entries
                    if (!$product){
                        Product::create($dataToUpdate);
                    }else{
                        $dataToUpdate['stock'] = $row['stock']+$product['stock']; // Make sure email is set for new entries
                        $product->update($dataToUpdate);
                    }
                }
                
            }
        
            // Delete the temporary file
            Storage::delete($path);
            return redirect()->route('fournisseurs.index');
        }
        
    

        public function edit($id)
        {
            $fournisseur = Fournisseur::find($id);
            $entreprises = Entreprise::all();
            return view('fournisseurs.update_fournisseurs', ['fournisseur' => $fournisseur, 'entreprises' => $entreprises]);
        }
        public function update(Request $request, $id)
        {
            $fournisseur = Fournisseur::find($id);
            $formFields = $request->validate([
                'name' => 'required',
                'email' => ['required', 'email', Rule::unique('fournisseurs')->ignore($fournisseur->id)],
                'phone' => 'nullable',
                'city' => 'nullable',
                'country' => 'nullable',
                'entreprise_id' => 'nullable|exists:entreprises,id'
            ]);

            $fournisseur->update($formFields);
            Session::flash('message', 'Fournisseur updated successfully.');
            return response()->json(['error' => false]);
        }
        public function destroy($id)
        {
            $response = DeletionService::delete(fournisseur::class, $id, 'fournisseur');
            // UserClientPreference::where('user_id', 'u_' . $id)->delete();

            return $response;
        }
        public function list()
        {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';

        $query = Fournisseur::query();
        // $fournisseurs=fournisseur::all();
        // Search functionality
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('entreprise', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $totalFournisseurs = $query->count();

        // $fournisseurs = $query->orderBy($sort, $order)
        //     ->paginate(request("limit"));
        $fournisseurs = $query->select('fournisseurs.*')
        ->leftJoin('entreprises', 'fournisseurs.entreprise_id', '=', 'entreprises.id')
        ->orderBy($sort, $order)
        ->paginate(request('limit'));
        // $fournisseurs = $fournisseurs->select('fournisseurs.*')
        // ->distinct()
        // ->orderBy($sort, $order)
        // ->paginate(request('limit'))
        // ->through(function ($fournisseur)
        $fournisseurs = $fournisseurs->through(function ($fournisseur){
            $actions = '';

                $actions .= '<a href="/fournisseurs/edit/' . $fournisseur->id . '" title="' . get_label('update', 'Update') . '">' .
                    '<i class="bx bx-edit mx-1"></i>' .
                    '</a>';



                $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $fournisseur->id . '" data-type="fournisseurs">' .
                    '<i class="bx bx-trash text-danger mx-1"></i>' .
                    '</button>';


            $actions = $actions ?: '-';

            $profileHtml = "<div class='avatar avatar-md pull-up' title='" . $fournisseur->name . " '>
                <a href='/clients/profile/" . $fournisseur->id . "'>
                </a>
                </div>";//when hover the photo display infos as popup

            $formattedHtml = '<div class="d-flex mt-2">' .
                $profileHtml .
                '<div class="mx-2">' .
                '<h6 class="mb-1">'.$fournisseur->name.
                '</h6>' .
                '<span class="text-muted">' . $fournisseur->email . '</span>';

            $formattedHtml .= '</div>' .
                '</div>';

            return [
                'id' => $fournisseur->id,
                'name' => $fournisseur->name,
                // 'entreprise' => $fournisseur->denomenation,
                // 'company' => $client->denomenation,
                'email' => $fournisseur->email,
                'phone' => $fournisseur->phone,
                'profile' => $formattedHtml,
                'created_at' => format_date($fournisseur->created_at, true),
                'updated_at' => format_date($fournisseur->updated_at, true),
                'actions' => $actions
            ];
        });

        return response()->json([
            'rows' => $fournisseurs->items(),
            'total' => $totalFournisseurs,
        ]);
        }

        // public function importExcelData(Request $request)
        // {
        //     $request->validate([
        //         'import_file' => [
        //             'required',
        //             'file'
        //         ],
        //     ]);

        //     // Retrieve the entreprise_id from the user or request
        //     $entrepriseId = auth()->user()->entreprise_id; // Example: from authenticated user

        //     // If `entreprise_id` is not set or needs to be obtained differently, adjust accordingly
        //     if (!$entrepriseId) {
        //         return redirect()->back()->with('error', 'Entreprise ID is required.');
        //     }

        //     // Import the Excel file
        //     Excel::import(new FournisseurImport($entrepriseId), $request->file('import_file'));
        //     // return redirect()->back()->with('status', 'Imported Successfully');
        //     Session::flash('message', 'Imported Successfully');
        //     return response()->json(['error'y=>false]);
        // }
}




