<?php

namespace App\Models;

use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('first_name', 'like', '%' . request('search') . '%')
                ->orWhere('last_name', 'like', '%' . request('search') . '%')
                ->orWhere('role', 'like', '%' . request('search') . '%');
        }
    }





    // public function meetings()
    // {
    //     return $this->belongsToMany(Meeting::class)->where('workspace_id', '=', session()->get('workspace_id'));
    // }
    public function meetings($status = null)
    {
        $meetings = $this->belongsToMany(Meeting::class);

        if ($status !== null && $status == 'ongoing') {
            $meetings->where('start_date_time', '<=', Carbon::now(config('app.timezone')))
                ->where('end_date_time', '>=', Carbon::now(config('app.timezone')));
        }

        return $meetings;
    }


    public function role()
    {
        return $this->belongsTo(RoleAuth::class);
    }


    public function todos($status = null, $search = '')
    {
        $query = $this->morphMany(Todo::class, 'creator');

        if ($status !== null) {
            $query->where('is_completed', $status);
        }
        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query;
    }


    public function userRole()
    {
        return str($this->role);
    }




    public function profile()
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function getresult()
    {
        return str($this->first_name . " " . $this->last_name);
    }


    public function notes($search = '', $orderBy = 'id', $direction = 'desc')
    {
        $query = Note::where(function ($query) {
            $query->where('creator_id', $this->getKey());
        });

        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }
        $query->orderBy($orderBy, $direction);
        return $query->get();
    }

    public function timesheets()
    {
        return $this->hasMany(TimeTracker::class, 'user_id', 'id')
            ->where('workspace_id', session()->get('workspace_id'));
    }






    // public function can($ability, $arguments = [])
    // {
    //     $isAdmin = $this->hasRole('admin'); // Check if the user has the 'admin' role

    //     // Check if the user is an admin or has the specific permission
    //     if ($isAdmin || $this->hasPermissionTo($ability)) {
    //         return true;
    //     }

    //     // For other cases, use the original can() method
    //     return parent::can($ability, $arguments);
    // }


    public function getlink()
    {
        return str('/users/profile/show/' . $this->id);
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user');
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

}
