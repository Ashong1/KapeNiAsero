<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month if no date provided
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Parse dates for Carbon (add end of day for the end date to catch all orders)
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // 1. Summary Stats
        $orders = Order::whereBetween('created_at', [$start, $end])
                       ->where('status', 'completed');

        $totalSales = $orders->sum('total_price');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        $cashSales = (clone $orders)->where('payment_mode', 'cash')->sum('total_price');
        $digitalSales = (clone $orders)->whereIn('payment_mode', ['gcash', 'card', 'paymaya'])->sum('total_price');

        // 2. Best Sellers (Product Performance)
        $bestSellers = OrderItem::whereHas('order', function($q) use ($start, $end) {
                                    $q->whereBetween('created_at', [$start, $end])
                                      ->where('status', 'completed');
                                })
                                ->select(
                                    'product_id', 
                                    DB::raw('SUM(quantity) as total_qty'), 
                                    DB::raw('SUM(price * quantity) as total_revenue')
                                )
                                ->with('product') // Eager load product name
                                ->groupBy('product_id')
                                ->orderByDesc('total_qty')
                                ->take(10) // Top 10
                                ->get();

        // 3. Recent Orders List for the Table
        $reportOrders = Order::with('user')
                             ->whereBetween('created_at', [$start, $end])
                             ->where('status', 'completed')
                             ->latest()
                             ->get();

        return view('reports.index', compact(
            'startDate', 'endDate', 'totalSales', 'totalOrders', 
            'averageOrderValue', 'bestSellers', 'reportOrders', 'cashSales', 'digitalSales'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $type = $request->input('type', 'csv'); // csv or pdf

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $orders = Order::with('user')
                       ->whereBetween('created_at', [$start, $end])
                       ->where('status', 'completed')
                       ->latest()
                       ->get();

        $filename = "sales_report_{$startDate}_to_{$endDate}";

        // --- CSV EXPORT ---
        if ($type === 'csv') {
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use ($orders) {
                $file = fopen('php://output', 'w');
                
                // Header Row
                fputcsv($file, ['Order ID', 'Date', 'Cashier', 'Payment Mode', 'Subtotal', 'Discount', 'Total']);

                // Data Rows
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->id,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->user->name ?? 'Unknown',
                        strtoupper($order->payment_mode),
                        number_format($order->subtotal, 2, '.', ''),
                        number_format($order->discount_amount, 2, '.', ''),
                        number_format($order->total_price, 2, '.', '')
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- PDF EXPORT ---
        if ($type === 'pdf') {
            $totalSales = $orders->sum('total_price');
            $totalOrders = $orders->count();

            $pdf = Pdf::loadView('reports.pdf', compact('orders', 'startDate', 'endDate', 'totalSales', 'totalOrders'));
            return $pdf->download("$filename.pdf");
        }

        return redirect()->back();
    }
}