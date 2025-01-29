<?php

namespace App\Http\Controllers;

use App\Models\depot;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use App\Services\DeletionService;

class DepotController extends Controller
{
    //
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    public function index()
    {
        $depots = $this->user->entreprise->depot;
        return view('depots.depot', ['depots' => $depots]);
    }

    public function create()
    {
        return view('depots.create_depot');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $formFields['entreprise_id'] = $this->user->entreprise_id;
        
        $depot = Depot::create($formFields);
        Session::flash('message', 'Depot created successfully.');
        return response()->json(['error' => false, 'id' => $depot->id]);
    }

    public function edit($id)
    {
        $depot = Depot::find($id);
        return view('depots.update_depot', ['depot' => $depot]);
    }

    public function update(Request $request, $id)
    {
        $depot = Depot::find($id);
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $depot->update($formFields);
        Session::flash('message', 'Depot updated successfully.');
        return response()->json(['error' => false]);
    }

    public function destroy($id)
    {
        $response = DeletionService::delete(Depot::class, $id, 'depot');
        return $response;
    }
    

    public function getDepot($id)
    {
        $depot = Depot::findOrFail($id);
        if (!$depot) {
            return response()->json(['error' => 'Depot not found'], 404);
        }

        $response = [
            'id' => $depot->id,
            'name' => $depot->name,
            'address' => $depot->address,
            'city' => $depot->city,
            'country' => $depot->country,
        ];

        return response()->json($response);
    }

    public function list()
    {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';
        $depot_status_filter = request('depot_status_filter', '');
        
        $query = Depot::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        if ($depot_status_filter !== '') {
            $query->where('depots.city', $depot_status_filter);
        }

        $totalDepots = $query->where('depots.entreprise_id', $this->user->entreprise_id)->count();

        $depots = $query->select('depots.*')
            ->leftJoin('entreprises', 'depots.entreprise_id', '=', 'entreprises.id')
            ->where('depots.entreprise_id', $this->user->entreprise_id)
            ->orderBy($sort, $order)
            ->paginate(request('limit'));

        $depots = $depots->through(function ($depot) {
            $actions = '';
            
            $actions .= '<a href="/depots/edit/' . $depot->id . '" title="' . get_label('update', 'Update') . '">' .
                '<i class="bx bx-edit mx-1"></i>' .
                '</a>';

            $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $depot->id . '" data-type="depots">' .
                '<i class="bx bx-trash text-danger mx-1"></i>' .
                '</button>';

            $actions = $actions ?: '-';

            return [
                'id' => $depot->id,
                'name' => $depot->name,
                'address' => $depot->address,
                'city' => $depot->city,
                'country' => $depot->country,
                'created_at' => format_date($depot->created_at, true),
                'updated_at' => format_date($depot->updated_at, true),
                'actions' => $actions
            ];
        });

        return response()->json([
            'rows' => $depots->items(),
            'total' => $totalDepots,
        ]);
    }
}