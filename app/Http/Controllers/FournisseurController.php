<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
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
use App\Models\Client;
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
        public function showForm()
        {
            return view('import.form');
        }
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

        public function step1(Request $request)
        {
            $file = $request->file('file');
            
            // Save the file to a temporary location in storage
            $path = $file->storeAs('temp', $file->getClientOriginalName());

            // Get the headings (first row) from the uploaded file
            $headings = Excel::toArray([], storage_path('app/' . $path))[0][0];

            // Define the database column titles (adjust as needed)
            $dbColumns = ['name', 'email', 'country','city','phone']; // Add your actual DB column names here

            return view('import.step2', compact('headings', 'dbColumns', 'path'));
        }

    
        public function step2(Request $request)
        {
            $path = $request->input('path');
            $mappings = $request->input('mappings');
        
            // Read all rows from the stored file
            $rows = Excel::toArray([], storage_path('app/' . $path))[0];
            $rows = array_slice($rows, 1);
            $rows = array_filter($rows, function($row) {
                return array_filter($row); // Keep rows that have at least one non-empty value
            });
        
            return view('import.step3', compact('rows', 'mappings', 'path'));
        }
    
        public function save(Request $request)
        {
            $data = $request->input('data');
            $path = $request->input('path');
        
            // Save data to the database
            foreach ($data as $row) {
                $fournisseur = Fournisseur::where('email', $row['email'])->first();
                if ($fournisseur) {
                    $fournisseur->update([
                        'name' => $row['name'],
                        'phone' => $row['phone'],
                        'city' => $row['city'],
                        'country' => $row['country'],
                    ]);
                } else {
                    Fournisseur::create([
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'city' => $row['city'],
                        'country' => $row['country'],
                        'entreprise_id' => $this->user->entrepriseId,
                    ]);        
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




