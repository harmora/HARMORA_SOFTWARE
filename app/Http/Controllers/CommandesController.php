<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\Priority;
use Illuminate\Http\Request;


use Illuminate\Support\Arr;
use App\Services\DeletionService;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Exception;

class CommandesController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
          //  $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    /**
     * Display a listing of the commandes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = '')
    {
        //$productId = isset($product->id) ? $product->id : (request()->has('product') ? request('product') : '');
            
        //$toSelectCommandeUsers = []; // Default empty array for safety

        $users = User::all();
       // $users = User::all();  // Fetch all users
        $clients = Client::all();  // Fetch all clients
        $products = Product::all();  // Fetch all products
        $commandes = Commande::all();
        //dd($commandes); // this will dump and die the $commandes data


        return view('commandes.commandes', compact('clients', 'users', 'products'), compact('commandes'));
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'commande_products'); // Adjust 'quantity' if you have other fields
    }


   /**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
    public function store(Request $request)
    {
        //dd(request()->all());
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|string',
            'priority' => 'nullable|integer',
            // 'product' => 'nullable|integer',
           // 'products' => 'nullable|array',
            'product_id' => 'nullable|integer|exists:products,id', // Validate product IDs
            //'user_id' => 'nullable|array',
            'user_id' => 'nullable|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'total_amount'=>'nullable|integer',
            'client_id' => 'nullable|integer|exists:clients,id', // Ensure client_id is present and valid
        ]);

        // Create a new commande
        $commande = Commande::create([
            
            'client_id' => $request->client_id, // Ensure client_id is provided
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'total_amount' => 0, // Placeholder for total amount logic
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $request->user_id, 
            'product_id' => $request->product_id
        ]);

        // if ($request->has('product')) {
        //     $commande->products()->attach($request->product); // You can also add extra fields like quantity if needed
        // }
       // $commande->products()->attach($request->products);

        //$userIds = (array) $request->input('user_id');


        return response()->json(['error' => false,'message' => 'Commande created successfully.']);
        //return redirect()->route('commandes.commande_informations')->with('success', 'Commande created successfully!');
    }





    /**
     * Display the specified commande.
     *
     * @param  \App\Models\Commande  $commande
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commande = Commande::findOrFail($id);
        \Log::info('Commande data:', ['commande' => $commande]);
        return view('commandes.commande_information', ['commande' => $commande, 'auth_user' => $this->user]);
    }


    public function get($id)
    {
        $commande = Commande::with('users')->findOrFail($id);
        $product = $commande->product()->with('users')->firstOrFail();
        \Log::info('Commande and Product data:', ['commande' => $commande, 'product' => $product]);

        return response()->json(['error' => false, 'commande' => $commande, 'product' => $product]);
    }


    /**
     * Update the specified commande in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commande  $commande
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => 'required|exists:commandes,id',
            'title' => ['required'],
            'status_id' => ['required'],
            'priority_id' => ['nullable'],
            'start_date' => ['required', 'before_or_equal:due_date'],
            'due_date' => ['required'],
            'description' => ['nullable']
        ], [
            'status_id.required' => 'The status field is required.'
        ]);

        $status = Status::findOrFail($request->input('status_id'));
        $id = $request->input('id');
        $commande = Commande::findOrFail($id);
        $currentStatusId = $commande->status_id;

        // Check if the status has changed
        if ($currentStatusId != $request->input('status_id')) {
            $status = Status::findOrFail($request->input('status_id'));
            if (!canSetStatus($status)) {
                return response()->json(['error' => true, 'message' => 'You are not authorized to set this status.']);
            }
        }
        $start_date = $request->input('start_date');
        $due_date = $request->input('due_date');
        $formFields['start_date'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d');
        $formFields['due_date'] = format_date($due_date, false, app('php_date_format'), 'Y-m-d');

        $userIds = $request->input('user_id', []);

        $commande = Commande::findOrFail($id);
        $commande->update($formFields);

        // Get the current users associated with the commande
        $currentUsers = $commande->users->pluck('id')->toArray();
        $currentClients = $commande->product->clients->pluck('id')->toArray();

        // Sync the users for the commande
        $commande->users()->sync($userIds);

        // Get the new users associated with the commande
        $newUsers = array_diff($userIds, $currentUsers);

        // Prepare notification data for new users
        $notification_data = [
            'type' => 'commande',
            'type_id' => $id,
            'type_title' => $commande->title,
            'access_url' => 'commandes/information/' . $commande->id,
            'action' => 'assigned'
        ];

        // Notify only the new users
        $recipients = array_map(function ($userId) {
            return 'u_' . $userId;
        }, $newUsers);

        // Process notifications for new users
        processNotifications($notification_data, $recipients);

        if ($currentStatusId != $request->input('status_id')) {
            $currentStatus = Status::findOrFail($currentStatusId);
            $newStatus = Status::findOrFail($request->input('status_id'));

            $notification_data = [
                'type' => 'commande_status_updation',
                'type_id' => $id,
                'type_title' => $commande->title,
                'updater_first_name' => $this->user->first_name,
                'updater_last_name' => $this->user->last_name,
                'old_status' => $currentStatus->title,
                'new_status' => $newStatus->title,
                'access_url' => 'commandes/information/' . $id,
                'action' => 'status_updated'
            ];

            $currentRecipients = array_merge(
                array_map(function ($userId) {
                    return 'u_' . $userId;
                }, $currentUsers),
                array_map(function ($clientId) {
                    return 'c_' . $clientId;
                }, $currentClients)
            );
            processNotifications($notification_data, $currentRecipients);
        }
        return response()->json(['error' => false, 'id' => $id, 'parent_id' => $commande->product->id, 'parent_type' => 'product',  'message' => 'Commande updated successfully.']);
    }

        
    /**
     * Remove the specified commande from storage.
     *
     * @param  \App\Models\Commande  $commande
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commande = Commande::find($id);
        DeletionService::delete(Commande::class, $id, 'Commande');
        return response()->json(['error' => false, 'message' => 'Commande deleted successfully.', 'id' => $id, 'title' => $commande->title, 'parent_id' => $commande->product_id, 'parent_type' => 'product']);
    }


    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:commandes,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedCommandes = [];
        $deletedCommandeTitles = [];
        $parentIds = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $commande = Commande::find($id);
            if ($commande) {
                $deletedCommandeTitles[] = $commande->title;
                DeletionService::delete(Commande::class, $id, 'Commande');
                $deletedCommandes[] = $id;
                $parentIds[] = $commande->product_id;
            }
        }

        return response()->json(['error' => false, 'message' => 'Commande(s) deleted successfully.', 'id' => $deletedCommandes, 'titles' => $deletedCommandeTitles, 'parent_id' => $parentIds, 'parent_type' => 'product']);
    }


    public function list()
    {
        // Fetch all commandes with their associated user and client data
        $commandes = Commande::with(['user', 'client'])->get();
    
        // Format commandes data
        $formattedCommandes = $commandes->map(function ($commande) {
            return [
                'id' => $commande->id,
                'title' => $commande->title,
                'users' => $commande->user ? $commande->user->name : 'Not Assigned',
                'clients' => $commande->client ? $commande->client->name : 'Not Assigned',
                'start_date' => $commande->start_date,
                'end_date' => $commande->due_date,
                'created_at' => $commande->created_at,
                'updated_at' => $commande->updated_at,
            ];
        });
    
        // Return JSON response
        return response()->json([
            "rows" => $formattedCommandes->all(),
            "total" => $formattedCommandes->count()
        ]);
    }
    
    
    
    
    
    


    public function dragula($id = '')
    {

        $users = User::all();
        $clients = Client::all();  // Fetch all clients
        $commandes = Commande::all();
        $products= Product::all();
        $commandesByStatus = $commandes->groupBy('status');



        $statuses = ['pending', 'completed', 'cancelled'];


        $total_commandes = $commandes->count();
         return view('commandes.board_view', 
          compact('commandes', 'products', 'users', 'clients') ,
        [
            'commandesByStatus' => $commandesByStatus,
            'clients' => $clients, 
            'users' => $users,
            'total_commandes' => $total_commandes, 
         ]);
    }

    // public function updateStatus($id, $newStatus)
    // {
    //     $status = Status::findOrFail($newStatus);
    //     if (canSetStatus($status)) {
    //         $commande = Commande::findOrFail($id);
    //         $current_status = $commande->status->title;
    //         $commande->status_id = $newStatus;
    //         if ($commande->save()) {
    //             $commande->refresh();
    //             $new_status = $commande->status->title;

    //             $notification_data = [
    //                 'type' => 'commande_status_updation',
    //                 'type_id' => $id,
    //                 'type_title' => $commande->title,
    //                 'updater_first_name' => $this->user->first_name,
    //                 'updater_last_name' => $this->user->last_name,
    //                 'old_status' => $current_status,
    //                 'new_status' => $new_status,
    //                 'access_url' => 'commandes/information/' . $id,
    //                 'action' => 'status_updated'
    //             ];
    //             $userIds = $commande->users->pluck('id')->toArray();
    //             $clientIds = $commande->product->clients->pluck('id')->toArray();
    //             $recipients = array_merge(
    //                 array_map(function ($userId) {
    //                     return 'u_' . $userId;
    //                 }, $userIds),
    //                 array_map(function ($clientId) {
    //                     return 'c_' . $clientId;
    //                 }, $clientIds)
    //             );
    //             processNotifications($notification_data, $recipients);

    //             return response()->json(['error' => false, 'message' => 'Commande status updated successfully.', 'id' => $id, 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' updated commande status from ' . $current_status . ' to ' . $new_status]);
    //         } else {
    //             return response()->json(['error' => true, 'message' => 'Commande status couldn\'t updated.']);
    //         }
    //     } else {
    //         return response()->json(['error' => true, 'message' => 'You are not authorized to set this status.']);
    //     }
    // }
    // //For status change from dropdown
    // public function update_status(Request $request)
    // {
    //     $request->validate([
    //         'id' => ['required'],
    //         'statusId' => ['required']

    //     ]);
    //     $id = $request->id;
    //     $statusId = $request->statusId;
    //     $status = Status::findOrFail($statusId);
    //     if (canSetStatus($status)) {
    //         $commande = Commande::findOrFail($id);
    //         $currentStatus = $commande->status->title;
    //         $commande->status_id = $statusId;
    //         $commande->note = $request->note;
    //         if ($commande->save()) {
    //             $commande = $commande->fresh();
    //             $newStatus = $commande->status->title;

    //             $notification_data = [
    //                 'type' => 'commande_status_updation',
    //                 'type_id' => $id,
    //                 'type_title' => $commande->title,
    //                 'updater_first_name' => $this->user->first_name,
    //                 'updater_last_name' => $this->user->last_name,
    //                 'old_status' => $currentStatus,
    //                 'new_status' => $newStatus,
    //                 'access_url' => 'commandes/information/' . $id,
    //                 'action' => 'status_updated'
    //             ];
    //             $userIds = $commande->users->pluck('id')->toArray();
    //             $clientIds = $commande->product->clients->pluck('id')->toArray();
    //             $recipients = array_merge(
    //                 array_map(function ($userId) {
    //                     return 'u_' . $userId;
    //                 }, $userIds),
    //                 array_map(function ($clientId) {
    //                     return 'c_' . $clientId;
    //                 }, $clientIds)
    //             );
    //             processNotifications($notification_data, $recipients);


    //             return response()->json(['error' => false, 'message' => 'Status updated successfully.', 'id' => $id, 'type' => 'commande', 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' updated commande status from ' . $currentStatus . ' to ' . $newStatus]);
    //         } else {
    //             return response()->json(['error' => true, 'message' => 'Status couldn\'t updated.']);
    //         }
    //     } else {
    //         return response()->json(['error' => true, 'message' => 'You are not authorized to set this status.']);
    //     }
    // }
    public function updateStatus($id, $status)
    {
        $commande = Commande::findOrFail($id);
        $commande->status_id = $status; // Ensure you're updating the correct field
        $commande->save();

        return response()->json(['error' => false, 'message' => 'Status updated successfully.']);
    }


    public function duplicate($id)
    {
        // Define the related tables for this meeting
        $relatedTables = ['users']; // Include related tables as needed

        // Use the general duplicateRecord function
        $title = (request()->has('title') && !empty(trim(request()->title))) ? request()->title : '';
        $duplicate = duplicateRecord(Commande::class, $id, $relatedTables, $title);

        if (!$duplicate) {
            return response()->json(['error' => true, 'message' => 'Commande duplication failed.']);
        }
        if (request()->has('reload') && request()->input('reload') === 'true') {
            Session::flash('message', 'Commande duplicated successfully.');
        }
        return response()->json(['error' => false, 'message' => 'Commande duplicated successfully.', 'id' => $id, 'parent_id' => $duplicate->product->id, 'parent_type' => 'product']);
    }

    public function upload_media(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'integer|exists:commandes,id'
            ]);

            $mediaIds = [];

            if ($request->hasFile('media_files')) {
                $commande = Commande::find($validatedData['id']);
                $mediaFiles = $request->file('media_files');

                foreach ($mediaFiles as $mediaFile) {
                    $mediaItem = $commande->addMedia($mediaFile)
                        ->sanitizingFileName(function ($fileName) use ($commande) {
                            // Replace special characters and spaces with hyphens
                            $sanitizedFileName = strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));

                            // Generate a unique identifier based on timestamp and random component
                            $uniqueId = time() . '_' . mt_rand(1000, 9999);

                            $extension = pathinfo($sanitizedFileName, PATHINFO_EXTENSION);
                            $baseName = pathinfo($sanitizedFileName, PATHINFO_FILENAME);

                            return "{$baseName}-{$uniqueId}.{$extension}";
                        })
                        ->toMediaCollection('commande-media');

                    $mediaIds[] = $mediaItem->id;
                }
                return response()->json(['error' => false, 'message' => 'File(s) uploaded successfully.', 'id' => $mediaIds, 'type' => 'media', 'parent_type' => 'commande', 'parent_id' => $commande->id]);
            } else {
                return response()->json(['error' => true, 'message' => 'No file(s) chosen.']);
            }
        } catch (Exception $e) {
            // Handle the exception as needed
            return response()->json(['error' => true, 'message' => 'An error occurred during file upload: ' . $e->getMessage()]);
        }
    }


    public function get_media($id)
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $commande = Commande::findOrFail($id);
        $media = $commande->getMedia('commande-media');

        if ($search) {
            $media = $media->filter(function ($mediaItem) use ($search) {
                return (
                    // Check if ID contains the search query
                    stripos($mediaItem->id, $search) !== false ||
                    // Check if file name contains the search query
                    stripos($mediaItem->file_name, $search) !== false ||
                    // Check if date created contains the search query
                    stripos($mediaItem->created_at->format('Y-m-d'), $search) !== false
                );
            });
        }


        $formattedMedia = $media->map(function ($mediaItem) {
            // Check if the disk is public
            $isPublicDisk = $mediaItem->disk == 'public' ? 1 : 0;

            // Generate file URL based on disk visibility
            $fileUrl = $isPublicDisk
                ? asset('storage/commande-media/' . $mediaItem->file_name)
                : $mediaItem->getFullUrl();


            $fileExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);

            // Check if file extension corresponds to an image type
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            $isImage = in_array(strtolower($fileExtension), $imageExtensions);

            if ($isImage) {
                $html = '<a href="' . $fileUrl . '" data-lightbox="commande-media">';
                $html .= '<img src="' . $fileUrl . '" alt="' . $mediaItem->file_name . '" width="50">';
                $html .= '</a>';
            } else {
                $html = '<a href="' . $fileUrl . '" title=' . get_label('download', 'Download') . '>' . $mediaItem->file_name . '</a>';
            }

            return [
                'id' => $mediaItem->id,
                'file' => $html,
                'file_name' => $mediaItem->file_name,
                'file_size' => formatSize($mediaItem->size),
                'created_at' => format_date($mediaItem->created_at, true),
                'updated_at' => format_date($mediaItem->updated_at, true),
                'actions' => [
                    '<a href="' . $fileUrl . '" title="' . get_label('download', 'Download') . '" download>' .
                        '<i class="bx bx-download bx-sm"></i>' .
                        '</a>' .
                        '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $mediaItem->id . '" data-type="commande-media">' .
                        '<i class="bx bx-trash text-danger"></i>' .
                        '</button>'
                ],


            ];
        });

        if ($order == 'asc') {
            $formattedMedia = $formattedMedia->sortBy($sort);
        } else {
            $formattedMedia = $formattedMedia->sortByDesc($sort);
        }

        return response()->json([
            'rows' => $formattedMedia->values()->toArray(),
            'total' => $formattedMedia->count(),
        ]);
    }

    public function delete_media($mediaId)
    {
        $mediaItem = Media::find($mediaId);

        if (!$mediaItem) {
            // Handle case where media item is not found
            return response()->json(['error' => true, 'message' => 'File not found.']);
        }

        // Delete media item from the database and disk
        $mediaItem->delete();

        return response()->json(['error' => false, 'message' => 'File deleted successfully.', 'id' => $mediaId, 'title' => $mediaItem->file_name, 'parent_id' => $mediaItem->model_id,  'type' => 'media', 'parent_type' => 'commande']);
    }

    public function delete_multiple_media(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:media,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        $parentIds = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $media = Media::find($id);
            if ($media) {
                $deletedIds[] = $id;
                $deletedTitles[] = $media->file_name;
                $parentIds[] = $media->model_id;
                $media->delete();
            }
        }

        return response()->json(['error' => false, 'message' => 'Files(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles, 'parent_id' => $parentIds, 'type' => 'media', 'parent_type' => 'commande']);
    }

    public function update_priority(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'priorityId' => ['nullable']

        ]);
        $id = $request->id;
        $priorityId = $request->priorityId;
        $commande = Commande::findOrFail($id);
        $currentPriority = $commande->priority ? $commande->priority->title : 'Default';
        $commande->priority_id = $priorityId;
        $commande->note = $request->note;
        if ($commande->save()) {
            // Reload the commande to get updated priority information
            $commande = $commande->fresh();
            $newPriority = $commande->priority ? $commande->priority->title : 'Default';
            $message = $this->user->first_name . ' ' . $this->user->last_name . ' updated commande priority from ' . $currentPriority . ' to ' . $newPriority;
            return response()->json(['error' => false, 'message' => 'Priority updated successfully.', 'id' => $id, 'type' => 'commande', 'activity_message' => $message]);
        } else {
            return response()->json(['error' => true, 'message' => 'Priority couldn\'t updated.']);
        }
    }

    public function saveViewPreference(Request $request)
    {
        $view = $request->input('view');
        $prefix = isClient() ? 'c_' : 'u_';
        UserClientPreference::updateOrCreate(
            ['user_id' => $prefix . $this->user->id, 'table_name' => 'commandes'],
            ['default_view' => $view]
        );
        return response()->json(['error' => false, 'message' => 'Default View Set Successfully.']);
    }
}

?>