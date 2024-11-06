<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function fournisseur()
    {
        return $this->belongsTo(fournisseur::class);
    }
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function reglements()
    {
        return $this->hasMany(regelement::class,);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'achat_product')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
    public function mouvements_stock()
    {
        return $this->hasMany(mouvements_stock::class);
    }

}
