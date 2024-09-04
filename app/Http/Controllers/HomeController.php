<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
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
    public function index(Request $request)
    {
        // $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects ?? [] : $this->user->projects ?? [];
        // $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks ?? [] : $this->user->tasks() ?? [];
        // $tasks = $tasks ? $tasks->count() : 0;
        $users = User::all();
        $clients =  Client::all();

        $todos = $this->user->todos()
            ->orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $total_todos = $this->user->todos;
        // $meetings = isAdminOrHasAllDataAccess() ? $this->workspace->meetings ?? [] : $this->user->meetings ?? [];
        $meetings = $this->user->meetings ?? [];

        return view('dashboard', ['users' => $users, 'clients' => $clients, 'projects' => 0, 'tasks' => 0, 'todos' => $todos, 'total_todos' => $total_todos, 'meetings' => $meetings, 'auth_user' => $this->user]);
    }


//     public function getChiffreAffaires(Request $request)
//     {
//         $grouping = $request->query('group_by', 'month'); // Default to 'month' if not provided
//         $year = $request->query('year', date('Y')); // Default to current year

//         $query = DB::table('factures')->whereYear('date', $year);

//         if ($grouping === 'year') {
//             $chiffreAffaires = $query->select(DB::raw('YEAR(date) as period'), DB::raw('SUM(grand_total) as total'))
//                 ->groupBy('period')
//                 ->orderBy('period', 'asc')
//                 ->get();
//         } elseif ($grouping === 'day') {
//             $chiffreAffaires = $query->select(DB::raw('DATE(date) as period'), DB::raw('SUM(grand_total) as total'))
//                 ->groupBy('period')
//                 ->orderBy('period', 'asc')
//                 ->get();
//         } else {
//             // Default to grouping by month
//             $chiffreAffaires = $query->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as period'), DB::raw('SUM(grand_total) as total'))
//                 ->groupBy('period')
//                 ->orderBy('period', 'asc')
//                 ->get();
//         }

//         return response()->json($chiffreAffaires);
//     }



//     public function getChiffreAffaireParCategorie()
// {
//     // Fetch the total revenue per category from the facture table
//     $revenues = DB::table('factures')
//         ->select('categorie', DB::raw('SUM(grand_total) as total'))
//         ->groupBy('categorie')
//         ->get();

//     // Calculate the total revenue
//     $totalRevenue = $revenues->sum('total');

//     // Calculate the percentage of revenue per category
//     $data = $revenues->map(function($revenue) use ($totalRevenue) {
//         return [
//             'categorie' => $revenue->categorie,
//             'percentage' => round(($revenue->total / $totalRevenue) * 100, 2)
//         ];
//     });

//     // Return the data as a JSON response
//     return response()->json($data);
// }
// }


public function getChiffreAffaires(Request $request)
{
    $grouping = $request->query('group_by', 'month'); // Default to 'month' if not provided
    $year = $request->query('year', date('Y')); // Default to current year

    $query = DB::table('commandes')->whereYear('start_date', $year);

    if ($grouping === 'year') {
        $chiffreAffaires = $query->select(DB::raw('YEAR(start_date) as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    } elseif ($grouping === 'day') {
        $chiffreAffaires = $query->select(DB::raw('DATE(start_date) as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    } else {
        // Default to grouping by month
        $chiffreAffaires = $query->select(DB::raw('DATE_FORMAT(start_date, "%Y-%m") as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    }

    return response()->json($chiffreAffaires);
}

public function getChiffreAffaireParCategorie()
{
    // Fetch the total revenue per category from the commandes table
    $revenues = DB::table('commandes')
        ->select('status as categorie', DB::raw('SUM(total_amount) as total'))
        ->groupBy('categorie')
        ->get();

    // Calculate the total revenue
    $totalRevenue = $revenues->sum('total');

    // Calculate the percentage of revenue per category
    $data = $revenues->map(function($revenue) use ($totalRevenue) {
        return [
            'categorie' => $revenue->categorie,
            'percentage' => round(($revenue->total / $totalRevenue) * 100, 2)
        ];
    });

    // Return the data as a JSON response
    return response()->json($data);
}

}



