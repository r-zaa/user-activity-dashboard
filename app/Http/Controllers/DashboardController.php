<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $start = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->subDays(7)->startOfDay();

        $end = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $action = $request->action;


        $baseQuery = Activity::whereBetween('created_at', [$start, $end]);

        if ($action) {
            $baseQuery->where('action', $action);
        }

        $cacheKey = 'dashboard:' . md5(json_encode($request->all()));
        
        $data = cache()->remember($cacheKey, now()->addMinutes(5), function () use ($baseQuery) {

            return [
                'activityPerDay' => (clone $baseQuery)
                    ->selectRaw('DATE(created_at) date, COUNT(*) total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),

                'topUsers' => (clone $baseQuery)
                    ->selectRaw('user_id, COUNT(*) total')
                    ->with('user:id,name')
                    ->groupBy('user_id')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->get(),

                'activityPerAction' => (clone $baseQuery)
                    ->selectRaw('action, COUNT(*) total')
                    ->groupBy('action')
                    ->get(),
            ];
        });

        return view('dashboard', $data);
    }
}
