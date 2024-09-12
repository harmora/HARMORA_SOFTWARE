<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'product_category_id', 'price', 'stock','photo','entreprise_id','prev_price','total_amount','prev_stock','stock_defective'
    ];

    public function category()
    {
        return $this->belongsTo(ProdCategory::class);
    }
    public function entreprises()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_products')
                    ->withPivot('quantity', 'price');
    }
    public function achats()
    {
        return $this->belongsToMany(Achat::class, 'achat_product')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
    public function mouvements_stock()
    {
        return $this->hasMany(mouvements_stock::class, 'produit_id');
    }


}
