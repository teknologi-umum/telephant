<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointsTransactions extends Model
{
    protected $table = 'points_transactions';

    protected $fillable = [
        'id',
        'points_id',
        'points',
        'op',
        'bapacks_id'
    ];

    public function getPoints()
    {
        return $this->belongsTo(Points::class, 'points_id');
    }

    public function bapacks()
    {
        return $this->belongsTo(Bapacks::class, 'bapacks_id');
    }

    
}
