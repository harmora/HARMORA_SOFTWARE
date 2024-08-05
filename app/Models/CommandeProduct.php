<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeProduct extends Model
{
    use HasFactory;
    protected $table = 'commande_product';
    protected $fillable = [
        'commande_id',
        'product_id',
        'quantity',
        'price',
    ];
    public $timestamps = false; // Since this is a pivot table, you might not need timestamps

}
