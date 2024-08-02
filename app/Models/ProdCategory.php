<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdCategory extends Model
{
    use HasFactory;
    protected $table = 'prod_categories';
    protected $fillable = ['name_cat'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
