<?php

namespace App\Http\Controllers;

use App\Models\ProdCategory;
use App\Models\Product;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Throwable;

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

        $products = Product::all();
        $categories = ProdCategory::all();

        return view('products.products', ['products' => $products,'categories'=>$categories]);
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProdCategory::all();
        return view('products.create',['categories'=>$categories]);
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
             'category_id' => ['required', 'exists:prod_categories,id'],
             'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
         ]);

         if ($request->hasFile('photo')) {
             $formFields['photo'] = $request->file('photo')->store('photos', 'public');
         } else {
             $formFields['photo'] = 'photos/no-image.jpg';
         }

         $product = Product::create($formFields);
         $product->product_category_id = $request->input('category_id');
         $product->save();

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


    public function list ()
    {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';
        $category = request('category', '');
    //    ...

        $query = Product::query();

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
            ->join('prod_categories', 'products.product_category_id', '=', 'prod_categories.id')
            ->orderBy($sort, $order)
            ->paginate(request("limit"));

        $products = $products->through(function ($product) {

            $actions = '';

                $actions .= '<a href="/users/edit/' . $product->id . '" title="' . get_label('update', 'Update') . '">' .
                    '<i class="bx bx-edit mx-1"></i>' .
                    '</a>';



                $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $product->id . '" data-type="product-media">' .
                    '<i class="bx bx-trash text-danger mx-1"></i>' .
                    '</button>';


            $actions = $actions ?: '-';


            $photoHtml = "<div class='avatar avatar-md pull-up' title='" . $product->name ."'>
            <a href='/users/profile/" . $product->id . "'>
                <img src='" . ( $product->photo ? asset('storage/' . $product->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
            </a>
          </div>";

            $product_category = '<span class="badge bg-dark"> '.$product->category_name.'</span>';

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
                'category' => $product_category,
                'stock' => $product_stock,
                'stock_def' => $stock_def,
                'profile' => $formattedHtml,
                'price' => $product->price,
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

    public function destroy($id)
    {
        $response = DeletionService::delete(Product::class, $id, 'product');
        return $response;
    }
}



