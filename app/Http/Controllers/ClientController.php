<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Template;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\DeletionService;
use App\Notifications\VerifyEmail;
use Spatie\Permission\Models\Role;
use App\Models\UserClientPreference;
use App\Notifications\AccountCreation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $clients = Client::all();
        return view('clients.clients', ['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create_client');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 300);
        $internal_purpose = $request->has('internal_purpose') && $request->input('internal_purpose') == 'on' ? 1 : 0;
        $formFields = $request->validate([
            'first_name' =>  $internal_purpose ? 'required' : 'nullable',
            'last_name' => $internal_purpose ? 'required' : 'nullable',
            'denomenation'=> $internal_purpose ? 'nullable' : 'required',
            'RC' => 'nullable|numeric|digits_between:10,14',
            'ICE' => 'nullable|numeric|digits_between:3,8',
            'IF' => 'nullable|numeric|digits_between:4,6',
            'email' => ['required', 'email', 'unique:clients,email'],
            'phone' => 'nullable',
            'country_code' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'zip' => 'nullable',
            'dob' => 'nullable',
            'doj' => 'nullable'
        ],
        [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'denomenation.required' => 'La dénomination est obligatoire.',
            'RC.numeric' => 'ce champ doit être un nombre.',
            'RC.digits_between' => 'ce champ doit avoir entre 10 et 14 chiffres.',
            'ICM.numeric' => 'ce champ doit être un nombre.',
            'ICM.digits_between' => 'ce champdoit avoir entre 1 et 8 chiffres.',
            'IF.numeric' => 'ce champ doit être un nombre.',
        ]);
        // if (!$internal_purpose && $request->input('password')) {
        //     $password = $request->input('password');
        //     $formFields['password'] = bcrypt($formFields['password']);
        // }

        $formFields['internal_purpose'] =  $internal_purpose;


        if ($request->hasFile('profile')) {
            $formFields['photo'] = $request->file('profile')->store('photos', 'public');
        } else {
            $formFields['photo'] = 'photos/no-image.jpg';
        }
        // $dob = $request->input('dob');
        // $doj = $request->input('doj');

        // $formFields['dob'] = format_date($dob, false, app('php_date_format'), 'Y-m-d');
        // $formFields['doj'] = format_date($doj, false, app('php_date_format'), 'Y-m-d');




        $require_ev = isAdminOrHasAllDataAccess() && $request->has('require_ev') && $request->input('require_ev') == 0 ? 0 : 1;
        $status = !$internal_purpose && isAdminOrHasAllDataAccess() && $request->has('status') && $request->input('status') == 1 ? 1 : 0;

        if (!$internal_purpose && $require_ev == 0) {
            $formFields['email_verified_at'] = now()->tz(config('app.timezone'));
        }
        $formFields['status'] = $status;

        $client = Client::create($formFields);

        try {
            if (!$internal_purpose && $require_ev == 1) {
                $client->notify(new VerifyEmail($client));
                $client->update(['email_verification_mail_sent' => 1]);
            }else{
                $client->notify(new VerifyEmail($client));
                $client->update(['email_verification_mail_sent' => 0]);
            }
            // $workspace->clients()->attach($client->id);

            if (!$internal_purpose && isEmailConfigured()) {
                $account_creation_template = Template::where('type', 'email')
                    ->where('name', 'account_creation')
                    ->first();
                if (!$account_creation_template || ($account_creation_template->status !== 0)) {
                    $client->notify(new AccountCreation($client));
                    $client->update(['acct_create_mail_sent' => 1]);
                } else {
                    $client->update(['acct_create_mail_sent' => 0]);
                }
            } else {
                $client->update(['acct_create_mail_sent' => 0]);
            }
            Session::flash('message', 'Client created successfully.');
            return response()->json(['error' => false, 'id' => $client->id]);
        } catch (TransportExceptionInterface $e) {

            $client = Client::findOrFail($client->id);
            $client->delete();
            return response()->json(['error' => true, 'message' => 'Client couldn\'t be created, please make sure email settings are oprational.']);
        } catch (Throwable $e) {
            // Catch any other throwable, including non-Exception errors

            $client = Client::findOrFail($client->id);
            $client->delete();
            return response()->json(['error' => true, 'message' => 'Client couldn\'t be created, please make sure email settings are oprational.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $workspace = Workspace::find(session()->get('workspace_id'));
        $client = Client::findOrFail($id);

        $users =User::all();
        $clients = Client::all();
        return view('clients.client_profile', ['client' => $client, 'users' => $users, 'clients' => $clients, 'auth_user' => getAuthenticatedUser()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.update_client')->with('client', $client);
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
        ini_set('max_execution_time', 300);
        $client = Client::findOrFail($id);
        $internal_purpose = $request->has('internal_purpose') && $request->input('internal_purpose') == 'on' ? 1 : 0;
        // if ($internal_purpose && $request->has('password') && !empty($request->input('password'))) {
        //     $request->merge(['password' => NULL]);
        // }
        $rules = [
            'first_name' =>  $internal_purpose ? 'required' : 'nullable',
            'last_name' => $internal_purpose ? 'required' : 'nullable',
            'denomenation'=> $internal_purpose ? 'nullable' : 'required',
            'RC' => 'nullable|numeric|digits_between:10,14',
            'ICE' => 'nullable|numeric|digits_between:3,8',
            'IF' => 'nullable|numeric|digits_between:4,6',
            'email' => [
                'required',
                Rule::unique('clients')->ignore($id),
            ],
            'phone' => 'nullable',
            'country_code' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'zip' => 'nullable',
            'dob' => 'nullable',
            'doj' => 'nullable'
        ];
        // if(!$internal_purpose && $client->password===NULL){
        //     $rules['password'] = 'required|min:6';
        // }else{
        //     $rules['password'] = 'nullable';
        // }
        // $rules['password_confirmation'] = 'required_with:password|same:password';

        $formFields = $request->validate($rules);
        if ($request->hasFile('upload')) {
            if ($client->photo != 'photos/no-image.jpg' && $client->photo !== null)
                Storage::disk('public')->delete($client->photo);
            $formFields['photo'] = $request->file('upload')->store('photos', 'public');
        }

        $status = $internal_purpose ? $client->status : (isAdminOrHasAllDataAccess() && $request->has('status') ? $request->input('status') : $client->status);
        $formFields['status'] = $status;

        // if (!$internal_purpose && isAdminOrHasAllDataAccess() && isset($formFields['password']) && !empty($formFields['password'])) {
        //     $password = $formFields['password'];
        //     $formFields['password'] = bcrypt($formFields['password']);
        // } else {
        //     unset($formFields['password']);
        // }

        $formFields['internal_purpose'] = $internal_purpose;

        $client->update($formFields);

        $require_ev = 0;

        if (!$internal_purpose && $client->email_verified_at === null && $client->email_verification_mail_sent === 0) {
            $require_ev = isAdminOrHasAllDataAccess() && $request->has('require_ev') && $request->input('require_ev') == 0 ? 0 : 1;
        }

        $send_account_creation_email = 0;

        if (!$internal_purpose && $client->acct_create_mail_sent === 0) {
            $send_account_creation_email = 1;
        }

        try {
            if (!$internal_purpose && $require_ev == 1) {
                $client->notify(new VerifyEmail($client));
                $client->update(['email_verification_mail_sent' => 1]);
            }
            if (!$internal_purpose && $send_account_creation_email == 1 && isEmailConfigured()) {
                $account_creation_template = Template::where('type', 'email')
                    ->where('name', 'account_creation')
                    ->first();
                if (!$account_creation_template || ($account_creation_template->status !== 0)) {
                    $client->notify(new AccountCreation($client, ));
                    $client->update(['acct_create_mail_sent' => 1]);
                }
            }
        } catch (TransportExceptionInterface $e) {
            // dd($e->getMessage());
        } catch (Throwable $e) {
            // Catch any other throwable, including non-Exception errors
            // dd($e->getMessage());
        }
        Session::flash('message', 'Client details updated successfully.');
        return response()->json(['error' => false, 'id' => $client->id]);
    }


    public function get($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['client' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $response = DeletionService::delete(Client::class, $id, 'Client');
        // UserClientPreference::where('user_id', 'c_' . $id)->delete();
        return $response;
    }


    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:clients,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedClients = [];
        $deletedClientNames = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $client = Client::findOrFail($id);
            if ($client) {
                $deletedClients[] = $id;
                $deletedClientNames[] = $client->first_name . ' ' . $client->last_name;
                DeletionService::delete(Client::class, $id, 'Client');
                UserClientPreference::where('user_id', 'c_' . $id)->delete();
                $client->todos()->delete();
            }
        }
        return response()->json(['error' => false, 'message' => 'Clients(s) deleted successfully.', 'id' => $deletedClients, 'titles' => $deletedClientNames]);
    }



    public function list()
    {
        $search = request('search');
        $sort = request('sort') ?: 'id';
        $order = request('order') ?: 'DESC';
        $status = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' ? $_REQUEST['status'] : "";
        $internal_purpose = isset($_REQUEST['internal_purpose']) && $_REQUEST['internal_purpose'] !== '' ? $_REQUEST['internal_purpose'] : "";

        $clients = Client::query();

        $clients = $clients->when($search, function ($query) use ($search) {
            return $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('company', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        });

        if ($status != '') {
            $clients = $clients->where('status', $status);
        }

        if ($internal_purpose != '') {
            $clients = $clients->where('internal_purpose', $internal_purpose);
        }

        $totalclients = $clients->count();

        // $canEdit = checkPermission('edit_clients');
        // $canDelete = checkPermission('delete_clients');

        $clients = $clients->select('clients.*')
            ->distinct()
            ->orderBy($sort, $order)
            ->paginate(request('limit'))
            ->through(function ($client)  {
                $actions = '';

                    $actions .= '<a href="/clients/edit/' . $client->id . '" title="' . get_label('update', 'Update') . '">' .
                        '<i class="bx bx-edit mx-1"></i>' .
                        '</a>';



                    $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $client->id . '" data-type="clients">' .
                        '<i class="bx bx-trash text-danger mx-1"></i>' .
                        '</button>';


                $actions = $actions ?: '-';

                $badge = '';


                $badge = $client->status === 1 ? '<span class="badge bg-success">' . get_label('active', 'Active') . '</span>' : '<span class="badge bg-danger">' . get_label('deactive', 'Deactive') . '</span>';

                $profileHtml = "<div class='avatar avatar-md pull-up' title='" . $client->first_name . " " . $client->last_name . " " . $client->denomenation . "'>
                    <a href='/clients/profile/" . $client->id . "'>
                    <img src='" . ($client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                    </a>
                    </div>";//when hover the photo display infos as popup

                $formattedHtml = '<div class="d-flex mt-2">' .
                    $profileHtml .
                    '<div class="mx-2">' .
                    '<h6 class="mb-1">';
                    if ($client->internal_purpose == 1) {
                        $formattedHtml .= $client->first_name . ' ' . $client->last_name.' ' ;
                    }
                    else{
                        $formattedHtml .= $client->denomenation.' ';
                    }
                    $formattedHtml .=
                    $badge .
                    '</h6>' .
                    '<span class="text-muted">' . $client->email . '</span>';

                if ($client->internal_purpose == 1) {
                    $formattedHtml .= '<span class="badge bg-info ms-2">' . get_label('internal_purpose', 'Internal Purpose') . '</span>';
                }

                $formattedHtml .= '</div>' .
                    '</div>';



                $phone = !empty($client->country_code) ? $client->country_code . ' ' . $client->phone : $client->phone;

                return [
                    'id' => $client->id,
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                    'denomenation' => $client->denomenation,
                    'company' => $client->company,
                    'email' => $client->email,
                    'phone' => $phone,
                    'profile' => $formattedHtml,
                    'status' => $client->status,
                    'internal_purpose' => $client->internal_purpose,
                    'created_at' => format_date($client->created_at, true),
                    'updated_at' => format_date($client->updated_at, true),
                    'actions' => $actions
                ];
            });

        return response()->json([
            'rows' => $clients->items(),
            'total' => $totalclients,
        ]);
    }


    public function verify_email(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/home')->with('message', 'Email verified successfully.');
    }
}
