<?php

use App\Http\Controllers\AchatController;
use App\Http\Controllers\ImportController;
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
use App\Http\Controllers\PackController;
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

        Route::get('/chiffre-affaires', [HomeController::class, 'getChiffreAffaires']);
        Route::get('/get-chiffre-affaire', [HomeController::class, 'getChiffreAffaireParCategorie']);



        Route::get('/ocr', function () {
            return view('ocr\ocr');
        });


        //packs------------------------------------------------------------------

// packs-------------------------------------------------------------
Route::get('/packs', [PackController::class, 'index']); // Display a list of all packs
Route::get('/packs/calendar', [PackController::class, 'calendar']); // Display a calendar view related to packs
Route::get('/packs/get/{id}', [PackController::class, 'show']); // Display details for a specific pack
Route::post('/packs/store', [PackController::class, 'store']); // Handle the creation of a new pack
Route::get('/packs/list', [PackController::class, 'list']); // Display a detailed list of packs
Route::delete('/packs/destroy/{id}', [PackController::class, 'destroy']); // Delete a specific pack
Route::get('/packs/edit/{id}', [PackController::class, 'edit']); // Display the edit form for a specific pack
Route::put('/packs/update/{id}', [PackController::class, 'update']); // Update a specific pack


        //disponibility-------------------------------------------------------------
        Route::get('/disponibility', [DisponibiliteController::class, 'index']);
        Route::get('/disponibility/get/{id}', [DisponibiliteController::class, 'show']);
        Route::post('/disponibility/store', [DisponibiliteController::class, 'store']);
        Route::get('/disponibilities/list', [DisponibiliteController::class, 'list']);
        Route::delete('/disponibilities/destroy/{id}', [DisponibiliteController::class, 'destroy']);
        Route::get('/disponibility/edit/{id}', [DisponibiliteController::class, 'edit']);
        Route::put('/disponibility/update/{id}', [DisponibiliteController::class, 'update']);
        Route::get('/disponibility/calendar', [DisponibiliteController::class, 'calendar']);




        //products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/movements', [ProductController::class, 'render_mv'])->name('products.movements');
        Route::get('/products/get/{id}', [ProductController::class, 'show']);
        Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/list', [ProductController::class, 'list']);
        Route::get('/mouvements/list', [ProductController::class, 'list_mv']);
        Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy']);
        Route::get('/products/create', [ProductController::class, 'create']);
        Route::get('/products/edit/{id}', [ProductController::class, 'edit']);
        Route::put('/products/update/{id}', [ProductController::class, 'update']);
        Route::get('/products/info/{id}', [ProductController::class, 'show']);



        //documents
        Route::get('/documents', [DocsController::class, 'index']);
        Route::get('/documents/list', [DocsController::class, 'list']);
        Route::get('/documents/edit/{id}', [DocsController::class, 'edit']);
        Route::put('/documents/update/{id}', [DocsController::class, 'update']);
        Route::get('/documents/facture', [DocsController::class, 'getfactureinfo']);
        Route::get('/documents/facturepdf', [DocsController::class, 'getDownloadFile']);
        Route::get('/documents/download-zip/{id}', [DocsController::class, 'downloadZip'])->name('documents.downloadZip');


        //meetings
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





        // commandes
        Route::get('/commandes', [CommandesController::class, 'index']);
        Route::get('/commandes/information/{id}', [CommandesController::class, 'show']);
        Route::get('commandes/create', [CommandesController::class, 'create'])->name('commandes.create');
        Route::post('/commandes/store', [CommandesController::class, 'store'])->name('commandes.store');
        Route::get('/commandes/duplicate/{id}', [CommandesController::class, 'duplicate']);
        Route::get('/commandes/get/{id}', [CommandesController::class, 'get'])->name('commande.get');
        // Route::post('/commandes/update', [CommandesController::class, 'update']);
        Route::post('/commandes/upload-media', [CommandesController::class, 'upload_media']);
        Route::get('/commandes/get-media/{id}', [CommandesController::class, 'get_media']);
        // Route::delete('/commandes/delete-media/{id}', [CommandesController::class, 'delete_media']);
        // Route::post('/commandes/delete-multiple-media', [CommandesController::class, 'delete_multiple_media']);
        Route::delete('/commandes/destroy/{id}', [CommandesController::class, 'destroy']);
        Route::post('/commandes/destroy_multiple', [CommandesController::class, 'destroy_multiple']);
        Route::get('/commandes/list/{id?}', [CommandesController::class, 'list']);

        Route::get('/commandes/draggable', [CommandesController::class, 'dragula']);

        // Route::get('/commandes/{id}/edit', [CommandeController::class, 'edit']);
        Route::get('/commandes/{id}/edit', [CommandesController::class, 'edit'])->name('commandes.edit');
        Route::delete('/commandes/{id}', [CommandesController::class, 'destroy'])->name('commandes.destroy');
        Route::put('/commandes/{id}', [CommandesController::class, 'update'])->name('commandes.update');


        Route::get('/commandes/counter', [CommandesController::class, 'listForCounter'])->name('commandes.counter');
        Route::post('commandes/updatestatus/{id}', [CommandesController::class, 'updateStatus']);


        Route::get('commandes/updatestatus/{id}', [CommandesController::class, 'generateDevis'])->name('devis.pdf');





        Route::get('/commandes/getforaffiche/{id}', [CommandesController::class, 'getCommande']);




        //Todos-------------------------------------------------------------
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


        //Users-------------------------------------------------------------
        Route::get('account/{user}', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile/update_photo/{userOrClient}', [ProfileController::class, 'update_photo'])->middleware(['demo_restriction']);
        Route::put('profile/update/{userOrClient}', [ProfileController::class, 'update'])->name('profile.update')->middleware(['demo_restriction']);
        Route::delete('/account/destroy/{user}', [ProfileController::class, 'destroy'])->middleware(['demo_restriction']);

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
                Route::group(['middleware' => ['auth', 'role:admin']], function () {

        Route::get('/entreprises', [EntrepriseController::class, 'index']);
        Route::get('/entreprises/create', [EntrepriseController::class, 'create']);
        Route::post('/entreprises/store', [EntrepriseController::class, 'store']);
        Route::get('/entreprises/profile/{id}', [EntrepriseController::class, 'show']);
        Route::get('/entreprises/edit/{id}', [EntrepriseController::class, 'edit']);
        Route::put('/entreprises/update_entreprise/{entreprise}', [EntrepriseController::class, 'update_entreprise']);
        Route::get('/entreprises/list', [EntrepriseController::class, 'list']);
        Route::delete('/entreprise/destroy/{id}', [EntrepriseController::class, 'destroy']);

                  });


        // fournisseurs-------------------------------------------------------------
        Route::get('/fournisseurs', [FournisseurController::class, 'index'])->name('fournisseurs.index');
        Route::get('fournisseurs/create', [FournisseurController::class,'create']);
        Route::post('fournisseurs/store', [FournisseurController::class,'store'])->name('fournisseurs.store');
        Route::get('fournisseurs/edit/{id}', [FournisseurController::class,'edit']);
        Route::put('fournisseurs/update/{id}', [FournisseurController::class,'update']);
        Route::get('fournisseurs/list', [FournisseurController::class,'list']);
        Route::delete('/fournisseurs/destroy/{id}', [FournisseurController::class, 'destroy']);
        Route::get('/import', [ImportController::class, 'showForm'])->name('import.form')->middleware('cleanup.temp');
        Route::post('/import/step1', [ImportController::class, 'step1'])->name('import.step1')->middleware('cleanup.temp');
        Route::post('/import/step2', [ImportController::class, 'step2'])->name('import.step2')->middleware('cleanup.temp');
        Route::post('/import/save', [ImportController::class, 'save'])->name('import.save')->middleware('cleanup.temp');
        Route::get('/achats/{id}', [AchatController::class, 'show']);

        // //test excrl -------------------------------------------------------------------------------
        // Route::post('/import/step1', [ImportController::class, 'step1'])->name('import.step1');
        // Route::post('/import/step2', [ImportController::class, 'step2'])->name('import.step2');
        // Route::post('/import/save', [ImportController::class, 'save'])->name('import.save');

        // Achats-------------------------------------------------------------
        Route::get('/achats', [AchatController::class, 'index'])->name('achats.index');
        Route::get('/achats/create', [AchatController::class,'create']);
        Route::post('/achats/store', [AchatController::class, 'store'])->name('achats.store');
        // Route::post('/achats/store', [AchatController::class,'store']);
        Route::get('achats/edit/{id}', [AchatController::class,'edit'])->name('achats.edit');
        Route::put('achats/update/{id}', [AchatController::class,'update'])->name('achats.update');
        Route::get('achats/list', [AchatController::class,'list']);
        Route::delete('/achats/destroy/{id}', [AchatController::class, 'destroy']);


        //Factures-------------------------------------------------------------
        Route::get('/factures', [FactureController::class, 'index'])->name('factures.show');
        Route::get('/factures/create', [FactureController::class, 'create'])->name('factures.create_factures');
        Route::post('/factures/store', [FactureController::class, 'store'])->name('factures.store');
        // Route::get('/factures/{id}/edit', [FactureController::class, 'edit'])->name('factures.edit_facture');
        // Route::put('/factures/update/{id}', [FactureController::class, 'update'])->name('factures.update');
        Route::delete('/factures/{id}', [FactureController::class, 'destroy'])->name('factures.destroy');
        Route::resource('commandes', CommandesController::class);






        //Clients-------------------------------------------------------------
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/profile/{id}', [ClientController::class, 'show']);
        Route::get('/clients/create', [ClientController::class, 'create']);
        Route::post('/clients/store', [ClientController::class, 'store']);
        Route::get('/clients/get/{id}', [ClientController::class, 'get']);
        Route::get('/clients/edit/{id}', [ClientController::class, 'edit']);
        Route::put('/clients/update/{id}', [ClientController::class, 'update']);
        Route::delete('/clients/destroy/{id}', [ClientController::class, 'destroy']);
        Route::post('/clients/destroy_multiple', [ClientController::class, 'destroy_multiple']);
        Route::get('/clients/list', [ClientController::class, 'list']);




        //Settings-------------------------------------------------------------
            Route::get("settings/languages/switch/{code}", [LanguageController::class, 'switch']);

            Route::put("settings/languages/set-default", [LanguageController::class, 'set_default'])->middleware(['demo_restriction']);

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

});


});
