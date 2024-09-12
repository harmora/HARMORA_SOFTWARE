<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\User;
use App\Models\Client;
use App\Models\Document;
use App\Models\Entreprise;
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

class CommandesController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
          //  $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    /**
     * Display a listing of the commandes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = '')
    {


$users = $this->user->entreprise->user;


$clients = $this->user->entreprise->client;


$products = $this->user->entreprise->product;


$commandes = $this->user->entreprise->commande;




        return view('commandes.commandes', compact('clients', 'users', 'products'), compact('commandes'));
    }




   /**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */

 public function create()
 {
     try {
         $products = $this->user->entreprise->product;
         $clients = $this->user->entreprise->client;
         $users =   $this->user->entreprise->user;

         return view('commandes.create_commande', compact('products', 'clients', 'users'));
     } catch (\Exception $e) {
         Log::error('Error in create method: ' . $e->getMessage());
         return abort(500, 'Something went wrong.');
     }
 }

 public function store(Request $request)
 {
     // Validate the request
     $request->validate([
         'title' => 'required|string|max:255',
         'products' => 'required|array|min:1',
         'products.*.product_id' => 'required|exists:products,id',
         'products.*.quantity' => 'required|integer|min:1',
         'start' => 'nullable|date',
         'description' => 'nullable|string',
         'note' => 'nullable|string',
         'client_id' => 'nullable|integer|exists:clients,id',
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
            $totalAmount += $productData['quantity'] * $product->price;
        }



     }

     // Calculate total amount after applying TVA
     $tvaAmount = ($request->tva / 100) * $totalAmount;
     $totalAmountWithTva = $totalAmount + $tvaAmount;

     // Create a new commande
     $commande = Commande::create([
         'client_id' => $request->client_id,
         'entreprise_id'=>$this->user->entreprise->id,
         'title' => $request->title,
         'description' => $request->description,
         'start_date' => $request->start,
         'due_date' => $request->due_date,
         'total_amount' => $totalAmountWithTva,
         'status' => 'pending',
         'created_at' => now(),
         'updated_at' => now(),
         'user_id' => $this->user->id,
         'tva' => $request->tva, // Store the TVA value
     ]);

    //  Attach products to the commande
     foreach ($request->products as $productData) {
        $product = Product::find($productData['product_id']);

         $commande->products()->attach($productData['product_id'], [
             'quantity' => $productData['quantity'],
             'price' => $product->price,
         ]);

         // Update product stock

         $product->stock -= $productData['quantity'];
         $product->total_amount -= $productData['quantity'] * $product->price;
         $product->save();
     }


     $entreprise = Entreprise::find($this->user->entreprise->id);
     $pdfContent = Pdf::loadView('pdf.devis', compact('commande', 'entreprise'))->output();

     $filePath = 'devis/devis_'.$commande->id.'_' . time() . '.pdf';

     $devisfile = Storage::disk('public')->put($filePath, $pdfContent);

     $documentField['type'] ='devis';
     $documentField['facture'] = Null;
     $documentField['devis'] = $devisfile;
     $documentField['origin'] = 'commande';



     $documentField['reference'] = $commande->id."-".$commande->title;

     $documentField['from_to'] = "client : ".$commande->id."-". $commande->client->first_name."". $commande->client->last_name;

     $documentField['total_amount'] = $commande->total_amount;

     $documentField['user'] = $this->user->first_name . ' ' . $this->user->last_name;

     Document::create($documentField);

     return response()->json(['error' => false, 'message' => 'Commande created successfully.']);
 }









    /**
     * Update the specified commande in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commande  $commande
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    public function edit($id)
    {

$commandes = $this->user->entreprise->commande;


        $commande = Commande::findOrFail($id);
        $clients = $this->user->entreprise->client;
        $users = $this->user->entreprise->user;
        $allProducts = $this->user->entreprise->product;

        $products = $this->user->entreprise->product;


        return view('commandes.edit', compact('commande', 'clients', 'users', 'allProducts', 'products'));
    }


    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'total_amount' => 'nullable|integer',
            'client_id' => 'required|integer|exists:clients,id',
            'tva' => 'required|numeric|min:0|max:100', // Validation rule for TVA
        ]);

        // Find the commande by ID
        $commande = Commande::findOrFail($id);

        // Update the commande fields
        $commande->update([
            'client_id' => $request->client_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'tva' => $request->tva, // Save the TVA value
        ]);



        // Attach new products and update stock
        $totalAmount = 0;
        foreach ($request->products as $productData) {

           $product = Product::find($productData['product_id']);


           if($productData['quantity'] > $product->stock)
           {
               return response()->json(['error' => true, 'message' => 'Quantity of product : '.$product->name.' is not availiable. [ Stock available : '.$product->stock.' ]']);
           }
           else
           {
               $totalAmount += $productData['quantity'] * $product->price;
           }

        }

              // Detach old products
              $commande->products()->detach();

        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);

             $commande->products()->attach($productData['product_id'], [
                 'quantity' => $productData['quantity'],
                 'price' => $product->price,
             ]);

             // Update product stock

             $product->stock -= $productData['quantity'];
             $product->save();
         }



        // Calculate the total amount including TVA
        $totalAmountWithTva = $totalAmount + ($totalAmount * $request->tva / 100);

        // Update the total amount in the commande
        $commande->total_amount = $totalAmountWithTva;
        $commande->save();


        $entreprise = Entreprise::find($this->user->entreprise->id);
        $pdfContent = Pdf::loadView('pdf.devis', compact('commande', 'entreprise'))->output();

        $filePath = 'devis/updated_devis_'.$commande->id.'_' . time() . '.pdf';

        $devisfile = Storage::disk('public')->put($filePath, $pdfContent);

        $documentField['type'] ='devis';
        $documentField['facture'] = Null;
        $documentField['devis'] = $devisfile;
        $documentField['origin'] = 'commande';



        $documentField['reference'] = "Update-".$commande->id."-".$commande->title;

        $documentField['from_to'] = "client : ".$commande->id."-". $commande->client->first_name."". $commande->client->last_name;

        $documentField['total_amount'] = $commande->total_amount;

        $documentField['user'] = $this->user->first_name . ' ' . $this->user->last_name;

        Document::create($documentField);





        $entreprise = Entreprise::find($this->user->entreprise->id);
        $pdfContent = Pdf::loadView('pdf.facture', compact('commande', 'entreprise'))->output();

        $filePath = 'factures/updated_factur_'.$commande->id.'_' . time() . '.pdf';

        $facturefile = Storage::disk('public')->put($filePath, $pdfContent);

        $documentField['type'] ='devis';
        $documentField['facture'] = $facturefile;
        $documentField['devis'] = Null;
        $documentField['origin'] = 'commande';



        $documentField['reference'] = "Update-".$commande->id."-".$commande->title;

        $documentField['from_to'] = "client : ".$commande->id."-". $commande->client->first_name."". $commande->client->last_name;

        $documentField['total_amount'] = $commande->total_amount;

        $documentField['user'] = $this->user->first_name . ' ' . $this->user->last_name;

        Document::create($documentField);


        return response()->json(['error' => false, 'message' => 'Commande updated successfully.']);
    }



public function updateStatus(Request $request, $id)
{
    $commande = Commande::findOrFail($id);
    $entreprise = $this->user->entreprise;

    $commande->status = $request->status;
    $commande->due_date = date('Y-m-d');

    if( $commande->status == "cancelled")
    {
        Session::flash('message', 'Commande was validated successfully, you can now get its facture !');
    }
    else if ( $commande->status == "completed")
    {


           $pdfContent = Pdf::loadView('pdf.devis', compact('commande', 'entreprise'))->output();

            $filePath = 'factures/facture_' .$commande->id.'_' . time() . '.pdf';


            $facturefile = Storage::disk('public')->put($filePath, $pdfContent);

            $documentField['type'] ='facture';
            $documentField['facture'] = $facturefile;
            $documentField['devis'] = null;
            $documentField['origin'] = 'commande';



            $documentField['reference'] = $commande->id."-".$commande->title;

            $documentField['from_to'] = "client : ".$commande->id."-". $commande->client->first_name."". $commande->client->last_name;

            $documentField['total_amount'] = $commande->total_amount;

            $documentField['user'] = $this->user->first_name . ' ' . $this->user->last_name;

            Document::create($documentField);

            Session::flash('message', 'Commande was canceled successfully');

    }



    if ($commande->save()) {
        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false], 500);
    }
}

    /**
     * Remove the specified commande from storage.
     *
     * @param  \App\Models\Commande  $commande
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commande = Commande::find($id);
        DeletionService::delete(Commande::class, $id, 'Commande');
        return response()->json(['error' => false, 'message' => 'Commande deleted successfully.', 'id' => $id, 'title' => $commande->title, 'parent_id' => $commande->product_id, 'parent_type' => 'product']);
    }


    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:commandes,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedCommandes = [];
        $deletedCommandeTitles = [];
        $parentIds = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $commande = Commande::find($id);
            if ($commande) {
                $deletedCommandeTitles[] = $commande->title;
                DeletionService::delete(Commande::class, $id, 'Commande');
                $deletedCommandes[] = $id;
                $parentIds[] = $commande->product_id;
            }
        }

        return response()->json(['error' => false, 'message' => 'Commande(s) deleted successfully.', 'id' => $deletedCommandes, 'titles' => $deletedCommandeTitles, 'parent_id' => $parentIds, 'parent_type' => 'product']);
    }



    // public function list()
    // {
    //     // Fetch all commandes with their associated user, client, and products data
    //     $commandes = Commande::with(['user', 'client', 'products'])->get();

    //     // Format commandes data
    //     $formattedCommandes = $commandes->map(function ($commande) {

    //         $editUrl = route('commandes.edit', $commande->id);

    //         $actions = '';

    //         // Edit link
    //         $actions .= '<a href="' . $editUrl . '" class="edit-commande">' .
    //             '<li class="dropdown-item">' .
    //             '<i class="menu-icon tf-icons bx bx-edit text-primary"></i> ' .
    //             get_label('update', 'Update') .
    //             '</li>' .
    //             '</a>';


    //         $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $commande->id . '" data-type="commandes" data-table="commande_table">' .
    //             '<i class="bx bx-trash text-danger mx-1"></i>' .
    //             '</button>';

    //         $actions .= '<a href="javascript:void(0);" class="quick-view" data-id="' . $commande->id . '" title="' . get_label('quick_view', 'Quick View') . '">' .
    //             '<i class="bx bx-info-circle mx-3"></i>' .
    //             '</a>';

    //         $actions = $actions ?: '-';

    //         return [
    //             'id' => $commande->id,
    //             'title' => $commande->title,
    //             'users' =>  $commande->user->first_name ." ".$commande->user->last_name,
    //             'clients' => $commande->client->first_name ." ".$commande->client->last_name,
    //             'start_date' => $commande->start_date,
    //             'end_date' => $commande->due_date,
    //             'created_at' => $commande->created_at,
    //             'updated_at' => $commande->updated_at,
    //             'status' => $commande->status,
    //             'actions' => $actions,
    //             // 'products' => $products->name,
    //         ];
    //     });

    //     // Return JSON response
    //     return response()->json([
    //         "rows" => $formattedCommandes->all(),
    //         "total" => $formattedCommandes->count()
    //     ]);
    // }


    public function list()
    {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';
        $status = request('status', '');

        $query = Commande::with(['user', 'client', 'products'])
        ->where('entreprise_id', $this->user->entreprise->id);

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

            $actions .= '<a href="/commandes/edit/' . $commande->id . '" title="' . get_label('update', 'Update') . '">
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


public function getCommande($id)
{
    // Fetch the commande with the related products, user, and client
    $commande = Commande::with(['user', 'client', 'products'])
        ->find($id);

    if (!$commande) {
        return response()->json(['error' => 'Commande not found'], 404);
    }

    // Prepare the response data
    $response = [
        'id' => $commande->id,
        'title' => $commande->title,
        'description' => $commande->description,
        'start_date' => $commande->start_date,
        'due_date' => $commande->due_date,
        'total_amount' => $commande->total_amount,
        'status' => $commande->status,
        'client' => $commande->client ? [
            'name' => $commande->client->first_name . ' ' . $commande->client->last_name,
            'denomenation' => $commande->client->denomenation,
            'picture_url' => $commande->client->photo ? asset('storage/' . $commande->client->photo) : asset('storage/photos/no-image.jpg'),
        ] : null,
        'added_by' => $commande->user ? [
            'name' => $commande->user->first_name . ' ' . $commande->user->last_name,
            'picture_url' => $commande->user->photo ? asset('storage/' . $commande->user->photo) : asset('storage/photos/no-image.jpg'),
        ] : null,
        'products' => $commande->products->map(function ($product) {
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


public function getCommandeDetails($id) {
    $commande = Commande::with(['client', 'products'])->find($id);
    return response()->json($commande);
}



public function listForCounter()
{
    $pendingCount = Commande::where('status', 'pending')
    ->where('entreprise_id', $this->user->entreprise->id)->count();

    $completedCount = Commande::where('status', 'completed')
    ->where('entreprise_id', $this->user->entreprise->id)->count();

    $canceledCount = Commande::where('status', 'cancelled')
    ->where('entreprise_id', $this->user->entreprise->id)->count();

    return response()->json([
        'pending' => $pendingCount,
        'completed' => $completedCount,
        'canceled' => $canceledCount,
    ]);
}


public function dragula($id = '')
{
    $user = auth()->user(); // Get the authenticated user
    $clients =  $this->user->entreprise->client;  // Fetch all clients
    $products =  $this->user->entreprise->product;



    // Fetch commandes associated with the authenticated user
    $commandes = Commande::where('entreprise_id', $this->user->entreprise->id)
    ->orderBy('start_date', 'desc') // Assuming 'date' is the column name
    ->get();

    $commandesByStatus = $commandes->groupBy('status');


    $statuses = ['pending', 'completed', 'cancelled'];
    $total_commandes = $commandes->count();

    return view('commandes.board_view', compact('commandes', 'products', 'clients', 'user'), [
        'commandesByStatus' => $commandesByStatus,
        'clients' => $clients,
        'users' => $user, // Pass the authenticated user
        'total_commandes' => $total_commandes,
    ]);
}


public function generateDevis($id)
{
    $commande = Commande::with('products')->findOrFail($id);
    $entreprise = $this->user->entreprise;
    $pdf = Pdf::loadView('pdf.devis', compact('commande'),compact('entreprise'));

    $pdfname = 'devis-'.$commande->id.'.pdf';
    return $pdf->stream($pdfname);
}

public function generateFacture($id)
{
    $commande = Commande::with('products')->findOrFail($id);
    $entreprise = $this->user->entreprise;
    $pdf = Pdf::loadView('pdf.facture', compact('commande'),compact('entreprise'));

    $pdfname = 'facture-'.$commande->id.'.pdf';
    return $pdf->stream($pdfname);
}









    public function get_media($id)
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $commande = Commande::findOrFail($id);
        $media = $commande->getMedia('commande-media');

        if ($search) {
            $media = $media->filter(function ($mediaItem) use ($search) {
                return (
                    // Check if ID contains the search query
                    stripos($mediaItem->id, $search) !== false ||
                    // Check if file name contains the search query
                    stripos($mediaItem->file_name, $search) !== false ||
                    // Check if date created contains the search query
                    stripos($mediaItem->created_at->format('Y-m-d'), $search) !== false
                );
            });
        }


        $formattedMedia = $media->map(function ($mediaItem) {
            // Check if the disk is public
            $isPublicDisk = $mediaItem->disk == 'public' ? 1 : 0;

            // Generate file URL based on disk visibility
            $fileUrl = $isPublicDisk
                ? asset('storage/commande-media/' . $mediaItem->file_name)
                : $mediaItem->getFullUrl();


            $fileExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);

            // Check if file extension corresponds to an image type
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            $isImage = in_array(strtolower($fileExtension), $imageExtensions);

            if ($isImage) {
                $html = '<a href="' . $fileUrl . '" data-lightbox="commande-media">';
                $html .= '<img src="' . $fileUrl . '" alt="' . $mediaItem->file_name . '" width="50">';
                $html .= '</a>';
            } else {
                $html = '<a href="' . $fileUrl . '" title=' . get_label('download', 'Download') . '>' . $mediaItem->file_name . '</a>';
            }

            return [
                'id' => $mediaItem->id,
                'file' => $html,
                'file_name' => $mediaItem->file_name,
                'file_size' => formatSize($mediaItem->size),
                'created_at' => format_date($mediaItem->created_at, true),
                'updated_at' => format_date($mediaItem->updated_at, true),
                'actions' => [
                    '<a href="' . $fileUrl . '" title="' . get_label('download', 'Download') . '" download>' .
                        '<i class="bx bx-download bx-sm"></i>' .
                        '</a>' .
                        '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $mediaItem->id . '" data-type="commande-media">' .
                        '<i class="bx bx-trash text-danger"></i>' .
                        '</button>'
                ],


            ];
        });

        if ($order == 'asc') {
            $formattedMedia = $formattedMedia->sortBy($sort);
        } else {
            $formattedMedia = $formattedMedia->sortByDesc($sort);
        }

        return response()->json([
            'rows' => $formattedMedia->values()->toArray(),
            'total' => $formattedMedia->count(),
        ]);
    }

    public function delete_media($mediaId)
    {
        $mediaItem = Media::find($mediaId);

        if (!$mediaItem) {
            // Handle case where media item is not found
            return response()->json(['error' => true, 'message' => 'File not found.']);
        }

        // Delete media item from the database and disk
        $mediaItem->delete();

        return response()->json(['error' => false, 'message' => 'File deleted successfully.', 'id' => $mediaId, 'title' => $mediaItem->file_name, 'parent_id' => $mediaItem->model_id,  'type' => 'media', 'parent_type' => 'commande']);
    }

    public function delete_multiple_media(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:media,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        $parentIds = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $media = Media::find($id);
            if ($media) {
                $deletedIds[] = $id;
                $deletedTitles[] = $media->file_name;
                $parentIds[] = $media->model_id;
                $media->delete();
            }
        }

        return response()->json(['error' => false, 'message' => 'Files(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles, 'parent_id' => $parentIds, 'type' => 'media', 'parent_type' => 'commande']);
    }

}

?>
