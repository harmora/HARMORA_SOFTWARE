<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_id',
        'to_ids',
        'type',
        'type_id',
        'action',
        'title',
        'message'
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_notifications')->withPivot('read_at');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')->withPivot('read_at');
    }

}
