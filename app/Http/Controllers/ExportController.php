<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\TestOrder;
use App\Models\Appointment;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    /**
     * Export patients to CSV
     */
    public function exportPatients(Request $request)
    {
        $labId = currentLabId();
        $query = Patient::where('lab_id', $labId);
        
        // Apply filters if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        $patients = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'patients_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($patients) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Phone', 'Email', 'Age', 'Gender', 'Address', 'Created At']);
            
            // Add data rows
            foreach ($patients as $patient) {
                fputcsv($file, [
                    $patient->id,
                    $patient->name,
                    $patient->phone,
                    $patient->email ?? '',
                    $patient->age ?? '',
                    $patient->gender ?? '',
                    $patient->address ?? '',
                    $patient->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export test orders to CSV
     */
    public function exportTestOrders(Request $request)
    {
        $labId = currentLabId();
        $query = TestOrder::where('lab_id', $labId)
            ->with(['patient', 'test', 'package']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->latest()->get();
        
        $filename = 'test_orders_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Order ID', 'Patient', 'Test/Package', 'Status', 'Amount', 'Date']);
            
            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    $order->patient->name,
                    $order->package ? $order->package->name : $order->test->name,
                    ucfirst($order->status),
                    $order->bill ? '₹' . number_format($order->bill->total_amount, 2) : 'N/A',
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export appointments to CSV
     */
    public function exportAppointments(Request $request)
    {
        $query = Appointment::forCurrentLab()
            ->with(['patient', 'doctor']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')->get();
        
        $filename = 'appointments_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Patient', 'Date', 'Time', 'Status', 'Booking Source', 'Doctor']);
            
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    '#' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
                    $appointment->patient->name,
                    $appointment->appointment_date->format('Y-m-d'),
                    $appointment->appointment_time,
                    ucfirst($appointment->status),
                    ucfirst(str_replace('_', ' ', $appointment->booking_source)),
                    $appointment->doctor ? $appointment->doctor->name : 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export bills to CSV
     */
    public function exportBills(Request $request)
    {
        $labId = currentLabId();
        $query = Bill::where('lab_id', $labId)
            ->with(['patient', 'payments']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $bills = $query->latest()->get();
        
        $filename = 'bills_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($bills) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Bill Number', 'Patient', 'Total Amount', 'Paid', 'Pending', 'Status', 'Date']);
            
            foreach ($bills as $bill) {
                $paid = $bill->payments->sum('amount');
                $pending = $bill->total_amount - $paid;
                
                fputcsv($file, [
                    $bill->bill_number,
                    $bill->patient->name,
                    '₹' . number_format($bill->total_amount, 2),
                    '₹' . number_format($paid, 2),
                    '₹' . number_format($pending, 2),
                    ucfirst($bill->status),
                    $bill->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
