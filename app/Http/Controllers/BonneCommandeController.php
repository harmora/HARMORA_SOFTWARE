<?php

namespace App\Http\Controllers;

use App\Models\BonDeCommande;
use App\Models\fournisseur;
use App\Models\ProdCategory;
use App\Models\Product;
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
        $documentsHtml = '<div class="mb-3" style="display: flex; justify-content: center; align-items: center;">
            <a class="me-2">
              pdf
            </a>';

        if ($bonDeCommande->status == "validated") {
            $documentsHtml .= '<a class="me-2">
                    <button id="generateFactureButton-' . $bonDeCommande->id . '" data-url="' . route('facture.pdf', $bonDeCommande->id) . '" type="button" class="btn btn-sm btn-primary">
                        ' . get_label('facture', 'Facture') . ' <i class="bx bx-dollar"></i>
                    </button>
                </a>';
        }

        $documentsHtml .= '</div>';

        $manage = '<div class="btn-group" role="group" aria-label="Manage Buttons">
              <a href="/bondecommande/download/' . $bonDeCommande->id . '" title="' . get_label('download_bon', 'Download Bon de Commande') . '" class="btn btn-sm btn-outline-info mx-1">
                  <i class="bx bx-download"></i> ' . get_label('download', 'Download') . '
              </a>
              <a href="/bondecommande/manage/' . $bonDeCommande->id . '" title="' . get_label('manage', 'Manage Bon de Commande') . '" class="btn btn-sm btn-outline-success mx-1">
                  <i class="bx bx-cog"></i> ' . get_label('manage', 'Manage') . '
              </a>
           </div>';


           return [
            'manage' => $manage,
            'reference' => $bonDeCommande->reference,
            'fournisseur' => $fournisseurProfileHtml . ' ' . $bonDeCommande->fournisseur->name,
            'products' => $productsHtml,
            'type_achat' => $bonDeCommande->type_achat,
            'montant' => number_format($bonDeCommande->montant, 2),
            'status' => $bonDeCommandeStatus,
            'date_commande' => $bonDeCommande->date_commande ? Carbon::parse($bonDeCommande->date_commande)->format('Y-m-d') : 'N/A',
            'created_at' => format_date($bonDeCommande->created_at, true),
            'updated_at' => format_date($bonDeCommande->updated_at, true),
            'actions' => $actions,
            'documents' => $documentsHtml
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
        'products.*.product_id' => 'nullable|exists:products,id',
        'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
        'products.*.price' => 'required_with:products.*.product_id|numeric|min:0',
        // Validate new products
        'new_products.*.name' => 'nullable|string',
        'new_products.*.category_id' => 'nullable|exists:prod_categories,id',
        'new_products.*.quantity' => 'required_with:new_products.*.name|integer|min:1',
        'new_products.*.price' => 'required_with:new_products.*.name|numeric|min:0',
    ]);

    // Check if at least one product (existing or new) has been provided
    $hasExistingProducts = !empty($request->products);
    $hasNewProducts = !empty($request->new_products);
    if (!$hasExistingProducts && !$hasNewProducts) {
        return redirect()->back()->withErrors(['products' => 'You must add at least one existing or new product.']);
    }

    // Generate the next reference
    $reference = $this->generateReference();

    // Initialize the total amount
    $totalAmount = 0;

    // Create the bon de commande
    $bonCommande = BonDeCommande::create([
        'fournisseur_id' => $request->fournisseur_id,
        'date_commande' => $request->date_commande,
        'entreprise_id' => $this->user->entreprise->id,
        'reference' => $reference, // Generated reference
        'type_achat' => $request->type_achat,
        'status' => 'pending',
        'montant' => 0, // This will be updated later
    ]);

    // Save existing products and calculate total amount
    if ($hasExistingProducts) {
        foreach ($request->products as $product) {
            if (!empty($product['product_id'])) {
                // Attach the existing product to the bon_commande
                $bonCommande->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);

                // Add to the total amount
                $totalAmount += $product['quantity'] * $product['price'];
            }
        }
    }

    // Save new products and calculate total amount
    if ($hasNewProducts) {
        foreach ($request->new_products as $newProduct) {
            if (!empty($newProduct['name'])) {
                // Create a new product
                $createdProduct = Product::create([
                    'name' => $newProduct['name'],
                    'category_id' => $newProduct['category_id'],
                    'price' => 0,
                ]);

                // Attach the new product to the bon_commande
                $bonCommande->products()->attach($createdProduct->id, [
                    'quantity' => $newProduct['quantity'],
                    'price' => $newProduct['price'],
                ]);

                // Add to the total amount
                $totalAmount += $newProduct['quantity'] * $newProduct['price'];
            }
        }
    }

    // Update the bon de commande with the total amount
    $bonCommande->update(['montant' => $totalAmount]);

    Session::flash('message', 'Bon de commande created successfully.');
    return response()->json(['error' => false, 'id' => $bonCommande->id]);
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



}
