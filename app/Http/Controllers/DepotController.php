<?php

namespace App\Http\Controllers;

use App\Models\depot;
use Illuminate\Http\Request;

class DepotController extends Controller
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
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'nullable|string',
        'city' => 'required|string|max:255',
        'country' => 'required|string|max:255',
    ]);
    $validatedData['entreprise_id'] = $this->user->entreprise_id;

    $depot=depot::create($validatedData);

    return response()->json(['error' => false, 'id' => $depot->id]);
}
}
