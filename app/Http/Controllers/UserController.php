<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Forme_juridique;
use App\Models\Entreprise;
use App\Models\Project;
use App\Models\TaskUser;
use App\Models\Template;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use GuzzleHttp\Promise\TaskQueue;
use App\Notifications\VerifyEmail;
use Spatie\Permission\Models\Role;
use App\Models\UserClientPreference;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AccountCreation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Contracts\Role as ContractsRole;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $workspace = Workspace::find(session()->get('workspace_id'));

        $users = User::all();
        $roles = Role::where('guard_name', 'web')->get();
        return view('users.users', ['users' => $users, 'roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'web')->get();
        $formesJuridique = Forme_juridique::all(); // Fetch all formes juridiques
        $entreprises= Entreprise::all();
        return view('users.create_user', ['roles' => $roles,'formesJuridique' => $formesJuridique,'entreprises'=>$entreprises]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        ini_set('max_execution_time', 300);
        $formFields = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'addressuser' => 'nullable',
            'phone' => 'nullable',
            'country_code' => 'nullable',
            'cityuser' => 'nullable',
            'stateuser' => 'nullable',
            'countryuser' => 'nullable',
            'entreprise_id' => 'nullable',
            //'dob' => 'nullable',
            //'doj' => 'nullable',
            //'role' => 'required'
        ]);

        // $workspace = Workspace::find(session()->get('workspace_id'));

        // $dob = $request->input('dob');
        // $doj = $request->input('doj');
        // $formFields['dob'] = format_date($dob, false, app('php_date_format'), 'Y-m-d');
        // $formFields['doj'] = format_date($doj, false, app('php_date_format'), 'Y-m-d');

        $password = $request->input('password');
        $formFields['password'] = bcrypt($password);
        if ($request->hasFile('photo')) {
            $formFields['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            $formFields['photo'] = 'photos/no-image.jpg';
        }

        $require_ev = isAdminOrHasAllDataAccess() && $request->has('require_ev') && $request->input('require_ev') == 0 ? 0 : 1;
        $status = isAdminOrHasAllDataAccess() && $request->has('status') && $request->input('status') == 1 ? 1 : 0;
        if ($require_ev == 0) {
            $formFields['email_verified_at'] = now()->tz(config('app.timezone'));
        }
        $formFields['status'] = $status;
            // $user = User::create($formFields);
        // required denomination,address,city, state, country
        $user = User::create([
                'first_name' => $formFields['first_name'],
                'last_name' => $formFields['last_name'],
                'email' => $formFields['email'],
                'country_code' => $formFields['country_code'],
                'phone' => $formFields['phone'],
                'password' => $formFields ['password'],
                'address' => $formFields['addressuser'],
                'city' => $formFields['cityuser'],
                'state' => $formFields['stateuser'],
                'country' => $formFields['countryuser'],
                'dob' => $request->input('dob'),
                'doj' => $request->input('doj'),
                'photo' => $formFields['photo'],
                'status' => $formFields['status'],
                'entreprise_id' => $formFields['entreprise_id'],
            ]);
        $user->assignRole($request->input('role'));
        try {
            if ($require_ev == 1) {
                $user->notify(new VerifyEmail($user));
            }

            // $workspace->users()->attach($user->id);

            if (isEmailConfigured()) {
                $account_creation_template = Template::where('type', 'email')
                    ->where('name', 'account_creation')
                    ->first();
                if (!$account_creation_template || ($account_creation_template->status !== 0)) {
                    $user->notify(new AccountCreation($user, $password));
                }
            }
            Session::flash('message', 'User created successfully.');
            return response()->json(['error' => false, 'id' => $user->id]);
        } catch (TransportExceptionInterface $e) {

            $user = User::findOrFail($user->id);
            $user->delete();
            return response()->json(['error' => true, 'message' => 'User couldn\'t be created, please make sure email settings are oprational.']);
        } catch (Throwable $e) {
            // dd($e->getMessage());
            // Catch any other throwable, including non-Exception errors

            $user = User::findOrFail($user->id);
            $user->delete();
            return response()->json(['error' => true, 'message' => 'User couldn\'t be created, please make sure email settings are oprational.']);
        }
    }

    public function email_verification()
    {
        $user = getAuthenticatedUser();
        if (!$user->hasVerifiedEmail()) {
            return view('auth.verification-notice');
        } else {
            return redirect('/home');
        }
    }

    public function resend_verification_link(Request $request)
    {
        if (isEmailConfigured()) {
            try {
                $request->user()->notify(new VerifyEmail($request->user()));
                Session::flash('message', 'Verification link sent successfully.');
            } catch (TransportExceptionInterface $e) {
                Session::flash('error', 'Verification link couldn\'t be sent, please check email settings.');
            } catch (Throwable $e) {
                Session::flash('error', 'Verification link couldn\'t be sent, please check email settings.');
            }
        } else {
            Session::flash('error', 'Verification link couldn\'t be sent, please check email settings.');
        }
        return back();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function edit($id)
     {
         $user = User::findOrFail($id);
        //  if($user->entreprise_id == null)
        //     $entreprise = [
        //         'denomination' => '',
        //         'ICE' => '',
        //         'RC' => '',
        //         'IF' => '',
        //         'address' => '',
        //         'city' => '',
        //         'state' => '',
        //         'country' => '',
        //         'forme_juridique_id' => '', // Update this field
        //     ];  
        //  else
        $entreprise = Entreprise::all();
        $roles = Role::where('guard_name', 'web')->get();
        $formesJuridique = Forme_juridique::all(); // Assuming you have a FormeJuridique model

         // Debugging lines
         // dd($user, $entreprise, $formesJuridique);

         return view('users.edit_user', [
             'user' => $user,
             'roles' => $roles,
             'entreprise' => $entreprise,
             'formesJuridique' => $formesJuridique,
         ]);
     }


    public function update_user(Request $request, $id)
    {
        $formFields = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
            // 'password' => 'required|min:6',
            // 'password_confirmation' => 'required|same:password',
            'addressuser' => 'nullable',
            'phone' => 'nullable',
            'country_code' => 'nullable',
            'cityuser' => 'nullable',
            'stateuser' => 'nullable',
            'countryuser' => 'nullable',
            'password' => 'nullable|min:6',
            'password_confirmation' => 'required_with:password|same:password',
            'entreprise_id' => 'nullable',
            //'dob' => 'nullable',
            //'doj' => 'nullable',
            //'role' => 'required'
        ]);
        $user = User::findOrFail($id);
        if ($request->hasFile('upload')) {
            if ($user->photo != 'photos/no-image.jpg' && $user->photo !== null)
                Storage::disk('public')->delete($user->photo);

            $formFields['photo'] = $request->file('upload')->store('photos', 'public');
        }
        else {
            // If no new photo is uploaded, keep the old one
            $formFields['photo'] = $user->photo;
        }

        $status = isAdminOrHasAllDataAccess() && $request->has('status') ? $request->input('status') : $user->status;
        $formFields['status'] = $status;

        if (isAdminOrHasAllDataAccess() && isset($formFields['password']) && !empty($formFields['password'])) {
            $formFields['password'] = bcrypt($formFields['password']);
        } else {
            unset($formFields['password']);
        }
        // $entreprise->update([
        //     'denomination' => $formFields['denomenation_u'],
        //     'ICE' => $formFields['ICE'],
        //     'RC' => $formFields['RC'],
        //     'IF' => $formFields['IF'],
        //     'address' => $formFields['address'],
        //     'city' => $formFields['city'],
        //     'state' => $formFields['state'],
        //     'country' => $formFields['country'],
        //     'forme_juridique_id' => $formFields['forme_juridique_id'], // Update this field
        // ]);
        $user->update([
            'first_name' => $formFields['first_name'],
            'last_name' => $formFields['last_name'],
            'email' => $formFields['email'],
            'country_code' => $formFields['country_code'],
            'phone' => $formFields['phone'],
            'address' => $formFields['addressuser'],
            'city' => $formFields['cityuser'],
            'state' => $formFields['stateuser'],
            'country' => $formFields['countryuser'],
            'dob' => $request->input('dob'),
            'doj' => $request->input('doj'),
            'photo' => $formFields['photo'],
            'status' => $formFields['status'],
            'entreprise_id' => $formFields['entreprise_id'],
        ]);
        $user->syncRoles($request->input('role'));

        Session::flash('message', 'Profile details updated successfully.');
        return response()->json(['error' => false, 'id' => $user->id]);
    }

    public function update_photo(Request $request, $id)
    {
        if ($request->hasFile('upload')) {
            $old = User::findOrFail($id);
            if ($old->photo != 'photos/no-image.jpg' && $old->photo !== null)
                Storage::disk('public')->delete($old->photo);
            $formFields['photo'] = $request->file('upload')->store('photos', 'public');
            User::findOrFail($id)->update($formFields);
            return back()->with('message', 'Profile picture updated successfully.');
        } else {
            return back()->with('error', 'No profile picture selected.');
        }
    }

    public function delete_user($id)
    {

        $user = User::findOrFail($id);
        $user->todos()->delete();

        $response = DeletionService::delete(User::class, $id, 'User');
        // UserClientPreference::where('user_id', 'u_' . $id)->delete();

        return $response;
    }

    public function delete_multiple_user(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:users,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedUsers = [];
        $deletedUserNames = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $user = User::findOrFail($id);
            if ($user) {
                $deletedUsers[] = $id;
                $deletedUserNames[] = $user->first_name . ' ' . $user->last_name;
                DeletionService::delete(User::class, $id, 'User');
                UserClientPreference::where('user_id', 'u_' . $id)->delete();
                $user->todos()->delete();
            }
        }
        return response()->json(['error' => false, 'message' => 'User(s) deleted successfully.', 'id' => $deletedUsers, 'titles' => $deletedUserNames]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            auth('web')->logout();
        } else {
            auth('client')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'Logged out successfully.');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        $userExists = User::where('email', $request->email)->exists();
        $clientExists = Client::where('email', $request->email)->exists();
        $logged_in = false;

        if ($userExists) {
            $user = User::where('email', $formFields['email'])->first();
            if ($user->hasRole('admin') || $user->status == 1) {
                if (auth('web')->attempt($formFields)) {
                    $user = auth('web')->user();
                    $logged_in = true;
                }
            } else {
                return response()->json(['error' => true, 'message' => get_label('status_not_active', 'Your account is currently inactive. Please contact the admin for assistance.')]);
            }
        } elseif ($clientExists) {
            $user = Client::where('email', $formFields['email'])->first();
            if ($user->internal_purpose == 0) {
                if ($user->status == 1) {
                    if (auth('client')->attempt($formFields)) {
                        $user = auth('client')->user();
                        $logged_in = true;
                    }
                } else {
                    return response()->json(['error' => true, 'message' => get_label('status_not_active', 'Your account is currently inactive. Please contact the admin for assistance.')]);
                }
            } else {
                return response()->json(['error' => true, 'message' => get_label('account_internal_purpose', 'Your account is recognized for internal purposes, Please contact the admin for assistance.')]);
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Account not found!']);
        }

        if ($logged_in) {
            $my_locale = $locale = isset($user->lang) && !empty($user->lang) ? $user->lang : 'en';
            $data = ['user_id' => $user->id, 'my_locale' => $my_locale, 'locale' => $locale];
            session()->put($data);
            $request->session()->regenerate();

            Session::flash('message', 'Logged in successfully.');
            return response()->json(['error' => false]);
        } else {
            return response()->json(['error' => true, 'message' => 'Invalid credentials!']);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        // $workspace = Workspace::find(session()->get('workspace_id'));
        // $projects = isAdminOrHasAllDataAccess('user', $id) ? $workspace->projects : $user->projects;
        // $tasks = isAdminOrHasAllDataAccess() ? $workspace->tasks->count() : $user->tasks->count();
        $users = User::all();
        $clients = Client::all();
        $entreprise = Entreprise::findOrFail($user->entreprise_id);
        $formeJuridiqueName = $entreprise->forme_juridique->label; // Assuming 'label' is the column name for the name
        return view('users.user_profile', ['user' => $user, 'users' => $users, 'clients' => $clients,'entreprise' => $entreprise,'formeJuridiqueName'=>$formeJuridiqueName, 'auth_user' => getAuthenticatedUser()]);
    }

    public function list()
    {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';
        $status = request('status', '');
        $role_ids = request('role_ids', []);

        $query = User::query();

        // Search functionality
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Status filtering
        if ($status !== '') {
            $query->where('status', $status);
        }

        // Role filtering
        if (!empty($role_ids)) {
            $query->whereHas('roles', function ($query) use ($role_ids) {
                $query->whereIn('roles.id', $role_ids);
            });
        }

        $totalusers = $query->count();

        $users = $query->select('users.*')
            ->distinct()
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->orderByRaw("CASE WHEN roles.name = 'admin' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN roles.name = 'admin' THEN users.id END ASC")
            ->orderBy($sort, $order)
            ->paginate(request("limit"));

        $users = $users->through(function ($user) {

            $actions = '';

                $actions .= '<a href="/users/edit/' . $user->id . '" title="' . get_label('update', 'Update') . '">' .
                    '<i class="bx bx-edit mx-1"></i>' .
                    '</a>';



                $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $user->id . '" data-type="users">' .
                    '<i class="bx bx-trash text-danger mx-1"></i>' .
                    '</button>';


            $actions = $actions ?: '-';


            $photoHtml = "<div class='avatar avatar-md pull-up' title='" . $user->first_name . " " . $user->last_name . "'>
            <a href='/users/profile/" . $user->id . "'>
                <img src='" . ($user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
            </a>
          </div>";

            $statusBadge = $user->status === 1
                ? '<span class="badge bg-success">' . get_label('active', 'Active') . '</span>'
                : '<span class="badge bg-danger">' . get_label('deactive', 'Deactive') . '</span>';

            $formattedHtml = '<div class="d-flex mt-2">' .
                $photoHtml .
                '<div class="mx-2">' .
                '<h6 class="mb-1">' .
                $user->first_name . ' ' . $user->last_name .
                ' ' . $statusBadge .
                '</h6>' .
                '<p class="text-muted">' . $user->email . '</p>' .
                '</div>' .
                '</div>';

            $phone = !empty($user->country_code) ? $user->country_code . ' ' . $user->phone : $user->phone;

            $r = 1;
        if($user->role == 1) {
           $r = 1;
            }
            $roleLabel = $r == 1 ? 'admin' : 'user';
            $roleCssClass = $r == 1 ? 'info' : 'warning';




            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'role' => "<span class='badge bg-label-" . $roleCssClass . " me-1'>role " . $roleLabel . "</span>",                'email' => $user->email,
                'phone' => $phone,
                'email' => $user->email,
                'profile' => $formattedHtml,
                'status' => $user->status,
                'created_at' => format_date($user->created_at, true),
                'updated_at' => format_date($user->updated_at, true),
                'actions' => $actions
            ];
        });

        return response()->json([
            "rows" => $users->items(),
            "total" => $totalusers,
        ]);
    }

}
