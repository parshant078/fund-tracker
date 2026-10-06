<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invite extends Model
{
    protected $fillable = ['code', 'label', 'donor_name', 'active'];

    protected $casts = ['active' => 'boolean'];

    public static function generate(string $label): self
    {
        return self::create([
            'code' => strtoupper(Str::random(6)),
            'label' => $label,
        ]);
    }
}
