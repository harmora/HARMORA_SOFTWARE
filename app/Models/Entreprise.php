<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;
    protected $table = "entreprises";
    protected $guarded = ['id'];


    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function client()
    {
        return $this->hasMany(Client::class);
    }
    public function fournisseur()
    {
        return $this->hasMany(Fournisseur::class);
    }
    public function achat()
    {
        return $this->hasMany(Achat::class);
    }
    public function disponibility()
    {
        return $this->hasMany(Disponibility::class);
    }
    public function forme_juridique()
    {
        return $this->belongsTo(Forme_juridique::class);
    }
}
