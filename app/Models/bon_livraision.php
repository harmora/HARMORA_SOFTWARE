<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bon_livraision extends Model
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
    public function invoice()
    {
        return $this->belongsTo(invoice::class);
    }
    
}
