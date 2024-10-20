<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class depot extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function products()
    {
        return $this->belongsToMany(Product::class,'depot_product')->withPivot('quantity')->withTimestamps();
    }
    public function entreprises()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function mouvements_stock()
    {
        return $this->hasMany(mouvements_stock::class);
    }
}
