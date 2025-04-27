<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Pesanan;
use App\Models\Transaksi;
use App\Models\Meja;
use App\Models\Pelanggan;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:kasir,owner');
    }

    /**
     * Display a listing of the transactions.
     */
    public function index()
    {
        $transactions = Transaksi::orderBy('tanggal', 'desc')->get();
        
        return view('transaksi.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        try {
            // Get all orders with status 'selesai' that don't have a transaction yet
            $pendingOrders = Pesanan::with(['menu', 'pelanggan', 'meja'])
                                    ->where('status', 'selesai')
                                    ->whereNotIn('idpesanan', function($query) {
                                        $query->select(DB::raw('SUBSTRING_INDEX(SUBSTRING_INDEX(idpesanan, ",", n.n), ",", -1)'))
                                              ->from('transaksi')
                                              ->crossJoin(DB::raw('(SELECT 1 as n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) n'))
                                              ->whereRaw('LENGTH(idpesanan) - LENGTH(REPLACE(idpesanan, ",", "")) >= n.n - 1');
                                    })
                                    ->orderBy('tanggal', 'desc')
                                    ->get()
                                    ->groupBy('idpelanggan');
                                    
            if ($pendingOrders->isEmpty()) {
                return redirect()->route('transaksi.index')->with('info', 'No pending orders available for transaction.');
            }
                                    
            return view('transaksi.create', compact('pendingOrders'));
        } catch (Exception $e) {
            Log::error('Error in create transaction: ' . $e->getMessage());
            return redirect()->route('transaksi.index')->with('danger', 'Error loading transaction page: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'idpesanan' => 'required|array|min:1',
                'idpesanan.*' => 'exists:pesanan,idpesanan',
                'total' => 'required|numeric|min:0',
                'bayar' => 'required|numeric|min:0',
                'metode_pembayaran' => 'required|in:tunai,kartu,e-wallet',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            // Check if payment is sufficient
            if ($request->bayar < $request->total) {
                return Redirect::back()->withInput()->with('danger', 'Payment amount must be equal to or greater than the total amount');
            }

            // Create the transaction
            $transaction = Transaksi::create([
                'idpesanan' => implode(',', $request->idpesanan), // Store as comma-separated values
                'total' => $request->total,
                'bayar' => $request->bayar,
                'iduser' => Auth::id(),
                'tanggal' => now(),
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            // Update all the related orders to 'dibayar' status
            foreach ($request->idpesanan as $orderId) {
                $order = Pesanan::findOrFail($orderId);
                $order->status = 'dibayar';
                $order->save();
                
                // Free up the table if this is the last order for this table
                $tableId = $order->idmeja;
                $remainingOrders = Pesanan::where('idmeja', $tableId)
                                        ->where('status', '!=', 'dibayar')
                                        ->count();
                                        
                if ($remainingOrders == 0) {
                    $table = Meja::findOrFail($tableId);
                    $table->status = 'tersedia';
                    $table->save();
                }
            }
            
            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Transaction completed successfully']);
            }

            return redirect()->route('transaksi.show', $transaction->idtransaksi)
                            ->with('success', 'Transaction completed successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating transaction: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error creating transaction: ' . $e->getMessage()], 422);
            }

            return Redirect::back()->withInput()->with('danger', 'Error creating transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show($idtransaksi)
    {
        $transaction = Transaksi::findOrFail($idtransaksi);
        
        // For multiple orders in a single transaction
        $orderIds = explode(',', $transaction->idpesanan);
        $orders = Pesanan::with(['menu', 'pelanggan', 'meja'])
                        ->whereIn('idpesanan', $orderIds)
                        ->get();
        
        return view('transaksi.show', compact('transaction', 'orders'));
    }

    /**
     * Generate receipt/invoice for a transaction.
     */
    public function receipt($idtransaksi)
    {
        $transaction = Transaksi::findOrFail($idtransaksi);
        
        // For multiple orders in a single transaction
        $orderIds = explode(',', $transaction->idpesanan);
        $orders = Pesanan::with(['menu', 'pelanggan', 'meja'])
                        ->whereIn('idpesanan', $orderIds)
                        ->get();
        
        return view('transaksi.receipt', compact('transaction', 'orders'));
    }

    /**
     * Process payment for existing orders.
     */
    public function processPayment(Request $request)
    {
        try {
            // Get all selected orders
            $orderIds = $request->input('order_ids', []);
            if (empty($orderIds)) {
                return Redirect::back()->with('danger', 'No orders selected for payment');
            }
            
            $orders = Pesanan::with('menu')
                           ->whereIn('idpesanan', $orderIds)
                           ->get();
                           
            if ($orders->isEmpty()) {
                return Redirect::back()->with('danger', 'Selected orders not found');
            }
            
            // Calculate total amount
            $total = 0;
            foreach ($orders as $order) {
                $total += $order->menu->harga * $order->jumlah;
            }
            
            return view('transaksi.payment', compact('orders', 'total', 'orderIds'));
        } catch (Exception $e) {
            Log::error('Error processing payment: ' . $e->getMessage());
            return Redirect::back()->with('danger', 'Error processing payment: ' . $e->getMessage());
        }
    }

    /**
     * Show report form.
     */
    public function reportForm()
    {
        return view('transaksi.report-form');
    }

    /**
     * Get transactions by date range (for reporting).
     */
    public function report(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->end_date . ' 23:59:59';

            $transactions = Transaksi::whereBetween('tanggal', [$startDate, $endDate])
                                   ->orderBy('tanggal', 'desc')
                                   ->get();
                                   
            $totalRevenue = $transactions->sum('total');
            $paymentMethods = $transactions->groupBy('metode_pembayaran')
                                         ->map(function ($items) {
                                             return [
                                                 'count' => $items->count(),
                                                 'total' => $items->sum('total')
                                             ];
                                         });

            // Check if PDF download is requested
            if ($request->has('download') && $request->download == 'pdf') {
                $pdf = PDF::loadView('transaksi.report-pdf', compact(
                    'transactions', 
                    'totalRevenue', 
                    'paymentMethods', 
                    'startDate', 
                    'endDate'
                ));
                
                $fileName = 'transaction_report_' . date('Y-m-d') . '.pdf';
                return $pdf->download($fileName);
            } elseif ($request->has('print') && $request->print == 'pdf') {
                $pdf = PDF::loadView('transaksi.report-pdf', compact(
                    'transactions', 
                    'totalRevenue', 
                    'paymentMethods', 
                    'startDate', 
                    'endDate'
                ));
                
                $fileName = 'transaction_report_' . date('Y-m-d') . '.pdf';
                return $pdf->stream($fileName);
            }

            return view('transaksi.report', compact(
                'transactions', 
                'totalRevenue', 
                'paymentMethods', 
                'startDate', 
                'endDate'
            ));
        } catch (Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());
            return redirect()->route('transaksi.report.form')->with('danger', 'Error generating report: ' . $e->getMessage());  
        }
    }
}