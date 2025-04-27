<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:waiter,owner');
    }

    public function index()
    {
        // get all orders with relationships
        $orders = Pesanan::with(['menu', 'pelanggan', 'meja', 'waiter'])
                        ->orderBy('tanggal', 'desc')
                        ->get()
                        ->groupBy('idpelanggan');

        // Get tables data for edit modal
        $tables = Meja::all();

        // Add this line to include menus in the view data
        $menus = Menu::where('status', 'tersedia')->get();
        
        return view('pesanan.index', compact('orders', 'menus', 'tables'));
    }

    public function create()
    {
        // Get all available tables, menus and customers for the form
        $tables = Meja::where('status', 'tersedia')->get();
        $menus = Menu::where('status', 'tersedia')->get();
        $customers = Pelanggan::all();
        
        return view('pesanan.create', compact('tables', 'menus', 'customers'));
    }

    public function store(Request $request)
    {
        try {
            // validate customer information
            $validatedCustomer = $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'no_hp' => 'required|string|max:13',
                'alamat' => 'nullable|string|max:255'
            ]);

            // create or find the customer
            $customer = Pelanggan::updateOrCreate(
                ['no_hp' => $validatedCustomer['no_hp']],
                [
                    'nama_pelanggan' => $validatedCustomer['nama_pelanggan'],
                    'jenis_kelamin' => $validatedCustomer['jenis_kelamin'] == 'L' ? 1 : 0,
                    'alamat' => $validatedCustomer['alamat'] ?? '',
                ]
            );

            // validate table selection
            $request->validate([
                'idmeja' => 'required|exists:meja,idmeja',
                'menu_items' => 'required|array|min:1',
                'menu_items.*.idmenu' => 'required|exists:menu,idmenu',
                'menu_items.*.jumlah' => 'required|integer|min:1',
            ]);

            // update table status
            $table = Meja::findOrFail($request->idmeja);
            $table->status = 'terisi';
            $table->save();

            // create order records for each menu item
            foreach ($request->menu_items as $item) {
                Pesanan::create([
                    'idmenu' => $item['idmenu'],
                    'idpelanggan' => $customer->idpelanggan,
                    'jumlah' => $item['jumlah'],
                    'iduser' => Auth::id(),
                    'idmeja' => $request->idmeja,
                    'tanggal' => now(),
                    'status' => 'baru', // New status
                ]);
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Order created successfully']);
            }

            return redirect()->route('order.index')->with('success', 'Order created successfully');
        } catch (Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error creating data: ' . $e->getMessage()], 422);
            }

            return Redirect::back()->withInput()->with('danger', 'Error creating data: ' . $e->getMessage());
        }
    }

    public function show($idpelanggan)
    {
        $orders = Pesanan::with(['menu', 'pelanggan', 'meja', 'waiter'])
                        ->where('idpelanggan', $idpelanggan)
                        ->get();
                        
        if ($orders->isEmpty()) {
            return redirect()->route('order.index')->with('danger', 'Order not found');
        }
        
        $customer = $orders->first()->pelanggan;
        $table = $orders->first()->meja;
        
        // Calculate order totals
        $orderItems = $orders->map(function($order) {
            return [
                'idpesanan' => $order->idpesanan,
                'menu_name' => $order->menu->nama_menu,
                'price' => $order->menu->harga,
                'quantity' => $order->jumlah,
                'subtotal' => $order->menu->harga * $order->jumlah,
                'status' => $order->status,
                'created_at' => $order->tanggal->format('d M Y H:i')
            ];
        });
        
        $totalAmount = $orderItems->sum('subtotal');
        $orderStatuses = $orders->pluck('status')->unique();
        $mainStatus = $orders->groupBy('status')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();
        
        return view('pesanan.show', compact(
            'orders', 
            'customer', 
            'table', 
            'orderItems',
            'totalAmount',
            'mainStatus',
            'orderStatuses'
        ));
    }

    public function edit($idpelanggan)
    {
        $orders = Pesanan::with(['menu', 'pelanggan', 'meja', 'waiter'])
                        ->where('idpelanggan', $idpelanggan)
                        ->get();
                        
        if ($orders->isEmpty()) {
            return redirect()->route('order.index')->with('danger', 'Order not found');
        }
        
        $customer = $orders->first()->pelanggan;
        $table = $orders->first()->meja;
        
        // Get available tables (available + current table even if occupied)
        $tables = Meja::where('status', 'tersedia')
                    ->orWhere('idmeja', $table->idmeja)
                    ->get();
        
        $menus = Menu::where('status', 'tersedia')->get();
        
        // Determine main status more reliably
        $statuses = $orders->pluck('status')->unique();
        
        // Default to first status if only one status exists
        if ($statuses->count() === 1) {
            $mainStatus = $statuses->first();
        } else {
            // If multiple statuses, use the most "incomplete" one
            $statusPriority = ['baru', 'diproses', 'selesai', 'dibayar'];
            foreach ($statusPriority as $status) {
                if ($statuses->contains($status)) {
                    $mainStatus = $status;
                    break;
                }
            }
        }
        
        return view('pesanan.edit', compact(
            'orders', 
            'customer', 
            'table', 
            'tables', 
            'menus',
            'mainStatus'
        ));
    }

    public function update(Request $request, $idpelanggan)
    {
        try {
            DB::beginTransaction();

            // Get existing customer data first for comparison
            $customer = Pelanggan::findOrFail($idpelanggan);
            $originalCustomerData = $customer->toArray();
            
            // Validate customer information
            $validatedCustomer = $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'no_hp' => 'required|string|max:13',
                'alamat' => 'nullable|string|max:255',
                'idmeja' => 'required|exists:meja,idmeja',
            ]);

            // Extract only changed customer fields
            $customerChanges = [];
            if ($originalCustomerData['nama_pelanggan'] !== $validatedCustomer['nama_pelanggan']) {
                $customerChanges['nama_pelanggan'] = $validatedCustomer['nama_pelanggan'];
            }
            
            if (($originalCustomerData['jenis_kelamin'] == 1 && $validatedCustomer['jenis_kelamin'] != 'L') ||
                ($originalCustomerData['jenis_kelamin'] == 0 && $validatedCustomer['jenis_kelamin'] != 'P')) {
                $customerChanges['jenis_kelamin'] = $validatedCustomer['jenis_kelamin'] == 'L' ? 1 : 0;
            }
            
            if ($originalCustomerData['no_hp'] !== $validatedCustomer['no_hp']) {
                $customerChanges['no_hp'] = $validatedCustomer['no_hp'];
            }
            
            if ($originalCustomerData['alamat'] !== ($validatedCustomer['alamat'] ?? '')) {
                $customerChanges['alamat'] = $validatedCustomer['alamat'] ?? '';
            }
            
            // Only update customer if there are changes
            if (!empty($customerChanges)) {
                $customer->update($customerChanges);
            }
            
            // Update customer info
            $customer = Pelanggan::findOrFail($idpelanggan);
            $customer->update([
                'nama_pelanggan' => $validatedCustomer['nama_pelanggan'],
                'jenis_kelamin' => $validatedCustomer['jenis_kelamin'] == 'L' ? 1 : 0,
                'no_hp' => $validatedCustomer['no_hp'],
                'alamat' => $validatedCustomer['alamat'] ?? '',
            ]);
            
            // Check if table has changed
            $orders = Pesanan::where('idpelanggan', $idpelanggan)->get();
            $currentTableId = $orders->first()->idmeja;
            
            if ($currentTableId != $request->idmeja) {
                // Release old table if no other orders are using it
                $otherOrdersOnOldTable = Pesanan::where('idmeja', $currentTableId)
                    ->where('idpelanggan', '!=', $idpelanggan)
                    ->count();
                
                if ($otherOrdersOnOldTable == 0) {
                    $oldTable = Meja::findOrFail($currentTableId);
                    $oldTable->status = 'tersedia';
                    $oldTable->save();
                }
                
                // Mark new table as occupied
                $newTable = Meja::findOrFail($request->idmeja);
                $newTable->status = 'terisi';
                $newTable->save();
                
                // Update all orders for this customer to use the new table
                Pesanan::where('idpelanggan', $idpelanggan)
                    ->update(['idmeja' => $request->idmeja]);
            } else {
                // Table hasn't changed, skip processing
                Log::info('Table unchanged for customer ID: ' . $idpelanggan);
            }
            
            // Handle existing menu items updates if present
            if ($request->has('order_items')) {
                foreach ($request->order_items as $idpesanan => $item) {
                    if (isset($item['idpesanan'])) {
                        $order = Pesanan::findOrFail($item['idpesanan']);

                        // Check for changes before updating
                        $orderChanged = false;
                        if ($order->idmenu != $item['idmenu'] || $order->jumlah != $item['jumlah']) {
                            $orderChanged = true;
                        }

                        if ($orderChanged) {
                            $order->update([
                                'idmenu' => $item['idmenu'],
                                'jumlah' => $item['jumlah'],
                            ]);
                            Log::info('Updated order item: ' . $item['idpesanan']);
                        } else {
                            Log::info('Skipped unchanged order item: ' . $item['idpesanan']);
                        }
                    }
                }
            }
            
            // Handle new menu items if any
            if ($request->has('menu_items')) {
                foreach ($request->menu_items as $item) {
                    Pesanan::create([
                        'idmenu' => $item['idmenu'],
                        'idpelanggan' => $idpelanggan,
                        'jumlah' => $item['jumlah'],
                        'iduser' => Auth::id(),
                        'idmeja' => $request->idmeja,
                        'tanggal' => now(),
                        'status' => 'baru',
                    ]);
                }
            }

            if ($request->has('status')) {
                $oldStatus = $orders->first()->status;
                if ($oldStatus !== $request->status) {
                    Pesanan::where('idpelanggan', $idpelanggan)
                        ->update(['status' => $request->status]);
                    Log::info('Updated status from ' . $oldStatus . ' to ' . $request->status);
                } else {
                    Log::info('Status unchanged, skipping update');
                }
            }
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Order updated successfully']);
            }
            
            return redirect()->route('order.index')->with('success', 'Order updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating order: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error updating order: ' . $e->getMessage()], 422);
            }
            
            return Redirect::back()->withInput()->with('danger', 'Error updating order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:diproses,selesai',
                'scope' => 'sometimes|in:single,all' // Tambahkan parameter scope
            ]);
    
            // Jika update semua item pesanan
            if ($request->scope === 'all') {
                $orders = Pesanan::where('idpelanggan', $id)->get();
                $mainStatus = $orders->first()->status;
                
                // Validasi transisi status
                $this->validateStatusTransition($mainStatus, $request->status);
                
                Pesanan::where('idpelanggan', $id)
                      ->update(['status' => $request->status]);
                      
                if ($request->expectsJson()) {
                    return response()->json(['success' => true, 'message' => 'All items status updated']);
                }
                return redirect()->back()->with('success', 'All items status updated');
            }
            // Jika update single item
            else {
                $order = Pesanan::findOrFail($id);
                $this->validateStatusTransition($order->status, $request->status);
                
                $order->update(['status' => $request->status]);

                if ($request->expectsJson()) {
                    return response()->json(['success' => true, 'message' => 'Item status updated']);
                }
                return redirect()->back()->with('success', 'Item status updated');
            }
        } catch (Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error updating status: ' . $e->getMessage()], 422);
            }

            return Redirect::back()->with('danger', 'Error updating status: ' . $e->getMessage());
        }
    }

    // Helper method untuk validasi transisi status
    private function validateStatusTransition($currentStatus, $newStatus)
    {
        $allowedTransitions = [
            'baru' => ['diproses'],
            'diproses' => ['selesai']
        ];
        
        if (!isset($allowedTransitions[$currentStatus])) {
            throw new Exception('Invalid current status for transition');
        }
        
        if (!in_array($newStatus, $allowedTransitions[$currentStatus])) {
            throw new Exception('Invalid status transition');
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $pesanan = Pesanan::findOrFail($request->idpesanan);
            
            // free up the table if this is the last order for this table
            $tableOrders = Pesanan::where('idmeja', $pesanan->idmeja)
                            ->where('idpesanan', '!=', $pesanan->idpesanan)
                            ->count();
                            
            if ($tableOrders == 0) {
                $table = Meja::findOrFail($pesanan->idmeja);
                $table->status = 'tersedia';
                $table->save();
            }
            
            $pesanan->delete();
            
            return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
        } catch (Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show report form.
     */
    public function reportForm()
    {
        return view('pesanan.report-form');
    }
    
    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Get orders within date range
        $orders = Pesanan::with(['menu', 'pelanggan', 'meja', 'waiter'])
                    ->whereDate('tanggal', '>=', $startDate)
                    ->whereDate('tanggal', '<=', $endDate)
                    ->orderBy('tanggal', 'desc')
                    ->get();

        // Calculate total revenue
        $totalItems = $orders->sum('jumlah');

        // Group by status
        $orderStatuses = $orders->groupBy('status')
            ->map(function($group) {
                return [
                    'count' => $group->count(),
                ];
            });

        if ($request->has('download') && $request->download == 'pdf') {
            $pdf = PDF::loadView('pesanan.report-pdf', compact(
                'orders', 
                'totalItems', 
                'orderStatuses',
                'startDate',
                'endDate'
            ));
            return $pdf->download('order-report-'.date('Y-m-d').'.pdf');
        } elseif ($request->has('print') && $request->print == 'pdf') {
            $pdf = PDF::loadView('pesanan.report-pdf', compact(
                'orders', 
                'totalItems', 
                'orderStatuses',
                'startDate',
                'endDate'
            ));
            return $pdf->stream('order-report-'.date('Y-m-d').'.pdf');
        }
        
        return view('pesanan.report', compact(
            'orders', 
            'totalItems', 
            'orderStatuses',
            'startDate',
            'endDate'
        ));
    }
}