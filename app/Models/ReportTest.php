<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportTest extends Model
{
    protected $fillable = [
        'report_id',
        'test_order_id',
        'test_id',
        'interpretation',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function testOrder(): BelongsTo
    {
        return $this->belongsTo(TestOrder::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
