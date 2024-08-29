<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'contact_details',
        'email',
        'date',
        'invoice_number',
        'logo',
        'client_id',
        'item_description',
        'item_quantity',
        'item_price',
        'total_amount',
        'tax_rate',
        'tax_amount',
        'grand_total',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'commande_products')
                    ->withPivot('quantity', 'price');
    }
}

