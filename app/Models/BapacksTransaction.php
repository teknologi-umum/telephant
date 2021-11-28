<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BapacksTransaction extends Model
{
    protected $table = 'bapacks_transactions';

    protected $fillable = [
        'id', 'bapack_id', 'user_id', 'operator', 'point'
    ];

    public function bapacks()
    {
        return $this->belongsTo(Bapacks::class, 'bapack_id');
    }
}
