<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetricDaily extends Model
{
    protected $table = 'metrics_daily';

    protected $fillable = [
        'metric_date',
        'accesses_total',
        'unique_visitors',
        'recurring_visitors',
        'searches_total',
        'avg_navigation_seconds',
        'return_rate',
        'top_pages',
        'top_attractions',
        'top_routes',
        'top_events',
        'geo_origin',
        'languages',
        'devices',
        'channels',
    ];

    protected $casts = [
        'metric_date' => 'date',

        'top_pages' => 'array',
        'top_attractions' => 'array',
        'top_routes' => 'array',
        'top_events' => 'array',

        'geo_origin' => 'array',
        'languages' => 'array',
        'devices' => 'array',
        'channels' => 'array',

        'return_rate' => 'float',
    ];
}