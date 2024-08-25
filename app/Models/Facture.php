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
        'client_name',
        'client_address',
        'client_contact_details',
        'item_description',
        'item_quantity',
        'item_price',
        'total_amount',
        'tax_rate',
        'tax_amount',
        'grand_total',
    ];
}
