<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Session;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('status.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('status.create');
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
            'title' => ['required'],
            'color' => ['required']
        ]);
        $slug = generateUniqueSlug($request->title, Status::class);
        $formFields['slug'] = $slug;
        $roleIds = $request->input('role_ids');
        if ($status = Status::create($formFields)) {
            $status->roles()->attach($roleIds);
            return response()->json(['error' => false, 'message' => 'Status created successfully.', 'id' => $status->id, 'status'=>$status]);
        } else {
            return response()->json(['error' => true, 'message' => 'Status couldn\'t created.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $status = Status::orderBy($sort, $order);

        if ($search) {
            $status = $status->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        $total = $status->count();
        $status = $status
            ->paginate(request("limit"))
            ->through(function ($status) {
                $roles = $status->roles->pluck('name')->map(function ($roleName) {
                    return ucfirst($roleName);
                })->implode(', ');
                return [
                    'id' => $status->id,
                    'title' => $status->title,
                    'roles_has_access' => $roles ?: ' - ',
                    'color' => '<span class="badge bg-' . $status->color . '">' . $status->title . '</span>',
                    'created_at' => format_date($status->created_at, true),
                    'updated_at' => format_date($status->updated_at, true),
                ];
            });

        return response()->json([
            "rows" => $status->items(),
            "total" => $total,
        ]);
    }


    public function get($id)
    {
        $status = Status::findOrFail($id);
        $roles = $status->roles()->pluck('id')->toArray();
        return response()->json(['status' => $status, 'roles' => $roles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'color' => ['required']
        ]);
        $slug = generateUniqueSlug($request->title, Status::class, $request->id);
        $formFields['slug'] = $slug;
        $status = Status::findOrFail($request->id);

        if ($status->update($formFields)) {
            $roleIds = $request->input('role_ids');
            $status->roles()->sync($roleIds);
            return response()->json(['error' => false, 'message' => 'Status updated successfully.', 'id' => $status->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Status couldn\'t updated.']);
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
        $status = Status::findOrFail($id);
        $status->projects(false)->update(['status_id' => 0]);
        $status->tasks(false)->update(['status_id' => 0]);
        $response = DeletionService::delete(Status::class, $id, 'Status');
        $data = $response->getData();
        if ($data->error) {
            return response()->json(['error' => true, 'message' => $data->message]);
        }else{
            return response()->json(['error' => false, 'message' => 'Status deleted successfully.', 'id' => $id, 'title' => $status->title]);
        }        
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:statuses,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $status = Status::findOrFail($id);
            $status->projects(false)->update(['status_id' => 0]);
            $status->tasks(false)->update(['status_id' => 0]);
            $deletedIds[] = $id;
            $deletedTitles[] = $status->title;
            DeletionService::delete(Status::class, $id, 'Status');
        }

        return response()->json(['error' => false, 'message' => 'Status(es) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles]);
    }
}
