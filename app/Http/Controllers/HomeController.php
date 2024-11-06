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
use App\Models\Commande;
use App\Models\Entreprise;
use App\Models\invoice;
use App\Models\Pack;
use App\Models\ProdCategory;

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


        $users =  auth()->user()->entreprise->user;
        $clients = auth()->user()->entreprise->client;
        $products = auth()->user()->entreprise->product;
        $commandes = auth()->user()->entreprise->invoice;

           // Initialize variables for counting entreprises, users, and admins
    $entrepriseforadmin = null;
    $usersforadmin = null;
    $adminsforadmin = null;

    // If the logged-in user is an admin, get the counts for entreprises, users, and admins
    if (auth()->user()->role->rolename === 'admin') {
        // Count all entreprises
        $entrepriseforadmin = Entreprise::count();

        // Count all users (excluding admins)
        $usersforadmin = User::whereHas('role', function ($query) {
            $query->where('rolename', 'user');
        })->count();

        $adminsforadmin = User::whereHas('role', function ($query) {
            $query->where('rolename', 'admin');
        })->count();
    }

            $currentYear = date('Y');

            // Query to fetch total revenue for the current year
            $totalRevenue = DB::table('invoices')
            ->where('entreprise_id', $this->user->entreprise->id)
            ->whereYear('due_date', $currentYear)
            ->whereIn('status', ['completed', 'validated'])
            ->sum('total_amount');

        return view('dashboard', ['commandes'=> $commandes,'users' => $users, 'clients' => $clients, 'ca' => $totalRevenue, 'products' => $products,
        'auth_user' => $this->user,
        'entrepriseforadmin' => $entrepriseforadmin,
        'usersforadmin' => $usersforadmin,
        'adminsforadmin' => $adminsforadmin]);
    }

    public function getPackStatistics()
{
    // Fetch packs with the count of entreprises
    $packs = Pack::withCount('entreprises')->get(['name', 'entreprises_count']);

    // Return the data as JSON
    return response()->json($packs);
}




// public function getChiffreAffaires(Request $request)
// {
//     $grouping = $request->query('group_by', 'month'); // Default to 'month' if not provided
//     $year = $request->query('year', date('Y')); // Default to current year

//     $query = DB::table('commandes')->whereYear('due_date', $year)
//     ->where('entreprise_id',$this->user->entreprise->id)
//     ->where('status', 'completed')

//     ;

//     if ($grouping === 'year') {
//         $chiffreAffaires = $query->select(DB::raw('YEAR(due_date) as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     } elseif ($grouping === 'day') {
//         $chiffreAffaires = $query->select(DB::raw('DATE(due_date) as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     } else {
//         // Default to grouping by month
//         $chiffreAffaires = $query->select(DB::raw('DATE_FORMAT(due_date, "%Y-%m") as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     }

//     return response()->json($chiffreAffaires);
// }


// public function getChiffreAffaires(Request $request)
// {
//     $grouping = $request->query('group_by', 'month'); // Default to 'month' if not provided
//     $year = $request->query('year', date('Y')); // Default to current year

//     $query = DB::table('commandes')
//         ->whereYear('due_date', $year)
//         ->where('entreprise_id', $this->user->entreprise->id)
//         ->where('status', 'completed');

//     if ($grouping === 'year') {
//         $chiffreAffaires = $query->select(DB::raw('YEAR(due_date) as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     } elseif ($grouping === 'day') {
//         $chiffreAffaires = $query->select(DB::raw('DATE(due_date) as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     } else {
//         // Default to grouping by month
//         $chiffreAffaires = $query->select(DB::raw('DATE_FORMAT(due_date, "%Y-%m") as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     }

//     // Accumulate the values
//     $accumulated = [];
//     $runningTotal = 0;

//     foreach ($chiffreAffaires as $ca) {
//         $runningTotal += $ca->total; // Add the current period's total to the running total
//         $accumulated[] = [
//             'period' => $ca->period,
//             'total' => $runningTotal
//         ];
//     }

//     return response()->json($accumulated);
// }


public function getChiffreAffaires(Request $request)
{
    $grouping = $request->query('group_by', 'month'); // Default to 'month' if not provided
    $year = $request->query('year', date('Y')); // Default to current year

    $query = DB::table('invoices')
        ->whereYear('due_date', $year)
        ->where('entreprise_id', $this->user->entreprise->id)
        ->whereIn('status', ['completed', 'validated']);

    if ($grouping === 'year') {
        $chiffreAffaires = $query->select(DB::raw('YEAR(due_date) as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    } elseif ($grouping === 'day') {
        $chiffreAffaires = $query->select(DB::raw('DATE(due_date) as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    } else {
        // Default to grouping by month
        $chiffreAffaires = $query->select(DB::raw('DATE_FORMAT(due_date, "%Y-%m") as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    }

    // Accumulate the values
    $accumulated = [];
    $runningTotal = 0;

    foreach ($chiffreAffaires as $ca) {
        $runningTotal += $ca->total; // Add the current period's total to the running total
        $accumulated[] = [
            'period' => $ca->period,
            'total' => number_format($runningTotal, 2, '.', '') // Limit to 2 decimal places with correct formatting
        ];
    }

    return response()->json($accumulated);
}




public function getChiffreAffaireParCategorie()
{
    // Fetch the total revenue per category from the commandes table
    $revenues = DB::table('invoices')
    ->where('entreprise_id',$this->user->entreprise->id)
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

public function getChiffreAffaireParCategorieProduit()
{
    // Fetch the commandes with their related products and categories
    $commandes = invoice::with(['products'])->get()
    ->where('entreprise_id',$this->user->entreprise->id);

    // Initialize an array to store total revenue per category
    $categoryRevenue = [];

    // Loop through each commande to accumulate revenue per product category with TVA
    foreach ($commandes as $commande) {
        foreach ($commande->products as $product) {
            // Ensure that the product has a category before trying to access it


                $category = ProdCategory::find($product->product_category_id);
                $categoryName = $category->name_cat;

                // Calculate the revenue for this product in this commande including TVA
                $revenue = $product->pivot->quantity * $product->pivot->price * (1 + $commande->tva / 100);

                // Accumulate the revenue for the category
                if (isset($categoryRevenue[$categoryName])) {
                    $categoryRevenue[$categoryName] += $revenue;
                } else {
                    $categoryRevenue[$categoryName] = $revenue;
                }

        }
    }

    // Calculate the total revenue across all categories
    $totalRevenue = array_sum($categoryRevenue);

    // Prepare the data with the percentage of total revenue per category
    $data = collect($categoryRevenue)->map(function($total, $category) use ($totalRevenue) {
        return [
            'productCategorie' => $category,
            'productPercentage' => round(($total / $totalRevenue) * 100, 2),
        ];
    })->values();

    // Return the data as a JSON response
    return response()->json($data);
}


// public function getClientChiffreAffaires(Request $request)
// {
//     $grouping = $request->query('group_by', 'month'); // Default to 'month' if not provided
//     $year = $request->query('year', date('Y')); // Default to current year
//     $clientId = $request->query('client_id'); // Client ID

//     // Ensure client ID is provided
//     if (!$clientId) {
//         return response()->json(['error' => 'Client ID is required'], 400);
//     }

//     $query = DB::table('commandes')
//     ->where('entreprise_id',$this->user->entreprise->id)
//         ->whereYear('due_date', $year)
//         ->where('status', 'completed')
//         ->where('client_id', $clientId);

//     if ($grouping === 'year') {
//         $chiffreAffaires = $query->select(DB::raw('YEAR(due_date) as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     } elseif ($grouping === 'day') {
//         $chiffreAffaires = $query->select(DB::raw('DATE(due_date) as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     } else {
//         // Default to grouping by month
//         $chiffreAffaires = $query->select(DB::raw('DATE_FORMAT(due_date, "%Y-%m") as period'), DB::raw('SUM(total_amount) as total'))
//             ->groupBy('period')
//             ->orderBy('period', 'asc')
//             ->get();
//     }

//     return response()->json($chiffreAffaires);
// }


public function getClientChiffreAffaires(Request $request)
{
    $grouping = $request->query('group_by', 'month'); // Default to 'month' if not provided
    $year = $request->query('year', date('Y')); // Default to current year
    $clientId = $request->query('client_id'); // Client ID

    // Ensure client ID is provided
    if (!$clientId) {
        return response()->json(['error' => 'Client ID is required'], 400);
    }

    $query = DB::table('invoices')
        ->where('entreprise_id', $this->user->entreprise->id)
        ->whereYear('due_date', $year)
        ->whereIn('status', ['completed', 'validated'])
        ->where('client_id', $clientId);

    if ($grouping === 'year') {
        $chiffreAffaires = $query->select(DB::raw('YEAR(due_date) as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    } elseif ($grouping === 'day') {
        $chiffreAffaires = $query->select(DB::raw('DATE(due_date) as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    } else {
        // Default to grouping by month
        $chiffreAffaires = $query->select(DB::raw('DATE_FORMAT(due_date, "%Y-%m") as period'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    }

    // Accumulate the values
    $accumulated = [];
    $runningTotal = 0;

    foreach ($chiffreAffaires as $ca) {
        $runningTotal += $ca->total; // Add the current period's total to the running total
        $accumulated[] = [
            'period' => $ca->period,
            'total' => number_format($runningTotal, 2, '.', '') // Limit to 2 decimal places
        ];
    }

    return response()->json($accumulated);
}



}



