<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packs = Pack::all();
        return view('packs.packs', ['packs' => $packs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('packs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'number_of_accounts' => 'required|integer',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $formFields['photo'] = $request->file('photo')->store('packs', 'public');
        } else {
            $formFields['photo'] = 'packs/default-image.jpg'; // Default image if none is uploaded
        }

        try {
            $pack = Pack::create($formFields);
            Session::flash('message', 'Pack created successfully.');
            return response()->json(['error' => false, 'id' => $pack->id]);
        } catch (Throwable $e) {
            return response()->json(['error' => true, 'message' => 'Pack could not be created.'.$e]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pack = Pack::findOrFail($id);
        return view('packs.edit', ['pack' => $pack]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'number_of_accounts' => 'required|integer',
            'photo' => 'nullable|image|max:2048',
        ]);

        $pack = Pack::findOrFail($id);

        if ($request->hasFile('photo')) {
            if ($pack->photo != 'packs/default-image.jpg' && $pack->photo !== null) {
                Storage::disk('public')->delete($pack->photo);
            }
            $formFields['photo'] = $request->file('photo')->store('packs', 'public');
        } else {
            $formFields['photo'] = $pack->photo; // Keep the old photo if none is uploaded
        }

        try {
            $pack->update($formFields);
            Session::flash('message', 'Pack updated successfully.');
            return response()->json(['error' => false, 'id' => $pack->id]);
        } catch (Throwable $e) {
            return response()->json(['error' => true, 'message' => 'Pack could not be updated.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pack = Pack::findOrFail($id);
        if ($pack->photo != 'packs/default-image.jpg' && $pack->photo !== null) {
            Storage::disk('public')->delete($pack->photo);
        }
        try {
            $pack->delete();
            return response()->json(['error' => false, 'message' => 'Pack deleted successfully.']);
        } catch (Throwable $e) {
            return response()->json(['error' => true, 'message' => 'Pack could not be deleted.']);
        }
    }

    /**
     * Remove multiple resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request)
    {
        $validatedData = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:packs,id',
        ]);

        $ids = $validatedData['ids'];
        $deletedPacks = [];

        foreach ($ids as $id) {
            $pack = Pack::findOrFail($id);
            if ($pack) {
                if ($pack->photo != 'packs/default-image.jpg' && $pack->photo !== null) {
                    Storage::disk('public')->delete($pack->photo);
                }
                $pack->delete();
                $deletedPacks[] = $id;
            }
        }

        return response()->json(['error' => false, 'message' => 'Pack(s) deleted successfully.', 'ids' => $deletedPacks]);
    }
}
