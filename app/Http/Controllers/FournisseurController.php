<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Forme_juridique;
use App\Models\fournisseur;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    //
    public function index()
    {
        $fournisseurs = fournisseur::all();
        $entreprises = Entreprise::all();
        $formesjuridique= Forme_juridique::all();
        // $visibleColumns = getUserPreferences('entreprises'); // Adjust this based on how you get user preferences
        return view('fournisseurs.fournisseurs',['fournisseurs'=> $fournisseurs,'entreprises'=> $entreprises,'fomesJuridique'=> $formesjuridique]);
    }

    public function create()
    {
        $entreprises = Entreprise::all();
        return view('fournisseurs.create_fournisseurs', ['entreprises' => $entreprises]);
    }
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:fournisseurs,email',
            'phone' => 'nullable',
            'city' => 'nullable',
            'country' => 'nullable',
            'entreprise_id' => 'nullable|exists:entreprises,id'
        ]);

        $fournisseur = Fournisseur::create($formFields);
        Session::flash('message', 'Fournisseur created successfully.');
        return response()->json(['error' => false, 'id' => $fournisseur->id]);
    }

    public function list()
{
    $search = request('search');
    $sort = request('sort') ?: 'id';
    $order = request('order') ?: 'DESC';
    $status = request('status', '');

    $query = Fournisseur::query();

    // Search functionality
    if ($search) {
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    // Status filtering
    if ($status !== '') {
        $query->where('status', $status);
    }

    $totalFournisseurs = $query->count();

    $fournisseurs = $query->orderBy($sort, $order)
        ->paginate(request("limit"));

    $fournisseurs = $fournisseurs->through(function ($fournisseur) {

        $actions = '';

        $actions .= '<a href="/fournisseurs/edit/' . $fournisseur->id . '" title="' . get_label('update', 'Update') . '">' .
            '<i class="bx bx-edit mx-1"></i>' .
            '</a>';

        $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $fournisseur->id . '" data-type="fournisseurs">' .
            '<i class="bx bx-trash text-danger mx-1"></i>' .
            '</button>';

        $actions = $actions ?: '-';

        $statusBadge = $fournisseur->status === 1
            ? '<span class="badge bg-success">' . get_label('active', 'Active') . '</span>'
            : '<span class="badge bg-danger">' . get_label('inactive', 'Inactive') . '</span>';

        return [
            'id' => $fournisseur->id,
            'name' => $fournisseur->name,
            'phone' => $fournisseur->phone,
            'email'=> $fournisseur->email,
            'status' => $statusBadge,
            'created_at' => format_date($fournisseur->created_at, true),
            'updated_at' => format_date($fournisseur->updated_at, true),
            'actions' => $actions
        ];
    });

    return response()->json([
        "rows" => $fournisseurs->items(),
        "total" => $totalFournisseurs,
    ]);
}

}
