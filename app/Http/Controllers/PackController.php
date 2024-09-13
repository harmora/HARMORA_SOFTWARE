<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Pack;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Load packs with their associated features
        $packs = Pack::with('features')->get();
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
        // Validate the input fields including features
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'number_of_accounts' => 'required|integer',
            'features' => 'required|string', // This will be a comma-separated string of feature names
        ]);

        // Create the pack
        try {
            $pack = Pack::create([
                'name' => $formFields['name'],
                'description' => $formFields['description'],
                'number_of_accounts' => $formFields['number_of_accounts'],
            ]);

            // Process features and attach to the pack
            $featureNames = explode(',', $formFields['features']);
            $features = [];
            foreach ($featureNames as $featureName) {
                $featureName = trim($featureName); // Remove any extra spaces
                $feature = Feature::firstOrCreate(['name' => $featureName]);
                $features[] = $feature->id; // Add the feature's ID to the list
            }

            // Attach features to the pack (many-to-many relationship)
            $pack->features()->sync($features);

            Session::flash('message', 'Pack created successfully.');
            return response()->json(['error' => false, 'id' => $pack->id]);
        } catch (Throwable $e) {
            return response()->json(['error' => true, 'message' => 'Pack could not be created.' . $e]);
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
        // Load pack with features
        $pack = Pack::with('features')->findOrFail($id);
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
        // Validate the input fields including features
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'number_of_accounts' => 'required|integer',
            'features' => 'required|string', // This will be a comma-separated string of feature names
        ]);

        $pack = Pack::findOrFail($id);

        try {
            // Update the pack fields
            $pack->update([
                'name' => $formFields['name'],
                'description' => $formFields['description'],
                'number_of_accounts' => $formFields['number_of_accounts'],
            ]);

            // Process features and attach to the pack
            $featureNames = explode(',', $formFields['features']);
            $features = [];
            foreach ($featureNames as $featureName) {
                $featureName = trim($featureName);
                $feature = Feature::firstOrCreate(['name' => $featureName]);
                $features[] = $feature->id;
            }

            // Sync the features to update the relationship
            $pack->features()->sync($features);

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
        try {
            // Detach all features before deleting
            $pack->features()->detach();
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
                // Detach features before deleting
                $pack->features()->detach();
                $pack->delete();
                $deletedPacks[] = $id;
            }
        }

        return response()->json(['error' => false, 'message' => 'Pack(s) deleted successfully.', 'ids' => $deletedPacks]);
    }
}
