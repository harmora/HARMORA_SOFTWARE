<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forme_juridique extends Model
{
    use HasFactory;
    protected $table = "Forme_juridiques";

    protected $guarded = ['id'];

    public function entreprise()
    {
        return $this->hasMany(Forme_juridique::class);
    }
}
