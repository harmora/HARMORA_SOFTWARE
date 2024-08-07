<?php

namespace App\Http\Controllers;

use App\Models\Disponibility;
use App\Models\Entreprise;
use App\Services\DeletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DisponibiliteController extends Controller
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
        // $meetings = isAdminOrHasAllDataAccess() ? $this->workspace->meetings : $this->user->meetings;

        $visibleColumns = getUserPreferences('disponibilities'); // Adjust this based on how you get user preferences

        $disponibilities = $this->user->entreprise->disponibility;
        return view('disponibility.disponibility',['disponibilities'=>$disponibilities],compact('visibleColumns'));


    }
    public function calendar()
    {
        $currentDate = today();

        $events = [];
        $reservations =  $this->user->entreprise->disponibility;

        foreach ($reservations as $disp) {
                // Format the start date in the required format for FullCalendar


                $startDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $disp->start_date_time);
                $endDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $disp->end_date_time);

                // Prepare the event data
                $event = [
                    'dispoId' => $disp->id,
                    'title' => $disp->activity_name,
                    'start' => $startDate,
                    'end' => $endDate,
                    'backgroundColor' => '#007bff',
                    'borderColor' => '#007bff',
                    'textColor' => '#ffffff',
                ];

                // Add the event to the events array
                $events[] = $event;

        }
        return response()->json($events);
    }

    public function show($id)
    {
        // Fetch event data from the database
        $disp = Disponibility::find($id);

        // Check if the event exists
        if ($disp) {
            return response()->json($disp);
        } else {
            return response()->json(['error' => 'Event not found'], 404);
        }
    }

    public function store(Request $request)
    {
        ini_set('max_execution_time', 300);

        // Validate input
        $formFields = $request->validate([
            'activity_name' => 'required',
            'details' => 'nullable',
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
        ]);

        // Combine date and time
        $start_date = $request->input('start_date');
        $start_time = $request->input('start_time');
        $end_date = $request->input('end_date');
        $end_time = $request->input('end_time');

        $formFields['start_date_time'] = $start_date . ' ' . $start_time;
        $formFields['end_date_time'] = $end_date . ' ' . $end_time;

        try {
            // Create the new disponibility
            $new_disponibility = Disponibility::create($formFields);

            // Attach disponibility to the entreprise
            $entreprise = $this->user->entreprise;
            $entreprise->disponibility()->save($new_disponibility);

            // Flash success message
            return response()->json(['error' => false, 'id' => $new_disponibility->id, 'message' => 'Reservation created successfully.']);

        } catch (\Throwable $e) {
            // Handle exceptions and rollback changes
            if (isset($new_disponibility)) {
                $new_disponibility->delete();
            }
            // Return error response
            return response()->json(['error' => true, 'message' => 'Reservation couldn\'t be created, please try again.']);
        }
    }


public function destroy($id)
{
    $response = DeletionService::delete(Disponibility::class, $id, 'disponibility');
    return $response;
}



public function list()
{
    $search = request('search');
    $sort = request('sort') ?: 'id';
    $order = request('order') ?: 'DESC';
    $category = request('category', '');
    $limit = request('limit') ?: 10;

    // Get the authenticated user's entreprise
    $entreprise = auth()->user()->entreprise;

    // Ensure entreprise is available
    if (!$entreprise) {
        return response()->json([
            "rows" => [],
            "total" => 0,
            "message" => "No entreprise associated with the user."
        ], 400);
    }

    $query = Disponibility::where('entreprise_id', $entreprise->id);

    // Search functionality
    if ($search) {

        $query->where(function ($query) use ($search) {
            $query->where('activity_name', 'like', '%' . $search . '%')
                  ->orWhere('details', 'like', '%' . $search . '%');
        });
    }

    // Status filtering
    if ($category !== '') {
        $query->where('category_id', $category);
    }

    $totalDisponibilities = $query->count();

    $disponibilities = $query->orderBy($sort, $order)
                              ->paginate($limit);

    $disponibilities = $disponibilities->through(function ($disponibility) {
        $actions = '';

        $actions .= '<a href="/disponibilities/edit/' . $disponibility->id . '" title="Update">' .
                    '<i class="bx bx-edit mx-1"></i>' .
                    '</a>';

        $actions .= '<button title="Delete" type="button" class="btn delete" data-id="' . $disponibility->id . '" data-type="disponibility">' .
                    '<i class="bx bx-trash text-danger mx-1"></i>' .
                    '</button>';

        $actions = $actions ?: '-';

        return [
            'id' => $disponibility->id,
            'activity_name' => $disponibility->activity_name,
            'details' => $disponibility->details,
            'start_date_time' => $disponibility->start_date_time,
            'end_date_time' => $disponibility->end_date_time,
            'created_at' => $disponibility->created_at,
            'updated_at' => $disponibility->updated_at,
            'actions' => $actions
        ];
    });

    return response()->json([
        "rows" => $disponibilities->items(),
        "total" => $totalDisponibilities,
    ]);
}


 }



