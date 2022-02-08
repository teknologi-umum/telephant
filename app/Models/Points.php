<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    protected $table = 'points';

    protected $fillable = [
        'id', 'key'
    ];

    public function pointsTransactions() {
        return $this->hasMany(PointsTransactions::class, 'points_id');
    }
}
