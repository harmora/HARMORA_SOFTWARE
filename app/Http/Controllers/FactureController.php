<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Entreprise;
use App\Models\Forme_juridique;
use App\Models\fournisseur;
use App\Models\ProdCategory;
use App\Models\Product;
use App\Services\DeletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;



class FactureController extends Controller
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
    // Method to show all factures
    public function index()
    {
        // Fetch all factures
        $factures = Facture::all();

        // Return the view with factures data
        return view('factures.show', compact('factures'));
    }


    public function create(Request $request)
    {
        $entreprises = Entreprise::all();
        $products = Product::all();
        $fournisseurs = fournisseur::all();
        $categories = ProdCategory::all();

        return view('factures.create_factures', ['entreprises' => $entreprises,'products'=>$products,'fournisseurs'=> $fournisseurs,'categories'=>$categories]);
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant' => 'required|numeric|min:0',
            'status_payement' => 'required|in:paid,unpaid',
            'tva' => 'nullable|numeric|min:0',
            'date_paiement' => 'nullable|date',
            'date_limit' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ]);
        $formFields['entreprise_id'] = $this->user->entreprise_id;

        $factures = Facture::create($formFields);
        Session::flash('message', 'Fournisseur created successfully.');
        // Session::flash('message', 'Product created successfully.');
        return response()->json(['error' => false, 'id' => $factures->id]);
    }

}    