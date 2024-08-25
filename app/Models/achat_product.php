<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class achat_product extends Model
{
    use HasFactory;
    protected $table = 'achat_products';
    protected $fillable = [
        'achat_id',
        'product_id',
        'quantity',
        'price',    
    ];
    public function achats()
    {
        return $this->belongsTo(Achat::class);
    }

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
    
}
