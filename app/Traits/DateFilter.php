<?php

namespace App\Traits;

use Carbon\Carbon;
trait DateFilter
{
    public function scopeToday($query)
    {
        $query->whereDate('created_at', Carbon::today());
    }
    public function scopeThisWeek($query)
    {
        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }
    public function scopeThisMonth($query)
    {
        $query->whereMonth('created_at', Carbon::now()->month);
    }

    public function scopeThisYear($query)
    {
        $query->whereYear('created_at', Carbon::now()->year);
    }
}

