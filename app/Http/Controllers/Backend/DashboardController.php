<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderTransaction;
use App\Models\Product;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cutoffConfig = config('business_day.cutoff_time');
        $now = Carbon::now();
        $businessDay = $now->lt($now->copy()->setTimeFromTimeString($cutoffConfig))
            ? $now->copy()->startOfDay()
            : $now->copy()->addDay()->startOfDay();
        $total_customer = Customer::whereDate('created_at', $businessDay)->count();
        $total_product = Product::count();
        $total_order = Order::whereDate('created_at', $businessDay)->count();
        $total_sale_item = OrderProduct::whereDate('created_at', $businessDay)->count();

        $sub_total = Order::whereDate('created_at', $businessDay)->sum('sub_total');
        $total = Order::whereDate('created_at', $businessDay)->sum('total');

        $dates = [];
        $totalAmounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $businessDay->copy()->subDays($i);
            $dates[] = $date->format('Y-m-d');
            $totalAmounts[] = Order::whereDate('created_at', $date)->sum('total');
        }

        $currentYear = Carbon::now()->year;
        $months = [];
        $totalAmountMonth = [];

        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::create($currentYear, $i + 1, 1);
            $months[] = $month->format('M');
            $totalAmountMonth[] = Order::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month->month)
                ->sum('total');
        }

        $dateRange = $businessDay->copy()->subDays(6)->format('Y-m-d') . ' to ' . $businessDay->format('Y-m-d');

        return view('backend.index', compact('total_customer', 'total_product', 'total_order', 'total_sale_item', 'sub_total', 'total', 'dates', 'totalAmounts', 'currentYear', 'months', 'totalAmountMonth', 'dateRange'));


    }

    public function profile()
    {
        $user = auth()->user();
        return view('backend.profile.index', compact('user'));
    }

    public function closeDay(Request $request)
    {
        $currentBusinessDay = Carbon::parse(config('business_day.start_date'));
        $nextBusinessDay = $currentBusinessDay->addDay();

        // Update the .env file with the new business day start date
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'BUSINESS_DAY_START_DATE="'.env('BUSINESS_DAY_START_DATE').'"',
                'BUSINESS_DAY_START_DATE="'.$nextBusinessDay->format('Y-m-d').'"',
                file_get_contents($path)
            ));
        }

        Artisan::call('config:clear');

        return response()->json(['message' => 'Business day advanced to ' . $nextBusinessDay->format('Y-m-d') . '!']);
    }
}
