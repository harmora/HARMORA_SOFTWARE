<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonDeCommande extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming conventions
    protected $table = 'bon_de_commande';

    // Define the fillable fields
    protected $fillable = [
        'fournisseur_id',
        'entreprise_id',
        'type_achat',
        'montant',
        'reference',
        'date_commande',
        'montant_ht',
        'bon',
        'status',
    ];

    /**
     * Get the fournisseur that this bon de commande belongs to.
     */
    public function fournisseur()
    {
        return $this->belongsTo(fournisseur::class);
    }

    /**
     * Get the entreprise that this bon de commande belongs to.
     */
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'bon_commande_product')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}
