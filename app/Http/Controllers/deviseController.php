<?php
namespace App\Http\Controllers;

use App\Models\bon_livraision;
use App\Models\Commande;
use App\Models\devise;
use App\Models\mouvements_stock;
use App\Models\User;
use App\Models\Client;
use App\Models\Document;
use App\Models\Entreprise;
use App\Models\invoice;
use App\Models\ProdCategory;
use App\Models\Product;
use Illuminate\Http\Request;


use Illuminate\Support\Arr;
use App\Services\DeletionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Exception;
use Illuminate\Support\Facades\Log;

class deviseController extends Controller
{
    //
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------------------------------------------------------------

    public function index()
    {
        $users = $this->user->entreprise->user;
        $clients = $this->user->entreprise->client;
        $products = $this->user->entreprise->product;
        $commandes = $this->user->entreprise->commande;
        return view('commandes.commandes', compact('clients', 'users', 'products'), compact('commandes'));

    }

    //--------------------------------------------------------------------------------------------------------------------------------

    public function createdevise()
    {
        try {
            $products = $this->user->entreprise->product;
            $clients = $this->user->entreprise->client;
            $users =   $this->user->entreprise->user;            
            $categories = ProdCategory::all();

   
            return view('devise.create_devise', compact('products', 'clients', 'users','categories'));
        } catch (\Exception $e) {
            Log::error('Error in create method: ' . $e->getMessage());
            return abort(500, 'Something went wrong.');
        }
    }
    //--------------------------------------------------------------------------------------------------------------------------------
    public function editdevis($id)
    {
        // $commandes = $this->user->entreprise->commande;
        $commande = devise::findOrFail($id);
        $clients = $this->user->entreprise->client;
        $users = $this->user->entreprise->user;
        $allProducts = $this->user->entreprise->product;

        $products = $this->user->entreprise->product;
        return view('devise.edit', compact('commande','clients', 'users', 'allProducts', 'products'));
    }

    //--------------------------------------------------------------------------------------------------------------------------------
    // public function generateDevis($id)
    // {
    //     $commande = devise::with('products')->findOrFail($id);
    //     $entreprise = $this->user->entreprise;
    //     $pdf = Pdf::loadView('pdf.devis', compact('commande'),compact('entreprise'));

    //     $pdfname = 'devis-'.$commande->id.'.pdf';
    //     return $pdf->stream($pdfname);
    // }

//--------------------------------------------------------------------------------------------------------------------------------

    public function store_devise(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:1',
            'start' => 'required|date',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'client_id' => 'required|integer|exists:clients,id',
            'tva' => 'nullable|numeric|min:0|max:100', // Validate TVA
        ]);
        // Calculate total amount before TVA
        $totalAmount = 0;
        foreach ($request->products as $productData) {
   
           $product = Product::find($productData['product_id']);
   
   
           if($productData['quantity'] > $product->stock)
           {
               return response()->json(['error' => true, 'message' => 'Quantity of product : '.$product->name.' is not availiable. [ Stock available : '.$product->stock.' ]']);
           }
           else
           {
               $totalAmount += $productData['quantity'] * $productData['price'];
           }
        }
   
        // Calculate total amount after applying TVA
        $tvaAmount = ($request->tva / 100) * $totalAmount;
        $totalAmountWithoutTva = $totalAmount - $tvaAmount;
   
        // Create a new commande
        $commande = devise::create([
            'client_id' => $request->client_id,
            'entreprise_id'=>$this->user->entreprise->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start,
            'due_date' => now(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $this->user->id,
        ]);
   
       //  Attach products to the commande
        foreach ($request->products as $productData) {
           $product = Product::find($productData['product_id']);
   
            $commande->products()->attach($productData['product_id'], [
                'quantity' => $productData['quantity'],
                'price' => $productData['price'],
            ]);
            
   
            // Update product stock
           //  $product->stock -= $productData['quantity'];
           //  $product->total_amount -= $productData['quantity'] * $product->price;
           //  $product->save();
        }
        $entreprise = Entreprise::find($this->user->entreprise->id);
        $pdfContent = Pdf::loadView('pdf.devis', compact('commande', 'entreprise'))->output();
        $filePath = 'devis/devis_'.$commande->id.'_' . time() . '.pdf';
        $devisfile = Storage::disk('public')->put($filePath, $pdfContent);

        $documentField['type'] ='devis';
        $documentField['facture'] = Null;
        $documentField['devis'] = $filePath;
        $documentField['origin'] = 'commande';   
        $documentField['reference'] = $commande->id."-".$commande->title;
        $documentField['from_to'] = "client : ".$commande->id."-". $commande->client->first_name."". $commande->client->last_name;
        $documentField['total_amount'] = $commande->total_amount;
        $documentField['user'] = $this->user->first_name . ' ' . $this->user->last_name; 
        Document::create($documentField);
   
        return response()->json(['error' => false, 'message' => 'Devise created successfully.']);
    }
//--------------------------------------------------------------------------------------------------------------------------------
    public function show_devise($id)
    {
        $commande = devise::findOrFail($id);
        $document = Document::where('type', 'devis')
                            ->where('reference', 'like', $commande->id.'-%')
                            ->first();

        if (!$document || !Storage::disk('public')->exists($document->devis)) {
            return response()->json(['error' => true, 'message' => 'Devis file not found.']);
        }

        $filePath = storage_path('app/public/' . $document->devis);
        return response()->file($filePath);
    }
//--------------------------------------------------------------------------------------------------------------------------------
    public function listdevis()
    {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';
        $status = request('status', '');

        $query = devise::with(['user', 'client', 'products']);

        // Search functionality
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('client', function ($query) use ($search) {
                        $query->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('denomenation', 'like', '%' . $search . '%');
                    });
            });
        }

        // Status filtering
        if ($status !== '') {
            $query->where('status', $status);
        }

        $totalCommandes = $query->count();

        $commandes = $query->orderBy($sort, $order)
            ->paginate(request("limit"));

        $formattedCommandes = $commandes->through(function ($commande) {

            // User profile picture
            $userProfileHtml = "<div class='avatar avatar-md pull-up' title='" . $commande->user->first_name . " " . $commande->user->last_name . "'>
                                    <a href='/users/profile/" . $commande->user->id . "'>
                                        <img src='" . ($commande->user->photo ? asset('storage/' . $commande->user->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                                    </a>
                                </div>";

            // Client profile picture
            $clientProfileHtml = "<div class='avatar avatar-md pull-up' title='" . $commande->client->first_name . " " . $commande->client->last_name . " " . $commande->client->denomenation . "'>
                                    <a href='/clients/profile/" . $commande->client->id . "'>
                                        <img src='" . ($commande->client->photo ? asset('storage/' . $commande->client->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                                    </a>
                                </div>";

            // Products with small circle image
            $productsHtml = "<div style='display: flex; flex-wrap: nowrap; align-items: center; overflow-x: auto;'>" .
                $commande->products->map(function ($product) {
                    return "<div class='avatar avatar-sm pull-up' title='" . $product->name . "' style='margin-right: 10px;'>
                                <a href='/products/info/" . $product->id . "'>
                                    <img src='" . ($product->photo ? asset('storage/' . $product->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                                </a>
                            </div>
                            <span style='margin-right: 20px;'>" . $product->name . "</span>";
                })->implode('') . "</div>";

            // TVA calculation and formatting
            $tvaHtml = '<span>' . number_format($commande->tva, 2) . '%</span>';

            // Actions
            $actions = '<a href="javascript:void(0);" class="quick-view" data-id="' . $commande->id . '" title="' . get_label('quick_view', 'Quick View') . '">
                            <i class="bx bx-info-circle text-info"></i>
                        </a>';

            $actions .= '<a href="/commandes/editdevise/' . $commande->id . '" title="' . get_label('update', 'Update') . '">
                            <i class="bx bx-edit mx-1"></i>
                        </a>';

            $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $commande->id . '" data-type="commandes">
                            <i class="bx bx-trash text-danger mx-1"></i>
                        </button>';

            $commandestatus = '<span class="badge ' .
                ($commande->status == 'pending' ? 'bg-warning' :
                ($commande->status == 'completed' ? 'bg-success' :
                ($commande->status == 'cancelled' ? 'bg-danger' : 'bg-info'))) .
                '">' . $commande->status . '</span>';

            $id_holder = 
                '<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#commandeModal">' .
                    '<button type="button" class="btn btn-info btn-sm" ' .
                        'data-id="' . htmlspecialchars($commande->id) . '" ' .
                        'data-bs-toggle="tooltip" ' .
                        'data-bs-placement="left" ' .
                        'data-bs-original-title="' . htmlspecialchars(get_label('View Details', 'View Details')) . '">' .
                        '<i class="bx bx-expand"></i> ' . htmlspecialchars(get_label('View Details', 'View Details')) .
                    '</button>' .
                '</a>';




                $documentsHtml = '<div class="mb-3" style="display: flex; justify-content: center; align-items: center;">
                <a class="me-2">
                    <button id="generatePdfButton-' . $commande->id . '" data-url="' . route('devis.pdf', $commande->id) . '" type="button" class="btn btn-sm btn-secondary">
                        ' . get_label('devis', 'Devis') . ' <i class="bx bx-file"></i>
                    </button>
                </a>';

    if ($commande->status == "completed") {
    $documentsHtml .= '<a class="me-2">
                    <button id="generatefactureButton-' . $commande->id . '" data-url="' . route('facture.pdf', $commande->id) . '" type="button" class="btn btn-sm btn-primary">
                        ' . get_label('facture', 'Facture') . ' <i class="bx bx-dollar"></i>
                    </button>
                </a>';
    }

    $documentsHtml .= '</div>';




            return [
                'id' => $id_holder,
                'title' => $commande->title,
                'description' => $commande->description,
                'start_date' => $commande->start_date,
                'due_date' => $commande->due_date,
                'total_amount' => $commande->total_amount,
                'tva' => $tvaHtml,
                'status' => $commandestatus,
                'products' => $productsHtml,
                'added_by' => $userProfileHtml . ' ' . $commande->user->first_name . ' ' . $commande->user->last_name,
                'client' => $clientProfileHtml . ' ' . $commande->client->first_name . ' ' . $commande->client->last_name,
                'created_at' => format_date($commande->created_at, true),
                'updated_at' => format_date($commande->updated_at, true),
                'actions' => $actions,
                'documents' => $documentsHtml
            ];
        });

        return response()->json([
            "rows" => $formattedCommandes->items(),
            "total" => $totalCommandes,
        ]);
    }
    //--------------------------------------------------------------------------------------------------------------------------------
    
    public function accepter(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:1',
            'start' => 'required|date',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'total_amount' => 'nullable|integer',
            'client_id' => 'required|integer|exists:clients,id',
            'tva' => 'required|numeric|min:0|max:100', // Validation rule for TVA
        ]);


        DB::beginTransaction();

        try {
        // Find the commande by ID
        $devise = devise::findOrFail($id);

        $totalAmount = 0;
        foreach ($request->products as $productData) {
   
           $product = Product::find($productData['product_id']);
   
   
           if($productData['quantity'] > $product->stock)
           {
               return response()->json(['error' => true, 'message' => 'Quantity of product : '.$product->name.' is not availiable. [ Stock available : '.$product->stock.' ]']);
           }
           else
           {
               $totalAmount += $productData['quantity'] * $productData['price'];
           }
        }

        $invoice=invoice::create([
            'client_id' => $request->client_id,
            'entreprise_id'=>$this->user->entreprise->id,
            'devise_id' => $devise->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start,
            'due_date' => now(),
            'total_amount' => $totalAmount,
            'status' => 'validated',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $this->user->id,

        ]);



        // Update the commande fields
        $devise->update([
            'status' => 'validated',
            'updated_at' => now(),
        ]);

        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);

             $invoice->products()->attach($productData['product_id'], [
                 'quantity' => $productData['quantity'],
                 'price' => $product->price,
             ]);

             // Update product stock
             $product->stock -= $productData['quantity'];
             $product->save();
             mouvements_stock::create([
                'product_id'=>$product->id,
                'quantitéajoutée'=>$productData['quantity'],
                'quantitéprecedente'=>$product->stock+$productData['quantity'],
                'date_mouvement'=>now(),
                'type_mouvement'=>'sortie',
                'reference'=>$devise->id."-".$devise->title,
            ]);
         }
        // Calculate the total amount including TVA
        $totalAmountWithTva = $totalAmount + ($totalAmount * $request->tva / 100);

        // Update the total amount in the commande
        $invoice->total_amount = $totalAmount;
        $devise->save();

        $commande=$invoice;
        $entreprise = Entreprise::find($this->user->entreprise->id);
        $pdfContent = Pdf::loadView('pdf.facture', compact('commande', 'entreprise'))->output();

        $filePath = 'facture/updated_facture_'.$invoice->id.'_' . time() . '.pdf';

        $facturefile = Storage::disk('public')->put($filePath, $pdfContent);

        $documentField['type'] ='facture';
        $documentField['facture'] = $filePath;
        $documentField['devis'] = Null;
        $documentField['origin'] = 'commande';

        $documentField['reference'] = "Update-".$invoice->id."-".$invoice->title;
        if($invoice->client->first_name != Null)
        {
            $documentField['from_to'] = "client : ".$invoice->id."-". $invoice->client->first_name."". $invoice->client->last_name;
        }
        else
        {
            $documentField['from_to'] = "client : ".$invoice->id."-". $invoice->client->denomenation;
        }

        $documentField['total_amount'] = $invoice->total_amount;

        $documentField['user'] = $this->user->first_name . ' ' . $this->user->last_name;

        Document::create($documentField);
        DB::commit();
        return response()->json(['error' => false, 'message' => 'facture created successfully.']);
    } catch (\Exception $e) {
        // Rollback the transaction if there's an error
        DB::rollBack();

        // Handle any exceptions
        return response()->json(['error' => true, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
    }
    public function show_invoice($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            
            $document = Document::where('type', 'facture')
                                ->where('reference', 'like', 'Update-' . $invoice->id . '-%')
                                ->first();

            if (!$document || !Storage::disk('public')->exists($document->facture)) {
                return response()->json(['error' => true, 'message' => 'Invoice file not found.']);
            }

            $filePath = storage_path('app/public/' . $document->facture);
            return response()->file($filePath);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function createbonlivraision(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $products = $this->user->entreprise->product;
        $clients = $this->user->entreprise->client;
        $users = $this->user->entreprise->user;
    
        return view('boncommande.create_bon_livraision', compact('invoice', 'products', 'clients', 'users'));
    }
    // public function bonlivr(Request $request, $id)
    // {
    //     $commande = Invoice::findOrFail($id);
    //     $products = $this->user->entreprise->product;
    //     $clients = $this->user->entreprise->client;
    //     $users = $this->user->entreprise->user;
    
    //     return view('boncommande.create_bon_commande', compact('commande', 'products', 'clients', 'users'));
    // }
    public function bonlivr(Request $request, $id)
{
    $commande = Invoice::findOrFail($id);
    $products = $this->user->entreprise->product;
    $clients = $this->user->entreprise->client;
    $users = $this->user->entreprise->user;
    
    // Fetch existing bon livraisons
    $previousBonLivraisons = $commande->bonLivraisons()->get();
    
    return view('boncommande.create_bon_livraision', compact('commande', 'products', 'clients', 'users', 'previousBonLivraisons'));
}

public function storeLivraison(Request $request, $invoiceId)
{
    // Retrieve the Invoice
    $invoice = Invoice::findOrFail($invoiceId);
    $bonLivraisonExists = $invoice->bonLivraisons()->exists() ? 1 : 0;

    // Validate incoming data
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start' => 'nullable|date',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' =>  $bonLivraisonExists?'nullable|integer|min:1':'required',
        'products.*.remaining_quantity'=> $bonLivraisonExists?'required':'nullable|integer',
        'status' => 'required|in:partial,total',
    ]);

    // Check if the status is 'total' and handle logic
    if ($validatedData['status'] === 'total') {
        // Mark the invoice as fully delivered
        $invoice->status = 'completed';
        $invoice->save();
    }
    $totalAmount = 0;
    $totalproducts = 0;
    foreach ($request->products as $productData) {
        $product = Product::find($productData['product_id']);
        $totalproducts +=  $bonLivraisonExists ? $productData['remaining_quantity']:$productData['quantity'];
        $totalAmount +=   ($bonLivraisonExists ? $productData['remaining_quantity']:$productData['quantity']) * $productData['price'];
    }
    // Create the BonLivraison record
    $bon=bon_livraision::create([
        'user_id'=>$invoice->user_id,
        'client_id'=>$invoice->client_id,
        'entreprise_id'=>$invoice->entreprise_id,
        'invoice_id' => $invoice->id,
        'title' => $validatedData['title'],
        'start_date' =>  now() ,
        'due_date' => now(),
        'status' => $validatedData['status'],
        'total_amount'=>$totalAmount,
    ]);
    
    foreach ($request->products as $productData) {
        $product = Product::find($productData['product_id']);
        $bon->products()->attach($productData['product_id'], [
             'quantity' =>   $bonLivraisonExists ? $productData['remaining_quantity']:$productData['quantity']
         ]);
     }
    // If status is 'partial', keep the invoice status as pending/partial
    if ($validatedData['status'] === 'partial') {
        $invoice->status = 'partial';
        $invoice->save();
    }
    // $entreprise = Entreprise::find($invoice->entreprise_id);
    // $pdfContent = PDF::loadView('pdf.bon_livraison', compact('bon', 'entreprise'))->output();
    // // Store PDF file
    $filePath = 'bon_livraison/bon_livraison_' . $bon->id . '_' . time() . '.pdf';
    // Storage::disk('public')->put($filePath, $pdfContent);
    // // Create document record
    Document::create([
        'type' => 'bon_livraison',
        'bon_livraison' => $filePath,
        'origin' => 'commande',
        'reference' => $bon->id . "-" . $bon->title,
        'from_to' => "client : " . $bon->client_id . "-" . $bon->client->first_name . " " . $bon->client->last_name,
        'total_amount' => $bon->total_amount,
        'user' => $bon->user->first_name . ' ' . $bon->user->last_name,
    ]);


    // Redirect back to the appropriate page
    return response()->json(['error' => false, 'message' => 'Bon de Livraison created successfully.']);
}

public function show_bonliv($id)
{
    try {
        $bonLivraison = bon_livraision::findOrFail($id);
        
        $document = Document::where('type', 'bon_livraison')
                            ->where('reference', 'like', $bonLivraison->id . '-%')
                            ->first();

        if (!$document || !Storage::disk('public')->exists($document->bon_livraison)) {
            return response()->json(['error' => true, 'message' => 'Bon de Livraison file not found.']);
        }

        $filePath = storage_path('app/public/' . $document->bon_livraison);
        return response()->file($filePath);
    } catch (\Exception $e) {
        return response()->json(['error' => true, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
}
public function destroy($id)
{
    $client = devise::findOrFail($id);
    $response = DeletionService::delete(devise::class, $id, 'devise');
    // UserClientPreference::where('user_id', 'c_' . $id)->delete();
    return $response;
}
public function destroyfacture($id)
{
    $client = invoice::findOrFail($id);
    $response = DeletionService::delete(invoice::class, $id, 'facture');
    // UserClientPreference::where('user_id', 'c_' . $id)->delete();
    return $response;
}
public function destroybon($id)
{
    $client = bon_livraision::findOrFail($id);
    $response = DeletionService::delete(bon_livraision::class, $id, 'bon_livraision');
    // UserClientPreference::where('user_id', 'c_' . $id)->delete();
    return $response;
}
}
