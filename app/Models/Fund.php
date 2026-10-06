<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $fillable = ['donor_name', 'amount', 'date', 'note'];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}
