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

}
