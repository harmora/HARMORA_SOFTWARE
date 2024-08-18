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
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function mouvements_stock()
    {
        return $this->hasMany(mouvements_stock::class);
    }

}
