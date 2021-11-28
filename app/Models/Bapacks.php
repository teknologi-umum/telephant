<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Bapacks extends Model
{
    protected $table = 'bapacks';

    protected $fillable = [
        'id', 'user_id', 'point', 'first_name', 'last_name'
    ];

    public function bapacksTransactions()
    {
        return $this->hasMany(BapacksTransaction::class, 'bapack_id');
    }
}
