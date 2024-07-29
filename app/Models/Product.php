<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'product_category_id', 'price', 'stock', 'stock_defective'
    ];

    public function category()
    {
        return $this->belongsTo(ProdCategory::class);
    }


}
