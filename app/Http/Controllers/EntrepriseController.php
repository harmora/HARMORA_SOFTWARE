<?php

namespace App\Http\Controllers;

use App\Models\Forme_juridique;
use Throwable;
use App\Models\Task;
// use App\Models\entreprise;
use App\Models\Client;
use App\Models\Entreprise;
use App\Models\Project;
use App\Models\Template;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\DeletionService;
use App\Notifications\VerifyEmail;
use Spatie\Permission\Models\Role;
// use App\Models\entrepriseClientPreference;
use App\Notifications\AccountCreation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Carbon\Carbon;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $entreprises = Entreprise::all();
        $formesjuridique= Forme_juridique::all();
        $visibleColumns = getUserPreferences('entreprises'); // Adjust this based on how you get user preferences
        return view('entreprises.entreprises', ['entreprises' => $entreprises,'fomesJuridique'=> $formesjuridique],compact('visibleColumns'));
    }
    // }   return view('disponibility.disponibility',['disponibilities'=>$disponibilities],compact('visibleColumns'));


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function create()
     {
         $formesJuridique = Forme_juridique::all(); // Fetch all formes juridiques
         return view('entreprises.create_entreprise', ['formesJuridique' => $formesJuridique]);
     }
     public function store(Request $request)
     {
         $formFields = $request->validate([
            'denomenation_u' => 'nullable',
            'forme_juridique_id' => 'nullable',
            'RC' => 'nullable|numeric|digits_between:2,6',
            'ICE' => 'nullable|numeric|digits_between:2,6',
            'IF' => 'nullable|numeric|digits_between:2,6',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
         ]);

         if ($request->hasFile('photo')) {
            $formFields['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            $formFields['photo'] = 'photos/no-image.jpg';
        }

         $entreprise = Entreprise::create([
             'denomination' => $formFields['denomenation_u'],
             'ICE' => $formFields['ICE'],
             'RC' => $formFields['RC'],
             'IF' => $formFields['IF'],
             'address' => $formFields['address'],
             'city' => $formFields['city'],
             'state' => $formFields['state'],
             'country' => $formFields['country'],
             'forme_juridique_id' => $formFields['forme_juridique_id'],
             'photo' => $formFields['photo'],

         ]);

         Session::flash('message', 'Entreprise created successfully.');
         return response()->json(['error' => false, 'id' => $entreprise->id]);
     }

     public function edit($id)
     {
         $entreprise =Entreprise::findOrFail($id);
         $formesJuridique = Forme_juridique::all(); // Assuming you have a FormeJuridique model

         // Debugging lines
         // dd($user, $entreprise, $formesJuridique);

         return view('entreprises.update_entreprise', [
             'entreprise' => $entreprise,
             'formesJuridique' => $formesJuridique
         ]);
     }

     public function update_entreprise(Request $request, $id)
    {
        $formFields = $request->validate([
            'denomenation_u' => 'nullable',
            'forme_juridique_id'=> 'nullable',
            'RC' => 'nullable|numeric|digits_between:2,6',
            'ICE' => 'nullable|numeric|digits_between:2,6',
            'IF' => 'nullable|numeric|digits_between:2,6',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);
        $entreprise = Entreprise::findOrFail($id);

        if ($request->hasFile('upload')) {
            // Check if the enterprise already has a photo that is not the default image
            if ($entreprise->photo != 'photos/no-image.jpg' && $entreprise->photo !== null) {
                // Delete the existing photo from storage
                Storage::disk('public')->delete($entreprise->photo);
            }

            // Store the new photo and update the 'photo' field
            $formFields['photo'] = $request->file('upload')->store('photos', 'public');
        } else {
            // If no new photo is uploaded, keep the old one
            $formFields['photo'] = $entreprise->photo;
        }



        $entreprise->update([
            'denomination' => $formFields['denomenation_u'],
            'ICE' => $formFields['ICE'],
            'RC' => $formFields['RC'],
            'IF' => $formFields['IF'],
            'address' => $formFields['address'],
            'city' => $formFields['city'],
            'state' => $formFields['state'],
            'country' => $formFields['country'],
            'photo' => $formFields['photo'],
            'forme_juridique_id' => $formFields['forme_juridique_id'], // Update this field
        ]);

        Session::flash('message', 'Profile details updated successfully.');
        return response()->json(['error' => false, 'id' => $entreprise->id]);
    }


    public function destroy($id)
    {
        $response = DeletionService::delete(Entreprise::class, $id, 'entreprise');
        // UserClientPreference::where('user_id', 'u_' . $id)->delete();

        return $response;
    }
     public function list()
     {
         $search = request('search');
         $sort = request('sort') ?: 'id';
         $order = request('order') ?: 'DESC';
         $forme_juridique_filter = request('forme_juridique_filter', '');

         $query = Entreprise::query();

         // Search functionality
         if ($search) {
             $query->where(function ($query) use ($search) {
                 $query->where('denomination', 'like', '%' . $search . '%')
                     ->orWhere('address', 'like', '%' . $search . '%')
                     ->orWhere('country', 'like', '%' . $search . '%')
                     ->orWhere('city', 'like', '%' . $search . '%');
             });
         }

        //  Status filtering
         if ($forme_juridique_filter !== '') {
             $query->where('forme_juridique_id', $forme_juridique_filter);
         }

         // Role filtering
        //  if (!empty($role_ids)) {
        //      $query->whereHas('roles', function ($query) use ($role_ids) {
        //          $query->whereIn('roles.id', $role_ids);
        //      });
        //  }

         $totalentreprises = $query->count();


         $entreprises = $query->select('entreprises.*')
         ->leftJoin('forme_juridiques', 'entreprises.forme_juridique_id', '=', 'forme_juridiques.id')
         ->orderBy($sort, $order)
         ->paginate(request("limit"));

         $entreprises = $entreprises->through(function ($entreprise) {

             $actions = '';

                 $actions .= '<a href="/entreprises/edit/' . $entreprise->id . '" title="' . get_label('update', 'Update') . '">' .
                     '<i class="bx bx-edit mx-1"></i>' .
                     '</a>';



                 $actions .= '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $entreprise->id . '" data-type="entreprise">' .
                     '<i class="bx bx-trash text-danger mx-1"></i>' .
                     '</button>';


             $actions = $actions ?: '-';

             $photoHtml = "<div class='avatar avatar-md pull-up' title='" . $entreprise->denomination . "'>
             <a href='/entreprises/profile/" . $entreprise->id . "'>
                 <img src='" . ($entreprise->photo ? asset('storage/' . $entreprise->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Logo' class='rounded-circle'>
             </a>
           </div>";

$formattedHtml = '<div class="d-flex mt-2">' .
                 $photoHtml .
                 '<div class="mx-2">' .
                 '<h6 class="mb-1">' .
                 $entreprise->denomination .
                 '</h6>' .
                 '<p class="text-muted">' . $entreprise->city . ', ' . $entreprise->country . '</p>' .
                 '</div>' .
               '</div>';


             return [
                 'id' => $entreprise->id,
                 'country' => $entreprise->country,
                 'city' => $entreprise->city,
                 'profile' => $formattedHtml,
                 'formej' => $entreprise->forme_juridique ? $entreprise->forme_juridique->label : '--',
                 'created_at' => format_date($entreprise->created_at, true),
                 'updated_at' => format_date($entreprise->updated_at, true),
                 'actions' => $actions
             ];
         });

         return response()->json([
             "rows" => $entreprises->items(),
             "total" => $totalentreprises,
         ]);
     }

 }
