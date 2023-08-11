<?php

namespace App\Http\Controllers;

use App\Models\Statistic;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function countPerCountryAndDate()
    {
        $baseQuery = Statistic::selectRaw("sum(`count`) as count, date, country");
        return $baseQuery->groupBy('date', 'country')->get();
    }

    //todo add more stats
}
