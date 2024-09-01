<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeProduct extends Model
{
    use HasFactory;

    protected $table = 'commande_products';

    protected $fillable = [
        'commande_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relationship to the Commande model
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // Relationship to the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
