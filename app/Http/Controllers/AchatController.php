<?php
namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Document;
// use App\Models\achat_product;
use App\Models\Entreprise;
use App\Models\Forme_juridique;
use App\Models\fournisseur;
use App\Models\mouvements_stock;
use App\Models\ProdCategory;
use App\Models\Product;
use App\Services\DeletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


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
        // Validate initial form fields
        $formFields = $request->validate([
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'type_achat' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'status_payement' => 'required|in:paid,unpaid,partial',
            'tva' => 'nullable|numeric|min:0',
            'date_paiement' => 'nullable|date',
            'date_limit' => 'nullable|date',
            'reference' => 'required|string|max:255',
            'montant_ht' => 'nullable|numeric|min:0',
            'facture' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'devis' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        if(!$formFields['montant_ht'])
        {
            $formFields['montant_ht'] = $formFields['montant']/($formFields['tva']/100 +1);
        }
        // Additional validation based on type_achat
        if ($formFields['type_achat'] == 'Matériel/Produits') {
            $request->validate([
                'fournisseur_id' => 'required|exists:fournisseurs,id',
                'devis' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric|min:0',
            ], [
                'products.*.product_id.required' => 'The product field is required.',
                'products.*.quantity.required' => 'The quantity field is required.',
                'products.*.price.required' => 'The price field is required.',
            ]);
            // $formFields['product_id'] = $additionalFields['product_id'];
        }
        if($formFields['status_payement'] == 'partial')
        {
            $additionalFields = $request->validate([
                'montant_restant' => 'nullable|numeric|min:0',
                'montant_payée' => 'required|numeric|min:0|max:' . ($formFields['montant'] - 0.01),
            ], [
                'montant_payée.max' => 'The amount paid must be less than '. $formFields['montant'] ,
            ]);
            $formFields['montant_payée'] = $additionalFields['montant_payée'];

            if(!$additionalFields['montant_restant']){
                $formFields['montant_restant'] = $formFields['montant'] - $additionalFields['montant_payée'];
            }
            else{
                $formFields['montant_restant'] = $additionalFields['montant_restant'];
            }
            
        }

            
        $formFields['entreprise_id'] = $this->user->entreprise_id;
    
        // Handle file upload
        if ($request->hasFile('facture')) {
            $formFields['facture'] = $request->file('facture')->store('factures', 'public');
        }        
        if ($request->hasFile('devis')) {
            $formFields['devis'] = $request->file('devis')->store('devis', 'public');
        }

        // Create Achat instance
        $achat = Achat::create($formFields); 
        DB::beginTransaction();
        if ($achat && $formFields['type_achat'] == 'Matériel/Produits') {
            $productData1=[];
            foreach ($request->products as $productd) {
                if (!empty($productd['product_id'])) {
                    $productData1[$productd['product_id']] = [
                        'quantity' => $productd['quantity'],
                        'price' => $productd['price'],
                    ];
                }
                $product = Product::find($productd['product_id']);
                mouvements_stock::create([
                    'product_id'=>$product->id,
                    'achat_id'=>$achat->id,
                    'quantitéajoutée'=>$productd['quantity'],
                    'quantitéprecedente'=>$product->stock,
                    'date_mouvement'=>now(),
                    'type_mouvement'=>'entrée',
                    'reference'=>$achat->reference,
                ]);
                $product->stock += $productd['quantity'];
                $product->save();
            }
            $achat->products()->attach($productData1);
            $documentsFields = [];

            if ($request->hasFile('devis')) {
                $documentsFields[] = [
                    'type' => 'devis',
                    'devis' => $formFields['devis'],
                    'facture' => null,
                ];
            }
            
            if ($request->hasFile('facture')) {
                $documentsFields[] = [
                    'type' => 'facture',
                    'facture' => $formFields['facture'],
                    'devis' => null,
                ];
            }
            foreach ($documentsFields as $documentField) {
                $documentField['reference'] = $formFields['reference'];
                $documentField['from_to'] = $achat->fournisseur->name;
                $documentField['total_amount'] = $formFields['montant'];
                $documentField['remaining_amount'] = $formFields['status_payement'] == 'partial' ? $formFields['montant_restant'] : 0;
                $documentField['user'] = $this->user->first_name . ' ' . $this->user->last_name;           
                Document::create($documentField);
            }
        }
        DB::commit();
        Session::flash('message', 'Fournisseur created successfully.'.$formFields['type_achat']);
    
        return response()->json(['error' => false, 'id' => $achat->id]);
    }
    public function edit($id)
{
    $achat = Achat::with('products')->findOrFail($id);
    $entreprises = Entreprise::all();
    $products = Product::all();
    $fournisseurs = Fournisseur::all();
    $categories = ProdCategory::all();

    return view('achats.update_achats', [
        'achat' => $achat,
        'entreprises' => $entreprises,
        'products' => $products,
        'fournisseurs' => $fournisseurs,
        'categories' => $categories
    ]);
}

public function update(Request $request, $id)
{
    $achat = Achat::with('products')->findOrFail($id);

    // Validate form fields
    $formFields = $request->validate([
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'type_achat' => 'required|string|max:255',
        'montant' => 'required|numeric|min:0',
        'status_payement' => 'required|in:paid,unpaid,partial',
        'tva' => 'nullable|numeric|min:0',
        'date_paiement' => 'nullable|date',
        'date_limit' => 'nullable|date',
        'reference' => 'required|string|max:255',
        'montant_ht' => 'nullable|numeric|min:0',
    ]);

    if(!$formFields['montant_ht'])
    {
        $formFields['montant_ht'] = $formFields['montant'] / ($formFields['tva'] / 100 + 1);
    }

    // Additional validation based on type_achat
    if ($formFields['type_achat'] == 'Matériel/Produits') {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);
    }

    if($formFields['status_payement'] == 'partial')
    {
        $additionalFields = $request->validate([
            'montant_restant' => 'nullable|numeric|min:0',
            'montant_payée' => 'required|numeric|min:0|max:' . ($formFields['montant'] - 0.01),
        ], [
            'montant_payée.max' => 'The amount paid must be less than '. $formFields['montant'] ,
        ]);
        $formFields['montant_payée'] = $additionalFields['montant_payée'];

        if(!$additionalFields['montant_restant']){
            $formFields['montant_restant'] = $formFields['montant'] - $additionalFields['montant_payée'];
        }
        else{
            $formFields['montant_restant'] = $additionalFields['montant_restant'];
        }
    }

    // Handle file upload
    if ($request->hasFile('facture')) {
        // Delete old file if exists
        if ($achat->facture) {
            Storage::disk('public')->delete($achat->facture);
        }
        $formFields['facture'] = $request->file('facture')->store('factures', 'public');
    }
    if ($request->hasFile('devis')) {
        // Delete old file if exists
        if ($achat->facture) {
            Storage::disk('public')->delete($achat->devis);
        }
        $formFields['devis'] = $request->file('devis')->store('devis', 'public');
    }

    // Update Achat instance
    $achat->update($formFields);

    if ($achat && $formFields['type_achat'] == 'Matériel/Produits') {
        foreach ($request->products as $productData) {
            // Check if the product is already attached to the achat
            $existingProduct = $achat->products()->where('product_id', $productData['product_id'])->first();

            if ($existingProduct) {
                // Update the existing pivot entry
                $oldQuantity = $existingProduct->pivot->quantity;
                $achat->products()->updateExistingPivot($productData['product_id'], [
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price'],
                ]);
            } else {
                // Attach the new product to the achat
                $achat->products()->attach($productData['product_id'], [
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price'],
                ]);
                $oldQuantity = 0; // Since this is a new product, old quantity is 0
            }

            // Calculate the difference in quantity
            $quantityDifference = $productData['quantity'] - $oldQuantity;

            // Find the product to update its stock
            $product = Product::find($productData['product_id']);
            if($quantityDifference!=0){
                mouvements_stock::create([
                    'product_id' => $product->id,
                    'achat_id' => $achat->id,
                    'quantitéajoutée' => $quantityDifference,
                    'quantitéprecedente' => $product->stock,
                    'date_mouvement' => now(),
                    'type_mouvement' => $quantityDifference > 0 ? 'entrée' : 'sortie',
                    'reference' => $achat->reference,
                ]);
            }

            $product->stock += $quantityDifference;
            $product->save();
        }
    }

    Session::flash('message', 'Achat updated successfully.');

    return response()->json(['error' => false, 'id' => $achat->id]);
}
    public function destroy($id)
    {
        $achat = Achat::find($id);
        $response = DeletionService::delete(Achat::class, $id, 'achat'); 
        if ($achat->type_achat == 'Matériel/Produits') {
            $product = Product::findOrFail($achat->product_id);
            $product->stock -= $achat->P;
            $product->save();
        }
        // UserClientPreference::where('user_id', 'u_' . $id)->delete();
        return $response;
    }

public function list()
{
    $search = request('search');
    $sort = request('sort') ?: 'id';
    $order = request('order') ?: 'DESC';
    $type_achat_filter = request('type_achat_filter', '');
    $status_filter = request('status_filter', '');


    $query = Achat::query();
    // $fournisseurs=fournisseur::all();
    // Search functionality
    if ($search) {
        $query->where(function ($query) use ($search) {
            $query->where('type_achat', 'like', '%' . $search . '%')
                ->orWhere('status_payement', 'like', '%' . $search . '%')
                ->orWhere('facture', 'like', '%' . $search . '%')
                ->orWhere('tva', 'like', '%' . $search . '%')
                ->orWhere('date_paiement', 'like', '%' . $search . '%')
                ->orWhere('date_limit', 'like', '%' . $search . '%')
                ->orWhere('reference', 'like', '%' . $search . '%')
                ->orWhereHas('fournisseur', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('entreprise', function ($query) use ($search) {
                    $query->where('denomination', 'like', '%' . $search . '%');
                });
        });
    }     
    //  Status filtering
    if ($type_achat_filter !== '') {
        $query->where('type_achat', $type_achat_filter);
    }
    if ($status_filter !== '') {
        $query->where('status_payement', $status_filter);
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
            '<h6 class="mb-1">' . get_label('Supplier', 'Supplier') . ': ' . $achat->fournisseur->name .
            '</h6>' .
            '<span class="text-muted">'. get_label('entreprise', 'entreprise') . ': ' . $achat->entreprise->denomination . '</span>';

        $formattedHtml .= '</div>' .
            '</div>';

        return [
            'id' => $achat->id,
            'status_payement' => get_label($achat->status_payement, $achat->status_payement),
            // 'entreprise' => $fournisseur->denomenation,
            // 'company' => $client->denomenation,
            'montant' => $achat->montant,
            'type_achat' => get_label($achat->type_achat,$achat->type_achat),
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
