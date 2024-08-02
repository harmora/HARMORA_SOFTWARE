<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TodosController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commandes = $this->user->commandes()
            ->orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('commandes.list', ['commandes' => $commandes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create()
    {
        return view('todos.create_todo');
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'priority' => ['required'],
            'description' => ['nullable']
        ]);

        $commande = new Commande($formFields);
        $commande->creator()->associate($this->user);
        $commande->save();

        Session::flash('message', 'Commande created successfully.');
        return response()->json(['error' => false, 'id' => $commande->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    
}
