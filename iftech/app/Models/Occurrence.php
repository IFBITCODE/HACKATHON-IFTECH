<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occurrence extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'category',
        'severity',
        'status',
        'occurred_at',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];
}