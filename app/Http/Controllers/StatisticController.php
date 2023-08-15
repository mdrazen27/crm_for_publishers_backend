<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function countPerMonth(Request $request): JsonResponse
    {
        $statistic = Statistic::selectRaw("sum(`count`) as `count`, month(date) as month");
        if ($request->publisher_id && Auth::user()->isAdmin()) {
            $statistic->where('publisher_id', $request->publisher_id);
        }
        $statistic->where('date', '>=', Carbon::now()->year)->groupBy('month');
        return new JsonResponse($statistic->get());
    }

    public function countPerDay(Request $request): JsonResponse
    {
        $statistic = Statistic::selectRaw("sum(`count`) as `count`, date");
        if ($request->publisher_id && Auth::user()->isAdmin()) {
            $statistic->where('publisher_id', $request->publisher_id);
        }
        $statistic->where('date', '>=', Carbon::now())->orderBy('date')->groupBy('date');
        return new JsonResponse($statistic->get());
    }

    public function topTenCountriesByViewCount(Request $request): JsonResponse
    {
        $statistic = Statistic::selectRaw("sum(`count`) as `count`, country");
        if ($request->publisher_id && Auth::user()->isAdmin()) {
            $statistic->where('publisher_id', $request->publisher_id);
        }
        $statistic->where('date', '>=', Carbon::now())->groupBy('country')->limit(10);
        return new JsonResponse($statistic->get());
    }

    public function topFiveActiveAdvertisementsByViewCount(Request $request): JsonResponse
    {
        $statistic = Statistic::selectRaw("sum(`count`) as `count`, advertisement_id")
            ->whereHas('advertisement', function ($query) {
                $query->where('active', 1);
            })
            ->with('advertisement');
        if ($request->publisher_id && Auth::user()->isAdmin()) {
            $statistic->where('publisher_id', $request->publisher_id);
        }
        $statistic->where('date', '>=', Carbon::now())->orderBy('count', 'desc')->groupBy('advertisement_id')->limit(5);
        return new JsonResponse($statistic->get());
    }
}
