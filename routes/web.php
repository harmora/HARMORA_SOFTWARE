<?php

use App\Http\Controllers\AchatController;
use App\Models\ActivityLog;
use App\Http\Middleware\Authorize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaxesController;
use App\Http\Controllers\TodosController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdaterController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MeetingsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PayslipsController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\InstallerController;
use App\Http\Middleware\CustomRoleMiddleware;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\AllowancesController;
use App\Http\Controllers\DeductionsController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\WorkspacesController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\TimeTrackerController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\NotificationsController;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\PaymentMethodsController;
use App\Http\Controllers\EstimatesInvoicesController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DisponibiliteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommandesController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\FournisseurController;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//---------------------------------------------------------------
/*
Route::get('/update-test', function () {
    $updatePath = Config::get('constants.UPDATE_PATH');
    $sub_directory = (file_exists($updatePath . "plugin/package.json")) ? "plugin/" : "";
    $package_data = file_get_contents($updatePath . $sub_directory . "package.json");
    $package_data = json_decode($package_data, true);
});
*/

Route::get('/clear-cache', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return redirect()->back()->with('message', 'Cache cleared successfully.');
});

Route::get('/create-symlink', function () {
    if (config('constants.ALLOW_MODIFICATION') === 1) {
        $storageLinkPath = public_path('storage');
        if (is_dir($storageLinkPath)) {
            File::deleteDirectory($storageLinkPath);
        }
        Artisan::call('storage:link');
        return redirect()->back()->with('message', 'Symbolik link created successfully.');
    } else {
        return redirect()->back()->with('error', 'This operation is not allowed in demo mode.');
    }
});

Route::get('/phpinfo', function () {
    phpinfo();
})->middleware('multiguard');


Route::get('/install', [InstallerController::class, 'index'])->middleware('guest');

Route::post('/installer/config-db', [InstallerController::class, 'config_db'])->middleware('guest');

Route::post('/installer/install', [InstallerController::class, 'install'])->middleware('guest');


Route::middleware(['CheckInstallation'])->group(function () {

    Route::get('/', [UserController::class, 'login'])->name('login')->middleware('guest');

    Route::post('/users/authenticate', [UserController::class, 'authenticate']);

    // Route::get('/signup', [SignUpController::class, 'index'])->middleware(['guest', 'checkSignupEnabled']);

    // Route::post('/create-account', [SignUpController::class, 'create_account'])->middleware(['guest', 'checkSignupEnabled']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->middleware('guest');

    Route::post('/forgot-password-mail', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('guest');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->middleware('guest')->name('password.reset');

    Route::post('/reset-password', [ForgotPasswordController::class, 'ResetPassword'])->middleware('guest')->name('password.update');

    Route::get('/email/verify', [UserController::class, 'email_verification'])->name('verification.notice')->middleware(['auth:web,client']);

    Route::get('/email/verify/{id}/{hash}', [ClientController::class, 'verify_email'])->middleware(['auth:web,client', 'custom.signature'])->name('verification.verify');

    Route::get('/email/verification-notification', [UserController::class, 'resend_verification_link'])->middleware(['auth:web,client', 'throttle:6,1'])->name('verification.send');

    Route::post('/logout', [UserController::class, 'logout'])->middleware(['multiguard']);

    // ,'custom-verified'
    Route::middleware(['multiguard', 'custom-verified'])->group(function () {

        Route::get('/home', [HomeController::class, 'index']);

        // Route::get('/home/upcoming-birthdays', [HomeController::class, 'upcoming_birthdays']);

        // Route::get('/home/upcoming-work-anniversaries', [HomeController::class, 'upcoming_work_anniversaries']);

        // Route::get('/home/members-on-leave', [HomeController::class, 'members_on_leave']);

        // Route::get('/home/upcoming-birthdays-calendar', [HomeController::class, 'upcoming_birthdays_calendar']);

        // Route::get('/home/upcoming-work-anniversaries-calendar', [HomeController::class, 'upcoming_work_anniversaries_calendar']);

        // Route::get('/home/members-on-leave-calendar', [HomeController::class, 'members_on_leave_calendar']);





        //disponibility-------------------------------------------------------------
        Route::get('/disponibility', [DisponibiliteController::class, 'index']);
        Route::get('/disponibility/calendar', [DisponibiliteController::class, 'calendar']);
        Route::get('/disponibility/get/{id}', [DisponibiliteController::class, 'show']);
        Route::post('/disponibility/store', [DisponibiliteController::class, 'store']);
        Route::get('/disponibilities/list', [DisponibiliteController::class, 'list']);
        Route::delete('/disponibilities/destroy/{id}', [DisponibiliteController::class, 'destroy']);



        Route::get('/products', [ProductController::class, 'index']);
       // web.php

        Route::get('/products/movements', [ProductController::class, 'render_mv'])->name('products.movements');

        Route::get('/products/get/{id}', [ProductController::class, 'show']);
        Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/list', [ProductController::class, 'list']);
        Route::get('/mouvements/list', [ProductController::class, 'list_mv']);
        Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy']);
        Route::get('/products/create', [ProductController::class, 'create']);

        Route::get('/products/info/{id}', [ProductController::class, 'show']);




        Route::get('/documents', [DocsController::class, 'index']);
        Route::get('/documents/list', [DocsController::class, 'list']);






        //Meetings-------------------------------------------------------------
        // Route::middleware(['has_workspace', 'customcan:manage_meetings'])->group(function () {

            Route::get('/meetings', [MeetingsController::class, 'index']);

            Route::post('/meetings/store', [MeetingsController::class, 'store']);

            Route::get('/meetings/list', [MeetingsController::class, 'list']);

            Route::get('/meetings/get/{id}', [MeetingsController::class, 'get'])->middleware(['checkAccess:App\Models\Meeting,meetings,id,meetings'])->name('meeting.get');

            Route::post('/meetings/update', [MeetingsController::class, 'update'])
                ->middleware(['customcan:edit_meetings', 'log.activity']);

            Route::delete('/meetings/destroy/{id}', [MeetingsController::class, 'destroy'])
                ->middleware(['customcan:delete_meetings', 'demo_restriction', 'checkAccess:App\Models\Meeting,meetings,id,meetings', 'log.activity']);

            Route::post('/meetings/destroy_multiple', [MeetingsController::class, 'destroy_multiple'])
                ->middleware(['customcan:delete_meetings', 'demo_restriction', 'log.activity']);

            Route::get('/meetings/join/{id}', [MeetingsController::class, 'join'])
                ->middleware(['checkAccess:App\Models\Meeting,meetings,id,meetings']);

            Route::get('/meetings/duplicate/{id}', [MeetingsController::class, 'duplicate'])
                ->middleware(['customcan:create_meetings', 'checkAccess:App\Models\Meeting,meetings,id,meetings', 'log.activity']);
        // });

        //Workspaces-------------------------------------------------------------
        // Route::middleware(['customcan:manage_workspaces'])->group(function () {

        //     Route::get('/workspaces', [WorkspacesController::class, 'index']);

        //     Route::post('/workspaces/store', [WorkspacesController::class, 'store'])->middleware(['customcan:create_workspaces', 'log.activity']);

        //     Route::get('/workspaces/duplicate/{id}', [WorkspacesController::class, 'duplicate'])
        //         ->middleware(['customcan:create_workspaces', 'checkAccess:App\Models\Workspace,workspaces,id,workspaces', 'log.activity']);

        //     Route::get('/workspaces/list', [WorkspacesController::class, 'list']);

        //     Route::get('/workspaces/get/{id}', [WorkspacesController::class, 'get'])->middleware(['checkAccess:App\Models\Workspace,workspaces,id,workspaces'])->name('workspace.get');

        //     Route::post('/workspaces/update', [WorkspacesController::class, 'update'])
        //         ->middleware(['customcan:edit_workspaces', 'demo_restriction', 'log.activity']);

        //     Route::delete('/workspaces/destroy/{id}', [WorkspacesController::class, 'destroy'])
        //         ->middleware(['customcan:delete_workspaces', 'demo_restriction', 'checkAccess:App\Models\Workspace,workspaces,id,workspaces', 'log.activity']);

        //     Route::post('/workspaces/destroy_multiple', [WorkspacesController::class, 'destroy_multiple'])
        //         ->middleware(['customcan:delete_workspaces', 'demo_restriction', 'log.activity']);

        //     Route::get('/workspaces/switch/{id}', [WorkspacesController::class, 'switch'])
        //         ->middleware(['checkAccess:App\Models\Workspace,workspaces,id,workspaces']);
        // // });
        // Route::get('/workspaces/remove_participant', [WorkspacesController::class, 'remove_participant'])->middleware(['demo_restriction']);

        //codes i added
        Route::get('/commandes', [CommandesController::class, 'index']);
    
        Route::get('/commandes/information/{id}', [CommandesController::class, 'show']);

        Route::post('/commandes/store', [CommandesController::class, 'store'])->name('commandes.store');

        Route::get('/commandes/duplicate/{id}', [CommandesController::class, 'duplicate']);

        Route::get('/commandes/get/{id}', [CommandesController::class, 'get'])->name('commande.get');

        Route::post('/commandes/update', [CommandesController::class, 'update']);


        Route::post('/commandes/upload-media', [CommandesController::class, 'upload_media']);

        Route::get('/commandes/get-media/{id}', [CommandesController::class, 'get_media']);

        Route::delete('/commandes/delete-media/{id}', [CommandesController::class, 'delete_media']);


        Route::post('/commandes/delete-multiple-media', [CommandesController::class, 'delete_multiple_media']);

        Route::delete('/commandes/destroy/{id}', [CommandesController::class, 'destroy']);


        Route::post('/commandes/destroy_multiple', [CommandesController::class, 'destroy_multiple']);

        Route::get('/commandes/list/{id?}', [CommandesController::class, 'list']);

        Route::get('/commandes/draggable', [CommandesController::class, 'dragula']);
           // });
        //it ends here

        //Todos-------------------------------------------------------------
        // Route::middleware(['has_workspace'])->group(function () {

            Route::get('/todos', [TodosController::class, 'index']);

            Route::get('/todos/create', [TodosController::class, 'create']);

            Route::post('/todos/store', [TodosController::class, 'store']);

            Route::get('/todos/edit/{id}', [TodosController::class, 'edit']);

            Route::post('/todos/update', [TodosController::class, 'update'])->name('todos.update')->middleware(['log.activity']);

            Route::put('/todos/update_status', [TodosController::class, 'update_status'])->middleware(['log.activity']);

            Route::delete('/todos/destroy/{id}', [TodosController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);

            Route::get('/todos/get/{id}', [TodosController::class, 'get']);


            Route::get('/notes', [NotesController::class, 'index']);

            Route::post('/notes/store', [NotesController::class, 'store']);

            Route::post('/notes/update', [NotesController::class, 'update']);

            Route::get('/notes/get/{id}', [NotesController::class, 'get']);

            Route::delete('/notes/destroy/{id}', [NotesController::class, 'destroy'])->middleware(['demo_restriction', 'log.activity']);
        // });

        //Users-------------------------------------------------------------

        Route::get('account/{user}', [ProfileController::class, 'show'])->name('profile.show');

        Route::put('/profile/update_photo/{userOrClient}', [ProfileController::class, 'update_photo'])->middleware(['demo_restriction']);

        Route::put('profile/update/{userOrClient}', [ProfileController::class, 'update'])->name('profile.update')->middleware(['demo_restriction']);

        Route::delete('/account/destroy/{user}', [ProfileController::class, 'destroy'])->middleware(['demo_restriction']);

        // Route::middleware(['has_workspace', 'customcan:manage_users'])->group(function () {

            Route::get('/users', [UserController::class, 'index']);

            Route::get('/users/create', [UserController::class, 'create']);

            Route::post('/users/store', [UserController::class, 'store']);

            Route::get('/users/profile/{id}', [UserController::class, 'show'])->name('users.profile');

            Route::get('/users/edit/{id}', [UserController::class, 'edit']);

            Route::put('/users/update_user/{user}', [UserController::class, 'update_user']);

            Route::delete('/users/delete_user/{user}', [UserController::class, 'delete_user']);

            Route::post('/users/delete_multiple_user', [UserController::class, 'delete_multiple_user']);

            Route::get('/users/list', [UserController::class, 'list']);

                    // Entreprises-------------------------------------------------------------

                    Route::get('/entreprises', [EntrepriseController::class, 'index']);
                    Route::get('/entreprises/create', [EntrepriseController::class, 'create']);
                    Route::post('/entreprises/store', [EntrepriseController::class, 'store']);
                    Route::get('/entreprises/profile/{id}', [EntrepriseController::class, 'show']);
                    Route::get('/entreprises/edit/{id}', [EntrepriseController::class, 'edit']);
                    Route::put('/entreprises/update_entreprise/{entreprise}', [EntrepriseController::class, 'update_entreprise']);
                    Route::get('/entreprises/list', [EntrepriseController::class, 'list']);
                    Route::delete('/entreprise/destroy/{id}', [EntrepriseController::class, 'destroy']);


                    // });

        // fournisseurs-------------------------------------------------------------
            Route::get('/fournisseurs', [FournisseurController::class, 'index']);
            Route::get('fournisseurs/create', [FournisseurController::class,'create']);
            Route::post('fournisseurs/store', [FournisseurController::class,'store']);
            Route::get('fournisseurs/edit/{id}', [FournisseurController::class,'edit']);
            Route::put('fournisseurs/update/{id}', [FournisseurController::class,'update']);
            Route::get('fournisseurs/list', [FournisseurController::class,'list']);
            Route::delete('/fournisseurs/destroy/{id}', [FournisseurController::class, 'destroy']);

        // Achats-------------------------------------------------------------
        Route::get('/achats', [AchatController::class, 'index'])->name('achats.index');
        Route::get('/achats/create', [AchatController::class,'create']);
        Route::post('/achats/store', [AchatController::class, 'store'])->name('achats.store');
        // Route::post('/achats/store', [AchatController::class,'store']);
        Route::get('achats/edit/{id}', [AchatController::class,'edit']);
        Route::put('achats/update/{id}', [AchatController::class,'update']);
        Route::get('achats/list', [AchatController::class,'list']);
        Route::delete('/achats/destroy/{id}', [AchatController::class, 'destroy']);


        //Factures-------------------------------------------------------------
        Route::get('/facture', [FactureController::class, 'show'])->name('factures.show');
        Route::get('/factures/{id}/download', [FactureController::class, 'download'])->name('factures.download');

        //Clients-------------------------------------------------------------

        // Route::middleware(['has_workspace', 'customcan:manage_clients'])->group(function () {

            Route::get('/clients', [ClientController::class, 'index']);

            Route::get('/clients/profile/{id}', [ClientController::class, 'show']);

            Route::get('/clients/create', [ClientController::class, 'create']);

            Route::post('/clients/store', [ClientController::class, 'store']);

            Route::get('/clients/get/{id}', [ClientController::class, 'get']);

            Route::get('/clients/edit/{id}', [ClientController::class, 'edit']);

            Route::put('/clients/update/{id}', [ClientController::class, 'update']);

            Route::delete('/clients/destroy/{id}', [ClientController::class, 'destroy']);

            Route::post('/clients/destroy_multiple', [ClientController::class, 'destroy_multiple']);

            Route::get('/clients/list', [ClientController::class, 'list']);
        // });



        //Settings-------------------------------------------------------------
        Route::get("settings/languages/switch/{code}", [LanguageController::class, 'switch']);

        Route::put("settings/languages/set-default", [LanguageController::class, 'set_default'])->middleware(['demo_restriction']);

     //   Route::middleware(['customRole:admin'])->group(function () {

            Route::get('/settings/permission/create', [RolesController::class, 'create_permission']);

            Route::get('/settings/permission', [RolesController::class, 'index']);

            Route::delete('/roles/destroy/{id}', [RolesController::class, 'destroy'])->middleware(['demo_restriction']);

            Route::get('/roles/create', [RolesController::class, 'create']);

            Route::post('/roles/store', [RolesController::class, 'store']);

            Route::get('/roles/edit/{id}', [RolesController::class, 'edit']);

            Route::put('/roles/update/{id}', [RolesController::class, 'update']);

            Route::get('/settings/general', [SettingsController::class, 'index']);

            Route::put('/settings/store_general', [SettingsController::class, 'store_general_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/languages', [LanguageController::class, 'index']);

            Route::post('/settings/languages/store', [LanguageController::class, 'store']);

            Route::get("settings/languages/change/{code}", [LanguageController::class, 'change']);

            Route::put("/settings/languages/save_labels", [LanguageController::class, 'save_labels'])->middleware(['demo_restriction']);

            Route::get("/settings/languages/manage", [LanguageController::class, 'manage']);

            Route::get('/settings/languages/get/{id}', [LanguageController::class, 'get']);

            Route::post('/settings/languages/update', [LanguageController::class, 'update'])->middleware(['demo_restriction']);

            Route::get("/settings/languages/list", [LanguageController::class, 'list']);

            Route::delete("/settings/languages/destroy/{id}", [LanguageController::class, 'destroy'])->middleware(['demo_restriction']);

            Route::post("/settings/languages/destroy_multiple", [LanguageController::class, 'destroy_multiple'])->middleware(['demo_restriction']);

            Route::get('/settings/email', [SettingsController::class, 'email']);

            Route::put('/settings/store_email', [SettingsController::class, 'store_email_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/sms-gateway', [SettingsController::class, 'sms_gateway']);

            Route::put('/settings/store_sms_gateway', [SettingsController::class, 'store_sms_gateway_settings'])->middleware(['demo_restriction']);

            Route::put('/settings/store_whatsapp', [SettingsController::class, 'store_whatsapp_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/pusher', [SettingsController::class, 'pusher']);

            Route::put('/settings/store_pusher', [SettingsController::class, 'store_pusher_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/media-storage', [SettingsController::class, 'media_storage']);

            Route::put('/settings/store_media_storage', [SettingsController::class, 'store_media_storage_settings'])->middleware(['demo_restriction']);

            Route::get('/settings/templates', [SettingsController::class, 'templates']);

            Route::put('/settings/store_template', [SettingsController::class, 'store_template'])->middleware(['demo_restriction']);

            Route::post('/settings/get-default-template', [SettingsController::class, 'get_default_template']);

            Route::get('/settings/system-updater', [UpdaterController::class, 'index']);

            Route::post('/settings/update-system', [UpdaterController::class, 'update'])->middleware(['demo_restriction']);
        //});

        // Route::middleware(['has_workspace'])->group(function () {

            Route::get('/search', [SearchController::class, 'search']);

            Route::middleware(['admin_or_user'])->group(function () {


            Route::get('/time-tracker', [TimeTrackerController::class, 'index'])->middleware(['customcan:manage_timesheet']);
            Route::post('/time-tracker/store', [TimeTrackerController::class, 'store'])->middleware(['customcan:create_timesheet', 'log.activity']);
            Route::post('/time-tracker/update', [TimeTrackerController::class, 'update'])->middleware('log.activity');
            Route::get('/time-tracker/list', [TimeTrackerController::class, 'list'])->middleware(['customcan:manage_timesheet']);
            Route::delete('/time-tracker/destroy/{id}', [TimeTrackerController::class, 'destroy'])->middleware(['customcan:delete_timesheet', 'log.activity']);
            Route::post('/time-tracker/destroy_multiple', [TimeTrackerController::class, 'destroy_multiple'])->middleware(['customcan:delete_timesheet', 'log.activity']);



            Route::middleware(['customcan:manage_system_notifications'])->group(function () {
                Route::put('/notifications/mark-all-as-read', [NotificationsController::class, 'mark_all_as_read']);
                Route::get('/notifications', [NotificationsController::class, 'index']);
                Route::get('/notifications/list', [NotificationsController::class, 'list']);
                Route::delete('/notifications/destroy/{id}', [NotificationsController::class, 'destroy'])->middleware(['customcan:delete_system_notifications', 'demo_restriction']);
                Route::post('/notifications/destroy_multiple', [NotificationsController::class, 'destroy_multiple'])->middleware(['customcan:delete_system_notifications', 'demo_restriction']);
                Route::put('/notifications/update-status', [NotificationsController::class, 'update_status']);
                Route::get('/notifications/get-unread-notifications', [NotificationsController::class, 'getUnreadNotifications'])->middleware(['customcan:manage_system_notifications']);
            });
            Route::get('preferences', [PreferenceController::class, 'index'])->name('preferences.index');

            Route::post('/save-notification-preferences', [PreferenceController::class, 'saveNotificationPreferences'])->name('preferences.saveNotifications');

            Route::post('/save-column-visibility', [PreferenceController::class, 'saveColumnVisibility']);
        });
    // });
});


});
