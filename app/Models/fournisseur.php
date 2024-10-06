<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fournisseur extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function produits()
    {
        return $this->hasMany(Product::class);
    }
    public function entreprises()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function bonDeCommandes()
{
    return $this->hasMany(BonDeCommande::class);
}


}
