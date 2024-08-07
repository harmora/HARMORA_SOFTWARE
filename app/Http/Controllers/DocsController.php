<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProdCategory;
use App\Models\Product;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Session;
use Throwable;


use Illuminate\Support\Str;

class DocsController extends Controller
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


        $visibleColumns = getUserPreferences('documents');
        $documents = Product::all();

        return view('documents.documents',compact('visibleColumns'));
    }



    public function list(Request $request)
{
    // Fake data as a PHP array
    $data = [
        [
            "document_number" => "Devis_D24/46",
            "order" => 103,
            "client" => "LMARCHICOM",
            "total_price" => 5400,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-23"
        ],
        [
            "document_number" => "Devis_D24/45",
            "order" => 97,
            "client" => "LMARCHICOM",
            "total_price" => 9840,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-23"
        ],
        [
            "document_number" => "Devis_D24/44",
            "order" => 97,
            "client" => "LMARCHICOM",
            "total_price" => 6240,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-23"
        ],
        [
            "document_number" => "Devis_D24/43",
            "order" => 97,
            "client" => "LMARCHICOM",
            "total_price" => 9840,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-23"
        ],
        [
            "document_number" => "Devis_D24/42",
            "order" => 100,
            "client" => "ibrahim",
            "total_price" => 3240,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-22"
        ],
        [
            "document_number" => "Devis_D24/41",
            "order" => 99,
            "client" => "MEDYANE",
            "total_price" => 4950,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-19"
        ],
        [
            "document_number" => "Devis_D24/40",
            "order" => 97,
            "client" => "LMARCHICOM",
            "total_price" => 4440,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-19"
        ],
        [
            "document_number" => "Devis_D24/39",
            "order" => 95,
            "client" => "~Coopérative zahrat El bahar",
            "total_price" => 3240,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-17"
        ],
        [
            "document_number" => "Devis_D24/38",
            "order" => 94,
            "client" => "Tüyler",
            "total_price" => 1800,
            "remaining_amount" => 0,
            "created_by" => "yassine",
            "creation_date" => "2024-04-12"
        ]
        // Add more fake data as needed
    ];

    // Apply search, sort, and pagination logic
    $search = $request->input('search');
    $sort = $request->input('sort', 'document_number');
    $order = $request->input('order', 'DESC');
    $limit = $request->input('limit', 10);
    $page = $request->input('page', 1);

    // Filtering based on search term
    if ($search) {
        $data = array_filter($data, function ($item) use ($search) {
            return Str::contains($item['document_number'], $search) || Str::contains($item['client'], $search);
        });
    }

    // Sorting
    usort($data, function ($a, $b) use ($sort, $order) {
        if ($order === 'DESC') {
            return strcmp($b[$sort], $a[$sort]);
        } else {
            return strcmp($a[$sort], $b[$sort]);
        }
    });

    // Paginate
    $total = count($data);
    $data = array_slice($data, ($page - 1) * $limit, $limit);

    // Format data for the table
    $formattedData = array_map(function ($item) {
        return [
            'document_number' => $item['document_number'],
            'order' => $item['order'],
            'client' => $item['client'],
            'total_price' => $item['total_price'],
            'remaining_amount' => $item['remaining_amount'],
            'created_by' => $item['created_by'],
            'creation_date' => $item['creation_date'],
            'actions' => '<a href="/documents/edit/' . $item['document_number'] . '" title="Update">' .
                         '<i class="bx bx-edit mx-1"></i>' .
                         '</a>' .
                         '<button title="Delete" type="button" class="btn delete" data-id="' . $item['document_number'] . '">' .
                         '<i class="bx bx-trash text-danger mx-1"></i>' .
                         '</button>'
        ];
    }, $data);

    return response()->json([
        "rows" => $formattedData,
        "total" => $total,
    ]);
}

}
