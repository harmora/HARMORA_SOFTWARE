<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Forme_juridique;
use App\Models\fournisseur;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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
    //
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
            'entreprise_id' => 'nullable|exists:entreprises,id'
        ]);

        $fournisseur = Fournisseur::create($formFields);
        Session::flash('message', 'Fournisseur created successfully.');
        return response()->json(['error' => false, 'id' => $fournisseur->id]);
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
            return response()->json(['error' => false, 'id' => $fournisseur->id]);
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
}