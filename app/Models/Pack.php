<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'number_of_accounts',
        'photo',
    ];

    public function entreprises()
{
    return $this->hasMany(Entreprise::class);
}
public function features()
{
    return $this->belongsToMany(Feature::class);
}

}
