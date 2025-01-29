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
        // $fournisseurs = Fournisseur::where('entreprise_id', $this->user->entreprise_id)->get();
        $fournisseurs=$this->user->entreprise->fournisseur;
        $entreprises = Entreprise::all();

        // $formesjuridique= Forme_juridique::all();
        $visibleColumns = getUserPreferences('fournisseurs'); // Adjust this based on how you get user preferences
        return view('fournisseurs.fournisseurs',['fournisseurs'=> $fournisseurs]);
    }

    public function create()
    {
        // $entreprises = Entreprise::all();
        return view('fournisseurs.create_fournisseurs');
    }
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:fournisseurs,email',
            'phone' => 'nullable',
            'city' => 'nullable', 
            'country' => 'nullable',
            'country_code' => 'nullable',
        ]);

        if ($request->hasFile('logo')) {
            $formFields['photo'] = $request->file('logo')->store('photos', 'public');
        } else {
            $formFields['photo'] = 'photos/no-supplier.jpg';
        }
        $formFields['entreprise_id'] = $this->user->entreprise_id;

        $fournisseur = Fournisseur::create($formFields);
        Session::flash('message', 'Fournisseur created successfully.');
        return response()->json(['error' => false, 'id' => $fournisseur->id]);
        }



        public function edit($id)
        {
            $fournisseur = Fournisseur::find($id);
            // $entreprises = Entreprise::all();
            return view('fournisseurs.update_fournisseurs', ['fournisseur' => $fournisseur]);
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
                // 'entreprise_id' => 'nullable|exists:entreprises,id'
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
        public function getfournisseur($id)
        {
            // Fetch the commande with the related products, user, and client
            $fournisseur = Fournisseur::findOrFail($id);
            if (!$fournisseur) {
                return response()->json(['error' => 'Commande not found'], 404);
            }

            // Prepare the response data
            $response = [
                'id' => $fournisseur->id,
                'name' => $fournisseur->name,
                'email' => $fournisseur->email,
                'phone' => $fournisseur->phone,
                'city' => $fournisseur->city,
                'country' => $fournisseur->country,

            ];

            return response()->json($response);
        }

        public function list()
        {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';
        $fournisseur_status_filter = request('fournisseur_status_filter', '');
        $query = Fournisseur::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
                //  Status filtering
         if ($fournisseur_status_filter !== '') {
             $query->where('fournisseurs.city', $fournisseur_status_filter);
         }


         $totalFournisseurs = $query->where('fournisseurs.entreprise_id', $this->user->entreprise_id)->count();

        // $fournisseurs = $query->orderBy($sort, $order)
        //     ->paginate(request("limit"));
        $fournisseurs = $query->select('fournisseurs.*')
        ->leftJoin('entreprises', 'fournisseurs.entreprise_id', '=', 'entreprises.id')
        ->where('fournisseurs.entreprise_id', $this->user->entreprise_id)
        ->orderBy($sort, $order)
        ->paginate(request('limit'));

        $fournisseurs = $fournisseurs->through(function ($fournisseur){
            $actions = '';

                $actions .= '<a href="/fournisseurs/edit/' . $fournisseur->id . '" title="' . get_label('update', 'Update') . '">' .
                    '<i class="bx bx-edit mx-1"></i>' .
                    '</a>';



                $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $fournisseur->id . '" data-type="fournisseurs">' .
                    '<i class="bx bx-trash text-danger mx-1"></i>' .
                    '</button>';


            $actions = $actions ?: '-';

            //when hover the photo display infos as popup
            $profileHtml = "<div class='avatar avatar-md pull-up' title='" . htmlspecialchars($fournisseur->name) . "'>
            <a href='javascript:void(0);' data-bs-toggle='modal' data-bs-target='#commandeModal' 
               data-id='" . htmlspecialchars($fournisseur->id) . "' data-bs-toggle='tooltip' 
               data-bs-placement='left' data-bs-original-title='" . htmlspecialchars(get_label('View Details', 'View Details')) . "'>
                <img src='" . ($fournisseur->photo ? asset('storage/' . $fournisseur->photo) : asset('storage/photos/no-supplier.jpg')) . "' alt='Avatar' class='rounded-circle' style='cursor: pointer;' data-id='" . htmlspecialchars($fournisseur->id) . "'>
            </a>
        </div>";
        
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

}




