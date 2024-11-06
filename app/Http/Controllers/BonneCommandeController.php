<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\BonDeCommande;
use App\Models\depot;
use App\Models\Document;
use App\Models\fournisseur;
use App\Models\mouvements_stock;
use App\Models\ProdCategory;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BonneCommandeController extends Controller
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


public function index()
{
    // Fetch only the `bon_de_commande` for the authenticated user's entreprise
    $bonDeCommandes = BonDeCommande::where('entreprise_id', $this->user->entreprise_id)->get();

    // Fetch fournisseurs, assuming there's a Fournisseur model
    $fournisseurs = fournisseur::where('entreprise_id', $this->user->entreprise_id)->get(); // Adjust if you need to filter the fournisseurs

    // Define an array of status values
    $statuses = [
        'pending' => get_label('pending', 'Pending'),
        'approved' => get_label('approved', 'Approved'),
        'rejected' => get_label('rejected', 'Rejected'),
    ];

    // Fetch type_achat values, assuming there's a TypeAchat model
    $typesAchat = [
        'Matériel/Produits' => get_label('Matériel/Produits', 'Materielle/Products'),
        'recherche/developpement' => get_label('recherche/developpement', 'Research/Development'),
        'investissements' => get_label('investissements', 'Investments'),
        'salaires/avantages sociaux' => get_label('salaires/avantages sociaux', 'Salaries/Social Benefits'),
        'mainetenances/amélioration' => get_label('mainetenances/amélioration', 'Maintenance/Improvement'),
    ];
 // Adjust if you need to filter the type_achats

    return view('achats.bonnecommande', [
        'bonDeCommandes' => $bonDeCommandes,
        'fournisseurs' => $fournisseurs,
        'statuses' => $statuses,
        'typesAchat' => $typesAchat,
    ]);
}


public function cancelBonCommande($id)
{
    // Find the BonDeCommande by ID
    $bonDeCommande = BonDeCommande::findOrFail($id);

    // Perform the cancellation logic, e.g., update the status
    $bonDeCommande->status = 'cancelled';
    $bonDeCommande->save();

    // Redirect back or to another page with a success message

    Session::flash('message', 'Bon de commande was cancelled successfully.');
    return response()->json(['error' => false, 'id' => $bonDeCommande->id]);
}



public function create(Request $request)
{
    // Fetch products, fournisseurs, and categories for the authenticated user's entreprise
    $products = Product::where('entreprise_id', $this->user->entreprise_id)->get();
    $fournisseurs = fournisseur::where('entreprise_id', $this->user->entreprise_id)->get();
    $categories = ProdCategory::all();

    // Return the view for creating a bon de commande
    return view('achats.create_bondecommande', [
        'products' => $products,
        'fournisseurs' => $fournisseurs,
        'categories' => $categories
    ]);
}
public function list()
{
    $search = request('search');
    $sort = request('sort') ?: 'id';
    $order = request('order') ?: 'DESC';
    $status_filter = request('status', '');
    $type_achat_filter = request('type_achat', '');
    $fournisseur_filter = request('fournisseur', '');

    // Query for BonDeCommande with related fournisseur and products
    $query = BonDeCommande::with(['fournisseur', 'products'])
        ->where('entreprise_id', $this->user->entreprise->id);

    // Search functionality
    if ($search) {
        $query->where(function ($query) use ($search) {
            $query->where('reference', 'like', '%' . $search . '%')
                  ->orWhereHas('fournisseur', function ($query) use ($search) {
                      $query->where('name', 'like', '%' . $search . '%');
                  });
        });
    }

    // Status filtering
    if ($status_filter !== '') {
        $query->where('status', $status_filter);
    }

    if ($type_achat_filter !== '') {
        $query->where('type_achat', $type_achat_filter);
    }

    if ($fournisseur_filter !== '') {
        $query->where('fournisseur_id', $fournisseur_filter);
    }

    $totalBonDeCommandes = $query->count();

    // Sorting and pagination
    $bonDeCommandes = $query->orderBy($sort, $order)
        ->paginate(request("limit"));

    // Formatting the output
    $formattedBonDeCommandes = $bonDeCommandes->through(function ($bonDeCommande) {

        // Fournisseur info
        $fournisseurProfileHtml = "<div class='avatar avatar-md pull-up' title='" . $bonDeCommande->fournisseur->name . "'>
                                        <a href='/fournisseurs/profile/" . $bonDeCommande->fournisseur->id . "'>
                                            <img src='" . ($bonDeCommande->fournisseur->photo ? asset('storage/' . $bonDeCommande->fournisseur->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                                        </a>
                                    </div>";

        // Products with price and image
        $productsHtml = "<div style='display: flex; flex-wrap: nowrap; align-items: center; overflow-x: auto;'>" .
            $bonDeCommande->products->map(function ($product) {
                return "
                <div style='display: flex; align-items: center; margin-bottom: 10px;'>
                    <div class='avatar avatar-sm pull-up' title='" . $product->name . "' style='margin-right: 20px;'>
                        <a href='/products/info/" . $product->id . "'>
                            <img src='" . ($product->photo ? asset('storage/' . $product->photo) : asset('storage/photos/no-image.jpg')) . "'
                            alt='Avatar' class='rounded-circle' style='width: 50px; height: 50px;'>
                        </a>
                    </div>
                    <div style='white-space: nowrap;'>
                        <span style='font-weight: bold; font-size: 14px; color: #333;'>" . $product->name . "</span>
                        <br>
                        <span style='color: #888; font-size: 14px;'>Purchase price :
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
        $actions = '<a href="/bondecommande/edit/' . $bonDeCommande->id . '" title="' . get_label('update', 'Update') . '">
                        <i class="bx bx-edit mx-1"></i>
                    </a>';

        $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $bonDeCommande->id . '" data-type="bondecommande">
                        <i class="bx bx-trash text-danger mx-1"></i>
                    </button>';

        // Status badge
        $bonDeCommandeStatus = '<span class="badge ' .
            ($bonDeCommande->status == 'pending' ? 'bg-warning' :
            ($bonDeCommande->status == 'validated' ? 'bg-success' :
            ($bonDeCommande->status == 'cancelled' ? 'bg-danger' : 'bg-info'))) .
            '">' . $bonDeCommande->status . '</span>';

        // Documents (Devis, Facture)

        $manage = '<div class="text-center">
        <div class="btn-group" role="group" aria-label="Manage Buttons">
            <a href="' . route('bon-commande.generate', $bonDeCommande->id) . '" title="' . get_label('download_bon', 'Download Bon de Commande') . '" class="btn btn-sm btn-outline-info mx-1">
                <i class="bx bx-download"></i> ' . get_label('download', 'Download') . '
            </a>';

    if ($bonDeCommande->status == 'pending') {
        $manage .= '<a href="' . route('bon_commande.manage', $bonDeCommande->id) . '" title="' . get_label('manage', 'Manage Bon de Commande') . '" class="btn btn-sm btn-outline-success mx-1">
            <i class="bx bx-cog"></i> ' . get_label('manage', 'Manage') . '
        </a>';
    }

    $manage .= '</div></div>';





    $devisButton = '_';

// Check if a devis file exists and create the "Download Devis" button
if (!empty($bonDeCommande->devis)) {
    // Get the full path to the file, assuming it's stored in 'achat/devis' within 'storage/app/public'
    $filePath = asset('storage/' . $bonDeCommande->devis);


    // Get the file name (with extension) to use in the download attribute
    $fileName = basename($bonDeCommande->devis);

    // Create the download button using the stored devis path and the file name
    $devisButton = '
    <a href="' . $filePath . '" title="' . get_label('download_devis', 'Download Devis') . '" class="btn btn-sm btn-outline-primary" download="' . $fileName . '">
        <i class="bx bx-file"></i> ' . get_label('download_devis', 'Download Devis') . '
    </a>';
}




           $tvaFormatted = number_format($bonDeCommande->tva, 2) . '%';
        // Add tva to the returned data
        return [
            'manage' => $manage,
            'reference' => $bonDeCommande->reference,
            'fournisseur' => $fournisseurProfileHtml . ' ' . $bonDeCommande->fournisseur->name,
            'devis' => $devisButton,  // Added this line for the devis button
            'products' => $productsHtml,
            'type_achat' => $bonDeCommande->type_achat,
            'montant' => number_format($bonDeCommande->montant, 2),
            'tva' => $tvaFormatted,  // Added this line
            'status' => $bonDeCommandeStatus,
            'date_commande' => $bonDeCommande->date_commande ? Carbon::parse($bonDeCommande->date_commande)->format('Y-m-d') : 'N/A',
            'created_at' => format_date($bonDeCommande->created_at, true),
            'updated_at' => format_date($bonDeCommande->updated_at, true),
            'actions' => $actions
        ];
    });

    return response()->json([
        "rows" => $formattedBonDeCommandes->items(),
        "total" => $totalBonDeCommandes,
    ]);
}


public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'date_commande' => 'required|date',
        // Validate existing products
        'products.*.product_id' => 'nullable|exists:products,id|distinct',
        'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
        'products.*.price' => 'required_with:products.*.product_id|numeric|min:0',
        // Validate new products
        'new_products.*.name' => 'nullable|string',
        'new_products.*.category_id' => 'nullable|exists:prod_categories,id',
        'new_products.*.quantity' => 'required_with:new_products.*.name|integer|min:1',
        'new_products.*.price' => 'required_with:new_products.*.name|numeric|min:0',
        'tva' => 'required|in:0,7,10,14,16,20', // Validate the tva input
        'devis' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:2048', // Optional file upload with size limit
    ]);

    // Check if at least one product (existing or new) has been provided
    $hasExistingProducts = !empty($request->products);
    $hasNewProducts = !empty($request->new_products);
    if (!$hasExistingProducts && !$hasNewProducts) {
        return redirect()->back()->withErrors(['products' => 'You must add at least one existing or new product.']);
    }

    // Generate the next reference
    $reference = $this->generateReference();

    // Initialize total amounts
    $totalAmountHT = 0; // Total amount without TVA
    $totalAmountTTC = 0; // Total amount with TVA

    $devisPath = null;


    if ($request->hasFile('devis')) {
        $extension = $request->file('devis')->getClientOriginalExtension();
        $filename = 'Devis_' . $reference . '.' . $extension;

        // Store the file with the correct extension
        $devisPath = $request->file('devis')->store('achat/devis', 'public');

    }


    // Create the bon de commande
    $bonCommande = BonDeCommande::create([
        'fournisseur_id' => $request->fournisseur_id,
        'date_commande' => $request->date_commande,
        'entreprise_id' => $this->user->entreprise->id,
        'reference' => $reference, // Generated reference
        'type_achat' => $request->type_achat,
        'status' => 'pending',
        'montant' => 0, // This will be updated later
        'montant_ht' => 0,
        'tva' => $request->tva, // Set the tva value from the request
        'devis' => $devisPath,
    ]);

    // Save existing products and calculate total amounts
    if ($hasExistingProducts) {
        foreach ($request->products as $product) {
            if (!empty($product['product_id'])) {
                // Attach the existing product to the bon_commande
                $bonCommande->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);

                // Add to the total amount without TVA (montant_ht)
                $totalAmountHT += $product['quantity'] * $product['price'];
            }
        }
    }

    // Save new products and calculate total amounts
    if ($hasNewProducts) {
        foreach ($request->new_products as $newProduct) {
            if (!empty($newProduct['name'])) {
                // Create a new product
                $createdProduct = Product::create([
                    'name' => $newProduct['name'],
                    'reference' => $this->generateProductReference(),
                    'entreprise_id' => $this->user->entreprise->id,
                    'product_category_id' => $newProduct['category_id'],
                    'price' => 0,
                ]);

                // Attach the new product to the bon_commande
                $bonCommande->products()->attach($createdProduct->id, [
                    'quantity' => $newProduct['quantity'],
                    'price' => $newProduct['price'],
                ]);

                // Add to the total amount without TVA (montant_ht)
                $totalAmountHT += $newProduct['quantity'] * $newProduct['price'];
            }
        }
    }

    // Calculate the total amount with TVA
    $totalAmountTTC = $totalAmountHT + ($totalAmountHT * ($request->tva / 100));

    // Update the bon de commande with the total amounts
    $bonCommande->update([
        'montant' => $totalAmountTTC,
        'montant_ht' => $totalAmountHT,
    ]);

    Session::flash('message', 'Bon de commande created successfully.');
    return response()->json(['error' => false, 'id' => $bonCommande->id]);
}

public function generateBonCommande($id)
{
    // Fetch the "Bon de Commande" with related products
    $bonCommande = BonDeCommande::with('products')->findOrFail($id);

    // Get the entreprise information of the logged-in user
    $entreprise = $this->user->entreprise;

    // Load the PDF view for "Bon de Commande"
    $pdf = Pdf::loadView('pdf.boncmd', compact('bonCommande', 'entreprise'));

    // Create a filename for the PDF
    $pdfname = 'bon_commande-' . $bonCommande->id .'_'.$bonCommande->reference.'.pdf';

    // Stream the PDF for download
    return $pdf->stream($pdfname);
}



// Function to generate the next reference
private function generateReference()
{
    // Get the last bon de commande with the highest reference
    $lastBonCommande = BonDeCommande::orderBy('reference', 'desc')->first();

    // If there is no previous reference, start with BonCmd_00000001
    if (!$lastBonCommande) {
        return 'BonCmd_00000001';
    }

    // Extract the hexadecimal part of the last reference
    $lastHex = substr($lastBonCommande->reference, 7); // Skip the 'BonCmd_' prefix

    // Convert the hexadecimal part to a decimal number, increment it, and then convert back to hexadecimal
    $nextHex = strtoupper(dechex(hexdec($lastHex) + 1));

    // Pad the new hex value to 8 characters (e.g., 00000001, 0000000A)
    $nextReference = str_pad($nextHex, 8, '0', STR_PAD_LEFT);

    // Return the new reference with the BonCmd_ prefix
    return 'BonCmd_' . $nextReference;
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


public function storeValidated(Request $request)
{
    // Validate the request
    $request->validate([
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'marge' => 'required|numeric|min:0',
        'date_achat' => 'required|date',
        // Validate existing products
        'products.*.product_id' => 'nullable|exists:products,id|distinct',
        'products.*.depot_id' => 'required|exists:depots,id',
        'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
        'products.*.price' => 'required_with:products.*.product_id|numeric|min:1',
        // Validate new products
        'tva' => 'required|in:0,7,10,14,16,20', // Validate the tva input
        'facture' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:2048', // Optional facture file upload
    ]);

    // Check if at least one product (existing or new) has been provided
    $hasExistingProducts = !empty($request->products);
    $hasNewProducts = !empty($request->new_products);
    if (!$hasExistingProducts && !$hasNewProducts) {
        return redirect()->back()->withErrors(['products' => 'You must add at least one existing or new product.']);
    }

    // Generate the next reference
    $reference = $this->generateAchaatReference(); // Assuming you have a reference generator

    // Initialize total amounts
    $totalAmountHT = 0; // Total amount without TVA
    $totalAmountTTC = 0; // Total amount with TVA

    // Handle file upload for facture
    $facturePath = null;
    if ($request->hasFile('facture')) {
        $extension = $request->file('facture')->getClientOriginalExtension();
        $filename = 'Facture_' . $reference . '.' . $extension;
        $facturePath = $request->file('facture')->storeAs('achat/factures', $filename, 'public');
    }

    // Handle file upload for devis, or use the existing path from hidden input
    $devisPath = $request->input('boncmddevis'); // Get value from hidden input

    $bonnecommandeid = $request->input('bonnecommandeid');


    // Create the achat
    $achat = Achat::create([
        'fournisseur_id' => $request->fournisseur_id,
        'date_achat' => $request->date_achat,
        'entreprise_id' => $this->user->entreprise->id,
        'reference' => $reference, // Generated reference
        'type_achat' => "Matériel/Produits",
        'status_payement' => 'unpaid',
        'marge' => $request->marge,
        'montant' => 0, // This will be updated later
        'montant_ht' => 0,
        'tva' => $request->tva, // Set the TVA value from the request
        'facture' => $facturePath,
        'devis' => $devisPath, // Set the value of devis
    ]);

    // Save existing products and calculate total amounts

      // Retrieve the margin from the request (assuming it's passed as a percentage)
    $marge = $request->input('marge', 0) / 100;
    $tva = $request->input('tva', 0) / 100;// Convert percentage to decimal (e.g., 15% => 0.15)

    foreach ($request->products as $product) {
        if (!empty($product['product_id'])) {
            $productModel = Product::find($product['product_id']);
            $depot = Depot::findOrFail($product['depot_id']);
            $quantity = $product['quantity'];
            $basePrice = $product['price'];

            // Adjust the price based on the margin for updating productModel price
            $adjustedPrice = $basePrice + ($basePrice * $marge) + ($basePrice * $tva); // price + (price * marge)
            $amountHT = $quantity * $basePrice; // For achat, use the base price without margin

            // Attach the product to the achat with quantity and base price (without margin)
            $achat->products()->attach($productModel, [
                'quantity' => $quantity,
                'price' => $basePrice, // Use the base price without margin here
                'depot_id' => $depot->id,
            ]);
            $depotProduct = $depot->products()->where('product_id', $productModel->id)->first();
            if ($depotProduct) {
                $depot->products()->updateExistingPivot($productModel->id, [
                    'quantity' => DB::raw("quantity + {$quantity}")
                ]);
            } else {
                $depot->products()->attach($productModel->id, ['quantity' => $quantity]);
            }
            $bonDeCommande = BonDeCommande::findOrFail($bonnecommandeid);

            // Update the desired column (e.g., 'status' column)
            $bonDeCommande->status = 'validated';  // Change 'status' to the column you want to update

            // Save the changes to the database
            $bonDeCommande->save();


            mouvements_stock::create([
                'product_id'=>$productModel->id,
                'achat_id'=>$achat->id,
                'quantitéajoutée'=>$quantity,
                'quantitéprecedente'=>$productModel->stock,
                'date_mouvement'=>now(),
                'type_mouvement'=>'entrée',
                'reference'=> $reference,
                'depot_id' => $depot->id,
            ]);

            // Update total amounts for the achat
            $totalAmountHT += $amountHT;

            // Update stock and price based on the current stock and CUMP method
            if ($productModel->stock == 0 || $productModel->price == 0) {
                // If stock or price is zero, update stock and set the product price to the adjusted price (with margin)

                if ($productModel->price == 0) {
                    $productModel->prev_price = $adjustedPrice;
                } else {
                    // If the price is different from zero, set prev_price to the current price
                    $productModel->prev_price = $productModel->price;
                }

                $productModel->stock += $quantity;
                $productModel->price = $adjustedPrice; // Set the new price with margin included
            } else {
                // Apply the CUMP method:

                $productModel->prev_price = $productModel->price;

                $oldStockValue = $productModel->stock * $productModel->price;
                $newPurchaseValue = $adjustedPrice * $quantity;
                $newTotalStock = $productModel->stock + $quantity;

                // Update the stock
                $productModel->stock = $newTotalStock;

                // Calculate the new CUMP (average price)
                $productModel->price = ($oldStockValue + $newPurchaseValue) / $newTotalStock;
            }

            // Save the updated product data
            $productModel->save();
        }
    }
    // Calculate total amount with TVA
    $totalAmountTTC = $totalAmountHT * (1 + $request->tva / 100);

    // Update achat with total amounts
    $achat->update([
        'montant_ht' => $totalAmountHT,
        'montant' => $totalAmountTTC,
    ]);
    Document::create([
        'type' => 'facture',
        'facture' => $facturePath,
        'origin' => 'achat',
        'entreprise_id'=>$this->user->entreprise_id,
        'reference' => $achat->id . "-" . $achat->type_achat,
        'from_to' => "fournsisur : " . $achat->client_id . "-" . $achat->fournisseur->name ,
        'total_amount' => $achat->total_amount,
        'user' => $this->user->first_name . ' ' . $this->user->last_name,
    ]);

    return redirect()->route('achats.index')->with('success', 'Achat created successfully.');
}



public function manage($id)
{
    $bonDeCommande = BonDeCommande::with(['fournisseur', 'products'])->findOrFail($id);
    $fournisseurs = Fournisseur::where('entreprise_id', $this->user->entreprise_id)->get(); // Assuming you're passing this to the view
    $products = Product::where('entreprise_id', $this->user->entreprise_id)->get();// Assuming you're passing this to the view
    $depots= depot::where('entreprise_id', $this->user->entreprise_id)->get();
    return view('achats.manage', [
        'bonDeCommande' => $bonDeCommande,
        'fournisseurs' => $fournisseurs,
        'products' => $products,
        'depots' => $depots,
    ]);
}






}


