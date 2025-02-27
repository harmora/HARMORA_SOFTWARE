<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'product_category_id', 'price', 'stock','photo','entreprise_id','prev_price','total_amount','prev_stock','stock_defective','reference'
    ];

    public function category()
    {
        return $this->belongsTo(ProdCategory::class);
    }
    public function entreprises()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function depots()
{
    return $this->belongsToMany(Depot::class,'depot_product')->withPivot('quantity')->withTimestamps();
}
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_products')
                    ->withPivot('quantity', 'price');
    }
    public function devises()
    {
        return $this->morphedByMany(devise::class, 'related', 'vente_products')
                    ->withPivot('quantity', 'price');
    }
    public function invoice()
    {
        return $this->morphedByMany(invoice::class, 'related', 'vente_products')
                    ->withPivot('quantity', 'price');
    }
    public function bon_livraisions()
    {
        return $this->morphedByMany(bon_livraision::class, 'related', 'vente_products')
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

    public function bonCommandes()
    {
        return $this->belongsToMany(BonDeCommande::class, 'bon_commande_product')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

}
