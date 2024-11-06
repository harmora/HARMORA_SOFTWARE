<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function products()
    {
        return $this->morphToMany(Product::class, 'related', 'vente_products')
        ->withPivot('quantity', 'price');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function reglements()
    {
        return $this->hasMany(regelement::class, 'invoice_vente_id');
    }
    public function bonLivraisons()
    {
        return $this->hasMany(bon_livraision::class);
    }
    public function isFullyShipped()
    {
        foreach ($this->products as $product) {
            $totalShipped = $this->bonLivraisons->sum(function($bl) use ($product) {
                return $bl->products->where('id', $product->id)->sum('pivot.quantity');
            });

            if ($totalShipped < $product->pivot->quantity) {
                return false;
            }
        }
        return true;
    }


}
