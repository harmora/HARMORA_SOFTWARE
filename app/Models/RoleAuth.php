<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAuth extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rolesauth';

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
