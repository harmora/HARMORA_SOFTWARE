<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProdCategory;
use App\Models\Product;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Throwable;
use ZipArchive;



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
        $documents = Document::where('entreprise_id', $this->user->entreprise_id)->get();

        return view('documents.documents',['documents' => $documents]);
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        // Add any additional logic or data retrieval here
        return view('documents.update', ['document' => $document]);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'reference' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'user' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'nullable|numeric',
            'remaining_amount' => 'nullable|numeric',
            'from_to' => 'nullable|string|max:255',
        ]);
        // Find the document by ID
        $document = Document::findOrFail($id);
        if ($request->hasFile('upload')) {
            if ($document->facture != 'photos/doc.png' && $document->facture !== null)
                Storage::disk('public')->delete($document->facture);
            $document->facture= $request->file('upload')->store('photos', 'public');
        }
        if ($request->hasFile('upload1')) {
            if ($document->devis != 'photos/doc.png' && $document->devis !== null)
                Storage::disk('public')->delete($document->devis);
            $document->devis= $request->file('upload1')->store('photos', 'public');
        }
        // Update the document with new data
        $document->reference = $request->input('reference');
        $document->description = $request->input('description');
        $document->type = $request->input('type');
        $document->user = $request->input('user');
        $document->total_amount = $request->input('total_amount');
        $document->paid_amount = $request->input('paid_amount');
        $document->remaining_amount = $request->input('remaining_amount');
        $document->from_to = $request->input('from_to');
    
        // Save the updated document
        $document->save();
    
        // Redirect to a specific URL or show a success message
        Session::flash('message', 'document updated successfully.');

        return response()->json(['error' => false, 'id' => $document->id]);    
    }
public function downloadZip($id)
{
    $document = Document::findOrFail($id);       
    if ($document->type=="facture") {
        if($document->origin=='achat')
            return response()->download(storage_path('app/public/' . $document->facture));
        else
            return response()->download(storage_path('app/public/' . $document->facture));
    }
    if ($document->type=="devis") {
        if($document->origin=='achat')
            return response()->download(storage_path('app/public/' . $document->devis));
        else
            return response()->download(storage_path('app/public/' . $document->devis));
    }
}

    public function list(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'reference');
        $order = $request->input('order', 'DESC');
        $document_type_filter = request('document_type_filter', '');
        $document_origin_filter = request('document_origin_filter', '');

        $query = Document::query();
    
        // Filtering based on search term
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('type', 'like', '%' . $search . '%')
                    ->orWhere('reference', 'like', '%' . $search . '%')
                    ->orWhere('facture', 'like', '%' . $search . '%')
                    ->orWhere('devis', 'like', '%' . $search . '%')
                    ->orWhere('user', 'like', '%' . $search . '%')
                    ->orWhere('from_to', 'like', '%' . $search . '%')
                    ->orWhere('total_amount', 'like', '%' . $search . '%')
                    ->orWhere('paid_amount', 'like', '%' . $search . '%')
                    ->orWhere('remaining_amount', 'like', '%' . $search . '%')
                    ;
            });
        }
        if ($document_type_filter !== '') {
            $query->where('type', $document_type_filter);
        }
        if ($document_origin_filter !== '') {
            $query->where('origin', $document_origin_filter);
        }

        $totaldocuments = $query->where('documents.entreprise_id', $this->user->entreprise_id)->count();
    
        $documents = $query->select('documents.*')
        ->leftJoin('entreprises', 'documents.entreprise_id', '=', 'entreprises.id')
        ->where('.entreprise_id', $this->user->entreprise_id)
        ->orderBy($sort, $order)
            ->paginate(request('limit'));


        // Format data for the table
        $documents = $documents->through(function ($document) {
            $actions = '';

                $actions .= '<a href="/documents/edit/' . $document->id . '" title="' . get_label('update', 'Update') . '">' .
                '<i class="bx bx-edit mx-1"></i>' .
                '</a>';

                $actions .= '<a href="/documents/download-zip/' . $document->id . '" title="' . get_label('download', 'Download') . '">' .
                '<i class="bx bx-download mx-1"></i>' .
                '</a>';
                
                $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $document->id . '" data-type="documents">' .
                '<i class="bx bx-trash text-danger mx-1"></i>' .
                '</button>';
            $actions = $actions ?: '-';
            return [
                'reference' => $document->type.'_'.$document->reference,
                'client' => $document->from_to,
                'total_amount' => $document->total_amount,
                'remaining_amount' => $document->remaining_amount,
                'created_by' => $document->user,
                'created_at' => $document->created_at->format('Y-m-d'),
                'origin' => $document->origin,
                'actions' => $actions
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
        'title' => get_label('facture', 'Invoice').' :',
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
        'title' => get_label('facture', 'Invoice').' :',
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
