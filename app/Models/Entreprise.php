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
    public function regelement()
    {
        return $this->hasMany(Regelement::class);
    }
    public function document()
    {
        return $this->hasMany(Document::class);
    }
    public function achat()
    {
        return $this->hasMany(Achat::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function depot()
    {
        return $this->hasMany(depot::class);
    }
    public function commande()
    {
        return $this->hasMany(Commande::class);
    }
    public function invoice()
    {
        return $this->hasMany(invoice::class);
    }
    public function devise()
    {
        return $this->hasMany(devise::class);
    }
    public function bon_livraision()
    {
        return $this->hasMany(bon_livraision::class);
    }
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
    public function disponibility()
    {
        return $this->hasMany(Disponibility::class);
    }

    public function forme_juridique()
    {
        return $this->belongsTo(Forme_juridique::class);
    }

    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }

    // Entreprise.php
public function bonDeCommandes()
{
    return $this->hasMany(BonDeCommande::class);
}

}

