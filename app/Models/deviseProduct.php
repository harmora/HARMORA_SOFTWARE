<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deviseProduct extends Model
{
    use HasFactory;
    protected $table = 'devise_products';

    protected $guarded = ['id'];

    public function devise()
    {
        return $this->belongsTo(devise::class);
    }

    // Relationship to the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
