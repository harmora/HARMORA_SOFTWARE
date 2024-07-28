<?php

namespace App\Http\Controllers\Auth;

use App\Models\Client;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use App\Notifications\VerifyEmail;
use App\Models\Template;
use App\Notifications\AccountCreation;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class SignUpController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function index()
    {
        $roles = Role::where('guard_name', 'web')->where('name', '!=', 'admin')->get();
        return view('auth.signup', ['roles' => $roles]);
    }

    public function create_account(Request $request)
    {
        ini_set('max_execution_time', 300);
        $isTeamMember = $request->input('type') === 'member';
        $rules = [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required', 'min:6'],
            'password_confirmation' => ['required', 'same:password'],
            'company' => 'nullable',
        ];
        if ($isTeamMember) {
            $rules['role'] = 'required';
        }
        if ($isTeamMember) {
            $rules['email'] = ['required', 'email', 'unique:users,email'];
        } else {
            $rules['email'] = ['required', 'email', 'unique:clients,email'];
        }
        $formFields = $request->validate($rules);

        $primaryWorkspaceId = hasPrimaryWorkspace();
        if(!$primaryWorkspaceId){
            return response()->json(['error' => true, 'message' => 'Primary workspace is not set, which is required for signup. Please contact the admin for assistance.']);
        }else{
            $workspace = Workspace::find($primaryWorkspaceId);
        }

        $password = $request->input('password');
        $formFields['password'] = bcrypt($password);
        $formFields['photo'] = 'photos/no-image.jpg';

        $status = 0;
        $formFields['status'] = $status;
        $user = $isTeamMember ? User::create($formFields) : Client::create($formFields);
        if ($isTeamMember) {
            $user->assignRole($request->input('role'));
        }else{
            $role_id = Role::where('guard_name', 'client')->first()->id;
            $user->assignRole($role_id);
        }
        try {
            $user->notify(new VerifyEmail($user));
            
            $isTeamMember ? $workspace->users()->attach($user->id) : $workspace->clients()->attach($user->id);
            if(!$isTeamMember){
                $user->update(['email_verification_mail_sent' => 1]);
            }

            if (isEmailConfigured()) {
                $account_creation_template = Template::where('type', 'email')
                    ->where('name', 'account_creation')
                    ->first();
                if (!$account_creation_template || ($account_creation_template->status !== 0)) {
                    $user->notify(new AccountCreation($user, $password));
                    $user->update(['acct_create_mail_sent' => 1]);
                }
            }
            Session::flash('message', 'Account created successfully.');
            return response()->json(['error' => false]);
        } catch (TransportExceptionInterface $e) {

            $user = $isTeamMember ? User::findOrFail($user->id) : Client::findOrFail($user->id);
            $user->delete();
            return response()->json(['error' => true, 'message' => 'Account couldn\'t be created. An error occurred while sending the verification email. Please contact the admin for assistance.']);
        } catch (Throwable $e) {
            // dd($e->getMessage());
            // Catch any other throwable, including non-Exception errors

            $user = $isTeamMember ? User::findOrFail($user->id) : Client::findOrFail($user->id);
            $user->delete();
            return response()->json(['error' => true, 'message' => 'Account couldn\'t be created, please contact the admin for assistance.']);
        }
    }
}
