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
        // $fournisseurs = fournisseur::all();
        // $entreprises = Entreprise::all();
        // $formesjuridique= Forme_juridique::all();
        // $products = Product::all();
        // $achats = Achat::all();
        $achats = Achat::where('entreprise_id', $this->user->entreprise_id)->get();
        // dd($achats)
        // $visibleColumns = getUserPreferences('entreprises'); // Adjust this based on how you get user preferences


        return view('achats.achats',['achats'=>$achats]);
    }
    public function create(Request $request)
    {
        // $entreprises = Entreprise::all();
        $products = Product::where('entreprise_id', $this->user->entreprise_id)->get();
        $fournisseurs = fournisseur::where('entreprise_id', $this->user->entreprise_id)->get();
        $categories = ProdCategory::all();
        return view('achats.create_achats', ['products'=>$products,'fournisseurs'=> $fournisseurs,'categories'=>$categories]);
    }

    // Function to generate the next achat reference
private function generateAchaatReference()
{
    // Get the last achat with the highest reference
    $lastAchat = Achat::orderBy('reference', 'desc')->first();

    // If there is no previous reference, start with Achat_00000001
    if (!$lastAchat) {
        return 'Achat_00000001';
    }

    // Extract the hexadecimal part of the last reference
    $lastHex = substr($lastAchat->reference, 6); // Skip the 'Achat_' prefix

    // Convert the hexadecimal part to a decimal number, increment it, and then convert back to hexadecimal
    $nextHex = strtoupper(dechex(hexdec($lastHex) + 1));

    // Pad the new hex value to 8 characters (e.g., 00000001, 0000000A)
    $nextReference = str_pad($nextHex, 8, '0', STR_PAD_LEFT);

    // Return the new reference with the Achat_ prefix
    return 'Achat_' . $nextReference;
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

        ]);
        if(!$formFields['montant_ht'])
        {
            $formFields['montant_ht'] = $formFields['montant']/($formFields['tva']/100 +1);
        }
        // Additional validation based on type_achat
        if ($formFields['type_achat'] == 'Matériel/Produits') {
            $request->validate([
                'fournisseur_id' => 'required|exists:fournisseurs,id',
                'facture' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
                'devis' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
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
                    'reference'=>$this->generateAchaatReference(),
                ]);

                if (!empty($productd['product_id'])) {
                    $total_amount_achat = $productd['price'] * $productd['quantity'];
                }

                // $previous_quantity = $product->stock;
                $product->stock += $productd['quantity'];

                // Now handle stock and price updates based on achats (if purchase data is provided)

                // $previous_price = $product->price;

                $total_amount = $total_amount_achat + $product->total_amount;

                if ($product->stock > 0) {
                    $new_cmup_price =  $total_amount/ $product->stock;
                } else {
                    $new_cmup_price = $productd['price'];
                }
                $product->price = $new_cmup_price;
                $product->total_amount = $total_amount;
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
                $documentField['origin'] ='achat';
                $documentField['entreprise_id'] = $this->user->entreprise_id;
                Document::create($documentField);
            }
        }
        DB::commit();
        Session::flash('message', 'Fournisseur created successfully.');

        return response()->json(['error' => false, 'id' => $achat->id]);
    }
    public function edit($id)
{
    $achat = Achat::with('products')->findOrFail($id);
    // $entreprises = Entreprise::where('entreprise_id', $this->user->entreprise_id)->get();
    $products = Product::where('entreprise_id', $this->user->entreprise_id)->get();
    $fournisseurs = Fournisseur::where('entreprise_id', $this->user->entreprise_id)->get();
    $categories = ProdCategory::all();

    return view('achats.update_achats', [
        'achat' => $achat,
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


public function getachat($id)
{
    // Fetch the commande with the related products, user, and client
    $achat = Achat::with('products')->findOrFail($id);
    if (!$achat) {
        return response()->json(['error' => 'Commande not found'], 404);
    }

    // Prepare the response data
    $response = [
        'id' => $achat->id,
        'type_achat' => $achat->type_achat,
        'montant' => $achat->montant,
        'tva' => $achat->tva,
        'montant_ht' => $achat->montant_ht,
        'montant_payée' => $achat->montant_payée,
        'montant_restant' => $achat->montant_restant,
        'status_payement' => $achat->status_payement,
        'date_paiement' => $achat->date_paiement,
        'date_limit' => $achat->date_limit,
        'fournisseur' => $achat->fournisseur->name,
        'reference' => $achat->reference,
        'facture' => $achat->facture ? asset('storage/' . $achat->facture) : null,
        'devis' => $achat->devis ? asset('storage/' . $achat->devis) : null,
        'products' => $achat->products->map(function ($product) {
            return [
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price, // Include price
                'picture_url' => $product->photo ? asset('storage/' . $product->photo) : asset('storage/photos/no-image.jpg'),
            ];
        }),
    ];

    return response()->json($response);
}

public function list()
{
    $search = request('search');
    $sort = request('sort') ?: 'id';
    $order = request('order') ?: 'DESC';
    $status_filter = request('status', '');
    $type_achat_filter = request('type_achat', '');
    $fournisseur_filter = request('fournisseur', '');

    // Query for Achat with related fournisseur and products
    $query = Achat::with(['fournisseur', 'products'])
        ->where('entreprise_id', $this->user->entreprise_id);

    // Search functionality
    if ($search) {
        $query->where(function ($query) use ($search) {
            $query->where('reference', 'like', '%' . $search . '%')
                  ->orWhereHas('fournisseur', function ($query) use ($search) {
                      $query->where('name', 'like', '%' . $search . '%');
                  });
        });
    }

    // Filtering
    if ($status_filter !== '') {
        $query->where('status_payement', $status_filter);
    }
    if ($type_achat_filter !== '') {
        $query->where('type_achat', $type_achat_filter);
    }
    if ($fournisseur_filter !== '') {
        $query->where('fournisseur_id', $fournisseur_filter);
    }

    $totalAchats = $query->count();

    // Sorting and pagination
    $achats = $query->orderBy($sort, $order)
        ->paginate(request("limit"));

    // Formatting the output
    $formattedAchats = $achats->through(function ($achat) {

        // Fournisseur info
        $fournisseurProfileHtml = "<div class='avatar avatar-md pull-up' title='" . $achat->fournisseur->name . "'>
                                        <a href='/fournisseurs/profile/" . $achat->fournisseur->id . "'>
                                            <img src='" . ($achat->fournisseur->photo ? asset('storage/' . $achat->fournisseur->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                                        </a>
                                    </div>";

        // Products with price and quantity
        $productsHtml = "<div style='display: flex; flex-wrap: nowrap; align-items: center; overflow-x: auto;'>" .
            $achat->products->map(function ($product) {
                return "
                <div style='display: flex; align-items: center; margin-bottom: 10px;'>
                    <div class='avatar avatar-sm pull-up' title='" . $product->name . "' style='margin-right: 20px;'>
                        <a href='/products/info/" . $product->id . "'>
                            <img src='" . ($product->photo ? asset('storage/' . $product->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle' style='width: 50px; height: 50px;'>
                        </a>
                    </div>
                    <div style='white-space: nowrap;'>
                        <span style='font-weight: bold; font-size: 14px; color: #333;'>" . $product->name . "</span>
                        <br>
                        <span style='color: #888; font-size: 14px;'>Price:
                            <span style='color: #28a745;'>" . number_format($product->pivot->price, 2) . "</span>
                        </span>
                        <br>
                        <span style='color: #888; font-size: 14px;'>Qty:
                            <span style='font-weight: bold;'>" . $product->pivot->quantity . "</span>
                        </span>
                    </div>
                </div>";
            })->implode('') . "</div>";

        // Actions (edit, delete)
        $actions = '<a href="/achats/edit/' . $achat->id . '" title="' . get_label('update', 'Update') . '">
                        <i class="bx bx-edit mx-1"></i>
                    </a>';
        $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $achat->id . '" data-type="achats">
                        <i class="bx bx-trash text-danger mx-1"></i>
                    </button>';

        // Status badge
        $statusBadge = '<span class="badge ' . ($achat->status_payement == 'pending' ? 'bg-warning' :
            ($achat->status_payement == 'validated' ? 'bg-success' :
            ($achat->status_payement == 'cancelled' ? 'bg-danger' : 'bg-info'))) . '">' .
            get_label($achat->status_payement, $achat->status_payement) . '</span>';

            $profileHtml =
            '<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#commandeModal" class="me-2">' . // Margin End
                '<button type="button" class="btn btn-info btn-sm" ' .
                    'data-id="' . htmlspecialchars($achat->id) . '" ' .
                    'data-bs-toggle="tooltip" ' .
                    'data-bs-placement="left" ' .
                    'data-bs-original-title="' . htmlspecialchars(get_label('View Details', 'View Details')) . '">' .
                    '<i class="bx bx-expand"></i> ' . htmlspecialchars(get_label('View Details', 'View Details')) .
                '</button>' .
            '</a>' .

            '<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#paymentModal">' .
                '<button type="button" class="btn btn-success btn-sm" ' . // Changed to success
                    'data-id="' . htmlspecialchars($achat->id) . '" ' .
                    'data-bs-toggle="tooltip" ' .
                    'data-bs-placement="left" ' .
                    'data-bs-original-title="' . htmlspecialchars(get_label('Payment', 'Payment')) . '">' .
                    '<i class="bx bx-credit-card"></i> ' . htmlspecialchars(get_label('Payment', 'Payment')) .
                '</button>' .
            '</a>';

    //when hover the photo display infos as popup

            $formattedHtml = '<div class="d-flex mt-2">' .
                $profileHtml ;

            $formattedHtml .= '</div>';

                $fournisseur = '<div class="mx-2">' .
    '<h6 class="mb-1">' . get_label('Supplier', 'Supplier') . ': ' . $achat->fournisseur->name . '</h6>' .
    '<span class="text-muted">' . get_label('entreprise', 'entreprise') . ': ' . $achat->entreprise->denomination . '</span>' .
    '</div>';



    $devisButton = '_';

    // Check if a devis file exists and create the "Download Devis" button
    if (!empty($achat->devis)) {
        // Get the full path to the file, assuming it's stored in 'achat/devis' within 'storage/app/public'
        $filePath = asset('storage/' . $achat->devis);


        // Get the file name (with extension) to use in the download attribute
        $fileName = basename($achat->devis);

        // Create the download button using the stored devis path and the file name
        $devisButton = '
        <a href="' . $filePath . '" title="' . get_label('download_devis', 'Download Devis') . '" class="btn btn-sm btn-outline-primary" download="' . $fileName . '">
            <i class="bx bx-file"></i> ' . get_label('download_devis', 'Download Devis') . '
        </a>';
    }


    $factureButton = '_';
if (!empty($achat->facture)) {
    // Get the full path to the file, assuming it's stored in 'achat/facture' within 'storage/app/public'
    $filePath = asset('storage/' . $achat->facture);

    // Get the file name (with extension) to use in the download attribute
    $fileName = basename($achat->facture);

    // Create the download button using the stored facture path and the file name
    $factureButton = '
    <a href="' . $filePath . '" title="' . get_label('download_facture', 'Download Facture') . '" class="btn btn-sm btn-outline-primary" download="' . $fileName . '">
        <i class="bx bx-file"></i> ' . get_label('download_facture', 'Download Facture') . '
    </a>';
}

$achatPaymentStatus = '';

// Determine the badge color and label
if ($achat->status_payement == 'paid') {
    $achatPaymentStatus .= '<span class="badge bg-success">' .
        get_label($achat->status_payement, $achat->status_payement) .
        '</span> <small class="text-muted">(' .
        get_label('date_paiement', 'Date de Paiement') . ': ' .
        date('d/m/Y', strtotime($achat->date_paiement)) .
        ')</small>';
} elseif ($achat->status_payement == 'unpaid') {
    $achatPaymentStatus .= '<span class="badge bg-danger">' .
        get_label($achat->status_payement, $achat->status_payement) .
        '</span> <small class="text-muted">(' .
        get_label('date_limite', 'Date Limite') . ': ' .
        date('d/m/Y', strtotime($achat->date_limit)) .
        ')</small>';
} elseif ($achat->status_payement == 'partial') {
    $achatPaymentStatus .= '<span class="badge bg-warning">' .
        get_label($achat->status_payement, $achat->status_payement) .
        '</span> <small class="text-muted">(' .
        get_label('date_limite', 'Date Limite') . ': ' .
        date('d/m/Y', strtotime($achat->date_limit)) .
        ')</small>';
} else {
    $achatPaymentStatus .= '<span class="badge bg-info">' .
        get_label($achat->status_payement, $achat->status_payement) .
        '</span>';
}



        return [
            'id' => $achat->id,
            'profile' => $formattedHtml,
            'fournisseur'=> $fournisseur,
            'status_payement' =>  $achatPaymentStatus,
            'montant' => number_format($achat->montant, 2),
            'type_achat' => get_label($achat->type_achat, $achat->type_achat),
            'facture' => $factureButton,
            'devis' => $devisButton,
            'tva' => number_format($achat->tva, 2) . '%',
            'date_paiement' => $achat->date_paiement,
            'date_limit' => $achat->date_limit,
            'date_achat' => $achat->date_achat,
            'reference' => $achat->reference,
            'created_at' => format_date($achat->created_at, true),
            'updated_at' => format_date($achat->updated_at, true),
            'actions' => $actions,
            'products' => $productsHtml,
            'status' => $statusBadge,
        ];
    });

    return response()->json([
        'rows' => $formattedAchats->items(),
        'total' => $totalAchats,
    ]);
}

}
