<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mouvements_stock extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function achat()
    {
        return $this->belongsTo(Achat::class);
    }

}
