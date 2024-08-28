<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Entreprise;
use App\Models\fournisseur;
use App\Models\ProdCategory;
use App\Models\Product;
use App\Services\DeletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $products = Product::all();
        $fournisseurs = fournisseur::all();
        $categories = ProdCategory::all();
        $user = auth()->user();
        $entreprise = Entreprise::find($user->entreprise_id);

        // $company = $user->entreprise_id; // Assuming there's a relationship set up between User and Entreprise

        return view('factures.create_facture', [
            'entreprise' => $entreprise,
            'products' => $products,
            'fournisseurs' => $fournisseurs,
            'categories' => $categories,
            'company_name' => $entreprise->denomination, // Pass the company name to the view
            'address' => $entreprise->address,  // Assuming `address` is the column for company address  
        ]);
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_details' => 'required|string',
            'email' => 'required|email',
            'date' => 'required|date',
            'invoice_number' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'client_name' => 'required|string|max:255',
            'client_address' => 'required|string',
            'client_contact_details' => 'required|string',
            'item_description' => 'required|string',
            'item_quantity' => 'required|integer|min:1',
            'item_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $formFields['logo'] = $logoPath;
        }

        $formFields['entreprise_id'] = $this->user->entreprise_id;

        $facture = Facture::create($formFields);

        Session::flash('message', 'Facture created successfully.');

        return response()->json(['error' => false, 'id' => $facture->id]);
    }


    public function edit($id)
{
    $facture = Facture::findOrFail($id);
    $entreprises = Entreprise::all();
    $products = Product::all();
    $fournisseurs = fournisseur::all();
    $categories = ProdCategory::all();

    return view('factures.edit_facture', [
        'facture' => $facture,
        'entreprises' => $entreprises,
        'products' => $products,
        'fournisseurs' => $fournisseurs,
        'categories' => $categories
    ]);
}


public function update(Request $request, $id)
{
    $facture = Facture::findOrFail($id);

    $formFields = $request->validate([
        // 'company_name' => 'required|string|max:255',
        'company_name' => 'required|string|max:255',
        'address' => 'required|string',
        'contact_details' => 'required|string',
        'email' => 'required|email',
        'date' => 'required|date',
        'invoice_number' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'client_name' => 'required|string|max:255',
        'client_address' => 'required|string',
        'client_contact_details' => 'required|string',
        'item_description' => 'required|string',
        'item_quantity' => 'required|integer|min:1',
        'item_price' => 'required|numeric|min:0',
        'total_amount' => 'required|numeric|min:0',
        'tax_rate' => 'required|numeric|min:0',
        'tax_amount' => 'required|numeric|min:0',
        'grand_total' => 'required|numeric|min:0',
    ]);

    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('logos', 'public');
        $formFields['logo'] = $logoPath;
    }

    $facture->update($formFields);

    Session::flash('message', 'Facture updated successfully.');

    return redirect()->route('factures.show');
}

// public function destroy($id)
// {
//     $facture = Facture::find($id);
//     $response = DeletionService::delete(Facture::class, $id, 'facture'); 

//     return $response;
// }


public function destroy($id)
{
    $facture = Facture::find($id);

    if ($facture) {
        $facture->delete();

        return response()->json(['success' => true, 'message' => 'Facture deleted successfully.']);
    }

    return response()->json(['success' => false, 'message' => 'Facture not found.']);
}


}
