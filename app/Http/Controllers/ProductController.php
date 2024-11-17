<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\depot;
use App\Models\mouvements_stock;
use App\Models\ProdCategory;
use App\Models\Product;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Throwable;


use Illuminate\Support\Str;

use function Laravel\Prompts\alert;

class ProductController extends Controller
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
        // $meetings = isAdminOrHasAllDataAccess() ? $this->workspace->meetings : $this->user->meetings;

        $products = $this->user->entreprise->product;

        $categories = ProdCategory::all();
        return view('products.products', ['products' => $products,'categories'=>$categories]);
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function render_mv()
     {
         // $meetings = isAdminOrHasAllDataAccess() ? $this->workspace->meetings : $this->user->meetings;

         $movements = $this->user->entreprise->product;
         $visibleColumns = getUserPreferences('movements');
         return view('products.mouvement', ['movements' => $movements,compact('visibleColumns')]);

     }

    public function create()
    {
        $categories = ProdCategory::all();
        $depots=depot::where('entreprise_id', $this->user->entreprise_id)->get();


        return view('products.create',['categories'=>$categories,'depots'=>$depots]);
    }

    public function add_quantity()
    {
        $depots=depot::where('entreprise_id', $this->user->entreprise_id)->get();
        $products = Product::where('entreprise_id', $this->user->entreprise_id)->get();

        return view('products.addquantity',['products'=>$products,'depots'=>$depots]);
    }
    public function     storeQuantity(Request $request)
    {
        // Validation
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'depot_id' => 'required|exists:depots,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Retrieve the product and depot
        $product = Product::findOrFail($request->product_id);
        $depot = Depot::findOrFail($request->depot_id);

        // Here you can implement logic to update stock quantity based on the depot
        // Example: Increase the product's stock for the specified depot
        $existingDepot = $product->depots()->where('depot_id', $depot->id)->first();

        if ($existingDepot) {
            // If it exists, update the quantity (adding the new quantity to the existing one)
            $currentQuantity = $existingDepot->pivot->quantity;
            $newQuantity = $currentQuantity + $request->quantity;
            $product->depots()->updateExistingPivot($depot->id, ['quantity' => $newQuantity]);
        } else {
            // If it doesn't exist, create a new record
            $product->depots()->attach($depot->id, ['quantity' => $request->quantity]);
        }        
        $product->stock += $request->quantity;
        $product->save();

        // Flash message and redirect
        return response()->json(['error' => false, 'id' => $product->id]);
        // return redirect()->route('products.index')->with('success', 'Quantity added successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
         ini_set('max_execution_time', 300);

         $formFields = $request->validate([
             'name' => ['required'],
             'description' => 'nullable',
             'price' => ['required', 'numeric'],
             'stock' => ['required', 'integer'],
             'stock_defective' => ['nullable', 'integer'],
             'category_id' => ['required', 'exists:prod_categories,id'],
             'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
         ]);
         $depot=$request->validate([
            'depot_id' => ['required', 'exists:depots,id']
        ]);

         if ($request->hasFile('photo')) {
             $formFields['photo'] = $request->file('photo')->store('photos', 'public');
         } else {
             $formFields['photo'] = 'photos/no-image.jpg';
         }
         $formFields['total_amount'] = $formFields['price'] * $formFields['stock'];
         $formFields['prev_price'] = $formFields['price'];
         $formFields['prev_stock'] = $formFields['stock'];

         $formFields['reference'] = $this->generateProductReference();

         $formFields['entreprise_id'] = $this->user->entreprise->id;
         if($formFields['stock_defective'] == null){
             $formFields['stock_defective'] = 0;
         }


         $product = Product::create($formFields);
         $product->product_category_id = $request->input('category_id');
         $product->depots()->attach($depot['depot_id'], ['quantity' => $formFields['stock']]);
         $product->save();

         mouvements_stock::create([
            'product_id'=>$product->id,
            'quantitéajoutée'=>$product->stock,
            'quantitéprecedente'=>0,
            'date_mouvement'=>now(),
            'type_mouvement'=>'entrée',
            'reference'=>$product->name.'-'.$product->reference,
        ]);

         try {
             Session::flash('message', 'Product created successfully.');
             return response()->json(['error' => false, 'id' => $product->id]);
         } catch (Throwable $e) {
             // Catch any throwable, including non-Exception errors
             $product = Product::findOrFail($product->id);
             $product->delete();
             return response()->json(['error' => true, 'message' => 'Product couldn\'t be created, please try again.']);
         }
     }




    public function edit($id)
    {
        $categories = ProdCategory::all();
        $product = Product::findOrFail($id);
        return view('products.edit',['categories'=>$categories,'product'=>$product]);
    }



     public function update(Request $request, $id)
{
    $formFields = $request->validate([
        'name' => ['required'],
        'description' => 'nullable',
        'price' => ['required', 'numeric'],
        'stock' => ['required', 'integer'],
        'stock_defective' => ['nullable', 'integer'],
        'category_id' => ['required', 'exists:prod_categories,id'],
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    $product = Product::findOrFail($id);

    if ($request->hasFile('photo')) {
        if ($product->photo != 'photos/no-image.jpg' && $product->photo !== null) {
            Storage::disk('public')->delete($product->photo);
        }

        $formFields['photo'] = $request->file('photo')->store('photos', 'public');
    } else {
        // If no new photo is uploaded, keep the old one
        $formFields['photo'] = $product->photo;
    }

    $product->update([
        'name' => $formFields['name'],
        'description' => $formFields['description'],
        'price' => $formFields['price'],
        'stock' => $formFields['stock'],
        'stock_defective' => $formFields['stock_defective'],
        'category_id' => $formFields['category_id'],
        'photo' => $formFields['photo']
    ]);

    try {
        Session::flash('message', 'Product updated successfully.');
        return response()->json(['error' => false, 'id' => $product->id]);
    } catch (Throwable $e) {
        // Catch any throwable, including non-Exception errors
        return response()->json(['error' => true, 'message' => 'Product couldn\'t be updated, please try again.']);
    }
}



    public function list ()
    {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';
        $category = request('category', '');
    //    ...

        $query = $this->user->entreprise->product();

        // Search functionality
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    // ->orWhere('phone', 'like', '%' . $search . '%')
                    // ->orWhere('email', 'like', '%' . $search . '%')
                    ;
            });
        }

        // Status filtering
        if ($category !== '') {
            $query->where('product_category_id', $category);
        }

        // // Role filtering
        // if (!empty($role_ids)) {
        //     $query->whereHas('roles', function ($query) use ($role_ids) {
        //         $query->whereIn('roles.id', $role_ids);
        //     });
        // }

        $totalproducts = $query->count();

        $products = $query->select('products.*', 'prod_categories.name_cat as category_name')
        ->leftJoin('prod_categories', 'products.product_category_id', '=', 'prod_categories.id')
        ->orderBy($sort, $order)
        ->paginate(request("limit"));

        $products = $products->through(function ($product) {

            $actions = '';

                $actions .= '<a href="/products/edit/' . $product->id . '" title="' . get_label('update', 'Update') . '">' .
                    '<i class="bx bx-edit mx-1"></i>' .
                    '</a>';



                $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $product->id . '" data-type="product-media">' .
                    '<i class="bx bx-trash text-danger mx-1"></i>' .
                    '</button>';


            $actions = $actions ?: '-';


            $photoHtml = "<div class='avatar avatar-md pull-up' title='" . $product->name ."'>
            <a href='/products/info/" . $product->id . "'>
                <img src='" . ( $product->photo ? asset('storage/' . $product->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
            </a>
          </div>";

            $product_category = $product->category_name 
                ? '<span class="badge bg-dark"> '.$product->category_name.'</span>' 
                : '<span class="badge bg-secondary">No Category</span>';
            $price_gray = '<span class="badge bg-secondary"> '.$product->price.'</span>';


            $product_stock = $product->stock != 0
                ? '<span class="badge bg-info">' . get_label('quantity', 'Quantity'). ' : '.$product->stock.'</span>'
                : '<span class="badge bg-danger">' . get_label('empty stock', 'Empty stock') . '</span>';


            $stock_def = '<span class="badge bg-danger">' . get_label('quantity', 'Quantity'). ' : '.$product->stock_defective.'</span>';

            $formattedHtml = '<div class="d-flex mt-2">' .
                $photoHtml .
                '<div class="mx-2">' .
                '<h6 class="mb-1">' .
                $product->name .
                ' ' . $price_gray .
                '</h6>' .
                '</div>' .
                '</div>';


                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'reference' => $product->reference,
                    'category' => $product_category,
                    'stock' => $product_stock,
                    'stock_def' => $stock_def,
                    'profile' => $formattedHtml,
                    'price' => $product->price,
                    'prevprice' => $product->prev_price,
                    'created_at' => format_date($product->created_at, true),
                    'updated_at' => format_date($product->updated_at, true),
                    'actions' => $actions
                ];
        });

        return response()->json([
            "rows" => $products->items(),
            "total" => $totalproducts,
        ]);
    }

    public function list_mv(Request $request)
    {

        // Apply search, sort, and pagination logic
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'DESC');


        $query = mouvements_stock::query();

        // Filtering based on search term
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('type_mouvement', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('reference', 'like', '%' . $search . '%');
            });
        }
        $totalmovements = $query->count();

        $mouvements= $query->select('mouvements_stocks.*')
            ->leftJoin('products', 'mouvements_stocks.product_id', '=', 'products.id')
            ->leftJoin('achats', 'mouvements_stocks.achat_id', '=', 'achats.id')
            ->leftJoin('commandes', 'mouvements_stocks.commande_id', '=', 'commandes.id')
            ->orderBy($sort, $order)
            ->paginate(request('limit'));

        $mouvements= $mouvements->through(function ($mouvement) {
            return [
                'id' => $mouvement['id'],
                'product' => $mouvement->product->name,
                'type' => $mouvement['type_mouvement'],
                'reference' => $mouvement['reference'],
                'description' => $mouvement['description'],
                'quantity' => $mouvement['quantitéajoutée'],
                'batch_number' => $mouvement['quantitéprecedente'],
                'movement_date' => $mouvement['date_mouvement'],
                'depot' => $mouvement->depot->name??'-',
        ];
        });
        return response()->json([
            'rows' => $mouvements->items(),
            'total' => $totalmovements,
        ]);

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



    public function destroy($id)
    {
        $response = DeletionService::delete(Product::class, $id, 'product');
        return $response;
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $workspace = Workspace::find(session()->get('workspace_id'));
        $product = Product::join('prod_categories', 'products.product_category_id', '=', 'prod_categories.id')
        ->where('products.id', $id)
        ->select('products.*', 'prod_categories.name_cat as category_name')
        ->firstOrFail();
        $depots = $product->depots->map(function ($depot) {
            return [
                'id' => $depot->id,
                'name' => $depot->name,
                'quantity' => $depot->pivot->quantity
            ];
        })->filter(function ($depot) {
            return $depot['quantity'] > 0; // Only keep depots with quantity > 0
        })->values();

        return view('products.product_info', ['product' => $product, 'auth_user' => getAuthenticatedUser(),'depots'=>$depots]);
    }
}

            // $profileHtml = "<div class='avatar avatar-md pull-up' title='" . $mouvement->fournisseur->name. " '>
            //     <a href='/clients/profile/" . $achat->id . "'>
            //     </a>
            //     </div>";//when hover the photo display infos as popup

            // $formattedHtml = '<div class="d-flex mt-2">' .
            //     $profileHtml .
            //     '<div class="mx-2">' .
            //     '<h6 class="mb-1">fournisseur: '.$achat->fournisseur->name.
            //     '</h6>' .
            //     '<span class="text-muted">entreprise: ' . $achat->entreprise->denomination . '</span>';

            // $formattedHtml .= '</div>' .
            //     '</div>';
        // Fake data as a PHP array
        // $data = [
        //     [
        //         "id" => 1,
        //         "type" => "In",
        //         "reference" => "REF123",
        //         "description" => "Received new stock",
        //         "quantity" => 100,
        //         "batch_number" => "BN001",
        //         "departure" => "Warehouse A",
        //         "arrival" => "Warehouse B",
        //         "reason" => "Restocking",
        //         "movement_date" => "2024-07-01",
        //         "delivery_date" => "2024-07-02",
        //         "user" => "John Doe"
        //     ],
        //     [
        //         "id" => 2,
        //         "type" => "Out",
        //         "reference" => "REF124",
        //         "description" => "Dispatched to customer",
        //         "quantity" => 50,
        //         "batch_number" => "BN002",
        //         "departure" => "Warehouse B",
        //         "arrival" => "Customer Location",
        //         "reason" => "Order fulfillment",
        //         "movement_date" => "2024-07-03",
        //         "delivery_date" => "2024-07-04",
        //         "user" => "Jane Smith"
        //     ]
        //     // Add more fake data as needed
        // ];


        // // Sorting
        // usort($data, function ($a, $b) use ($sort, $order) {
        //     if ($order === 'DESC') {
        //         return strcmp($b[$sort], $a[$sort]);
        //     } else {
        //         return strcmp($a[$sort], $b[$sort]);
        //     }
        // });

        // Paginate
        // $total = count($data);
        // $data = array_slice($data, ($page - 1) * $limit, $limit);

        // // Format data for the table
        // $formattedData = array_map(function ($item) {
        //     return [
        //         'id' => $item['id'],
        //         'type' => $item['type'],
        //         'reference' => $item['reference'],
        //         'description' => $item['description'],
        //         'quantity' => $item['quantity'],
        //         'batch_number' => $item['batch_number'],
        //         'departure' => $item['departure'],
        //         'arrival' => $item['arrival'],
        //         'reason' => $item['reason'],
        //         'movement_date' => $item['movement_date'],
        //         'delivery_date' => $item['delivery_date'],
        //         'user' => $item['user'],
        //         'actions' => '<a href="/movements/edit/' . $item['id'] . '" title="Update">' .
        //                      '<i class="bx bx-edit mx-1"></i>' .
        //                      '</a>' .
        //                      '<button title="Delete" type="button" class="btn delete" data-id="' . $item['id'] . '">' .
        //                      '<i class="bx bx-trash text-danger mx-1"></i>' .
        //                      '</button>'
        //     ];
        // }, $data);
