<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id',
        'start_date_time',
        'end_date_time',
        'created_by'

    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }
    public function getresult()
    {
        return substr($this->title, 0, 100);
    }


    public function getlink()
    {
        return str('/meetings');
    }
}
