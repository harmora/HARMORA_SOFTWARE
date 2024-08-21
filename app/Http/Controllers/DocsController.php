<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $documents = Document::all();

        return view('documents.documents',['documents' => $documents]);
    }


    public function list(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'document_number');
        $order = $request->input('order', 'DESC');
        $limit = $request->input('limit', 10);
    
        $query = Document::query();
    
        // Filtering based on search term
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('type', 'like', '%' . $search . '%')
                    ->orWhere('reference', 'like', '%' . $search . '%')
                    ->orWhere('facture', 'like', '%' . $search . '%');
            });
        }
    
        $totaldocuments = $query->count();
    
        $documents = $query->select('documents.*')
            ->orderBy($sort, $order)
            ->paginate($limit);
    
        // Format data for the table
        $documents = $documents->through(function ($document) {
            return [
                'document_number' => $document->type,
                'client' => $document->from_to,
                'total_price' => $document->total_amount,
                'remaining_amount' => $document->remaining_amount,
                'created_by' => $document->user,
                // 'creation_date' => $document->created_at->format('Y-m-d'),
            ];
        });
    
        return response()->json([
            'rows' => $documents->items(),
            'total' => $totaldocuments,
        ]);
    }
    
// public function getfactureinfo($id)
public function getfactureinfo()
{
    $id = 1;
    // Fake data array
    $fakeData = [
        1 => [
            'user' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ],
            'type' => 'Service',
            'paid' => 150,
            'rest' => 50,
            'payroll' => 200,
            'created_at' => '2024-08-12 10:00:00'
        ],
        2 => [
            'user' => [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com'
            ],
            'type' => 'Product',
            'paid' => 300,
            'rest' => 100,
            'payroll' => 400,
            'created_at' => '2024-08-12 12:00:00'
        ],
        // Add more fake data as needed
    ];

    // Retrieve the fake data for the given ID
    $fact = $fakeData[$id];
    $user = $fact['user'];

    $data = [
        'title' => get_label('invoice', 'Invoice').' :',
        'content' => [
            'name' => $user['name'],
            'type' => $fact['type'],
            'paid' => $fact['paid'],
            'rest' => $fact['rest'],
            'tot' => $fact['payroll'],
            'email' => $user['email'],
            'created_at' => $fact['created_at'],
        ],
    ];

    return view('pdf.facture', $data);
}

public function getDownloadFile($id = null)
{
    $id = 1;
    // Fake data array
    $fakeData = [
        1 => [
            'user' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ],
            'type' => 'Service',
            'paid' => 150,
            'rest' => 50,
            'payroll' => 200,
            'created_at' => '2024-08-12 10:00:00'
        ],
        2 => [
            'user' => [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com'
            ],
            'type' => 'Product',
            'paid' => 300,
            'rest' => 100,
            'payroll' => 400,
            'created_at' => '2024-08-12 12:00:00'
        ],
        // Add more fake data as needed
    ];

    // Retrieve the fake data for the given ID
    $fact = $fakeData[$id];
    $user = $fact['user'];

    $data = [
        'title' => get_label('invoice', 'Invoice').' :',
        'content' => [
            'name' => $user['name'],
            'type' => $fact['type'],
            'paid' => $fact['paid'],
            'rest' => $fact['rest'],
            'tot' => $fact['payroll'],
            'email' => $user['email'],
            'created_at' => $fact['created_at'],
        ],
    ];

    $pdf = Pdf::loadview('pdf.facture', $data);
    $pdfname = "facture.pdf";
    return $pdf->stream($pdfname);
}












}
