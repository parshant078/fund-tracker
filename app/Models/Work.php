<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Work extends Model
{
    protected $fillable = ['title', 'budget', 'advance_paid', 'status', 'notes', 'sort_order'];

    protected $casts = [
        'budget' => 'decimal:2',
        'advance_paid' => 'decimal:2',
    ];

    public const STATUSES = ['planned' => 'Planned', 'in_progress' => 'In Progress', 'completed' => 'Completed'];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function spent(): float
    {
        return (float) $this->expenses()->sum('amount');
    }
}
