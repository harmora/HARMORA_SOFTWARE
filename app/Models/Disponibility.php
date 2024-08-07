<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_name',
        'details',
        'start_date_time',
        'end_date_time',
        'entreprise_id', 
    ];




        public function entreprises()
    {
        return $this->belongsTo(Entreprise::class);
    }


}
