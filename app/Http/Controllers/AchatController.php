<?php
namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Entreprise;
use App\Models\Forme_juridique;
use App\Models\fournisseur;
use App\Models\ProdCategory;
use App\Models\Product;
use App\Services\DeletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AchatController extends Controller
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
        $products = Product::all();
        // $visibleColumns = getUserPreferences('entreprises'); // Adjust this based on how you get user preferences
        return view('achats.achats',['fournisseurs'=> $fournisseurs,'entreprises'=> $entreprises,'fomesJuridique'=> $formesjuridique,'products'=>$products]);
    }
    public function create(Request $request)
    {
        $entreprises = Entreprise::all();
        $products = Product::all();
        $fournisseurs = fournisseur::all();
        $categories = ProdCategory::all();

        return view('achats.create_achats', ['entreprises' => $entreprises,'products'=>$products,'fournisseurs'=> $fournisseurs,'categories'=>$categories]);
    }
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'type_achat' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'status_payement' => 'required|in:paid,unpaid',
            'tva' => 'nullable|numeric|min:0',
            'date_paiement' => 'nullable|date',
            'date_limit' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ]);
        $formFields['entreprise_id'] = $this->user->entreprise_id;
        if ($request->hasFile('facture')) {
            $formFields['facture'] = $request->file('facture')->store('factures', 'public');
        }
        $achat = Achat::create($formFields);
        Session::flash('message', 'Fournisseur created successfully.');
        // Session::flash('message', 'Product created successfully.');
        return response()->json(['error' => false, 'id' => $achat->id]);
    }
    public function edit($id)
    {
        $fournisseurs = Fournisseur::all();
        $entreprises = Entreprise::all();
        $achat = Achat::find($id);
        return view('achats.update_achats', ['fournisseurs' => $fournisseurs, 'entreprises' => $entreprises, 'achat' => $achat]);
    }
    public function update(Request $request, $id)
    {
        // Find the achat by ID
        $achat = Achat::findOrFail($id);

        // Validate the request data
        $formFields = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'type_achat' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'status_payement' => 'required|in:paid,unpaid',
            'tva' => 'nullable|numeric|min:0',
            'date_paiement' => 'nullable|date',
            'date_limit' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ]);

        // Update entreprise_id if needed
        $formFields['entreprise_id'] = $this->user->entreprise_id;

        // Check if a new facture file is uploaded
        if ($request->hasFile('facture')) {
            // Delete the old facture file if it exists
            if ($achat->facture) {
                Storage::disk('public')->delete($achat->facture);
            }
            // Store the new facture file
            $formFields['facture'] = $request->file('facture')->store('factures', 'public');
        }

        // Update the achat record
        $achat->update($formFields);

        // Flash message
        Session::flash('message', 'Achat updated successfully.');
            return response()->json(['error' => false]);
    }

    public function destroy($id)
    {
        $response = DeletionService::delete(Achat::class, $id, 'achat');
        // UserClientPreference::where('user_id', 'u_' . $id)->delete();

        return $response;
    }

    public function list()
    {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';

        $query = Achat::query();
        // $fournisseurs=fournisseur::all();
        // Search functionality
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('type_achat', 'like', '%' . $search . '%')
                    ->orWhere('status_payement', 'like', '%' . $search . '%')
                    ->orWhere('facture', 'like', '%' . $search . '%');
            });
        }

        $totalachats = $query->count();

        // $fournisseurs = $query->orderBy($sort, $order)
        //     ->paginate(request("limit"));
        $achats = $query->select('achats.*')
        ->leftJoin('entreprises', 'achats.entreprise_id', '=', 'entreprises.id')
        ->leftJoin('fournisseurs', 'achats.fournisseur_id', '=', 'fournisseurs.id')
        ->orderBy($sort, $order)
        ->paginate(request('limit'));

        // $fournisseurs = $fournisseurs->select('fournisseurs.*')
        // ->distinct()
        // ->orderBy($sort, $order)
        // ->paginate(request('limit'))
        // ->through(function ($fournisseur)
        $achats = $achats->through(function ($achat){
            $actions = '';

                $actions .= '<a href="/achats/edit/' . $achat->id . '" title="' . get_label('update', 'Update') . '">' .
                    '<i class="bx bx-edit mx-1"></i>' .
                    '</a>';



                $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $achat->id . '" data-type="achats">' .
                    '<i class="bx bx-trash text-danger mx-1"></i>' .
                    '</button>';


            $actions = $actions ?: '-';

            $profileHtml = "<div class='avatar avatar-md pull-up' title='" . $achat->fournisseur->name. " '>
                <a href='/clients/profile/" . $achat->id . "'>
                </a>
                </div>";//when hover the photo display infos as popup

            $formattedHtml = '<div class="d-flex mt-2">' .
                $profileHtml .
                '<div class="mx-2">' .
                '<h6 class="mb-1">fournisseur: '.$achat->fournisseur->name.
                '</h6>' .
                '<span class="text-muted">entreprise: ' . $achat->entreprise->denomination . '</span>';

            $formattedHtml .= '</div>' .
                '</div>';

            return [
                'id' => $achat->id,
                'status_payement' => $achat->status_payement,
                // 'entreprise' => $fournisseur->denomenation,
                // 'company' => $client->denomenation,
                'montant' => $achat->montant,
                'type_achat' => $achat->type_achat,
                'profile' => $formattedHtml,
                'facture' => $achat->facture,
                'tva' => $achat->tva,
                'date_paiement' => $achat->date_paiement,
                'date_limit' => $achat->date_limit,
                'reference' => $achat->reference,
                'created_at' => format_date($achat->created_at, true),
                'updated_at' => format_date($achat->updated_at, true),
                'actions' => $actions
            ];
        });

        return response()->json([
            'rows' => $achats->items(),
            'total' => $totalachats,
        ]);

    }
}
