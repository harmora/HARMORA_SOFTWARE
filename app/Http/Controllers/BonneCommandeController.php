<?php

namespace App\Http\Controllers;

use App\Models\BonDeCommande;
use App\Models\fournisseur;
use App\Models\ProdCategory;
use App\Models\Product;
use Illuminate\Http\Request;

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

    // You can adjust the 'visibleColumns' as needed based on user preferences
    // $visibleColumns = getUserPreferences('bon_de_commande');

    return view('achats.bonnecommande', ['bonDeCommandes' => $bonDeCommandes]);
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


}
