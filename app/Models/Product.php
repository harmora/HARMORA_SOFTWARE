<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'product_category_id', 'price', 'stock', 'stock_defective','photo'
    ];

    public function category()
    {
        return $this->belongsTo(ProdCategory::class);
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_product')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
    public function achats()
    {
        return $this->hasMany(Achat::class);
    }
    public function mouvements_stock()
    {
        return $this->hasMany(mouvements_stock::class, 'produit_id');
    }


}
