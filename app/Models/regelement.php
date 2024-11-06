<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class regelement extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_vente_id');
    }

    public function achat()
    {
        return $this->belongsTo(Achat::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
    
    
}
