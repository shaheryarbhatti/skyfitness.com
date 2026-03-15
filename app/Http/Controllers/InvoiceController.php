<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Member;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        if (request()->ajax()) {
            // Eager load 'member' to avoid N+1 query issues
            $query = Invoice::with('member')->select('invoices.*');

            return datatables()->of($query)
                ->addIndexColumn()
            // Add/Style the Invoice Number column
                ->editColumn('invoice_no', function ($invoice) {
                    return '<span class="badge badge-light-primary text-dark fw-bold">' . $invoice->invoice_no . '</span>';
                })
            // New column for Member Name
                ->addColumn('member_name', function ($invoice) {
                    return $invoice->member ? $invoice->member->full_name : '<span class="text-danger">N/A</span>';
                })
                ->addColumn('fee_display', function ($invoice) {
                    return '<span class="fw-bold">' . CurrencyService::formatAmount($invoice->fee) . '</span>';
                })
                ->addColumn('start_date', function ($invoice) {
                    return $invoice->start_date ? $invoice->start_date->format('d/m/Y') : '-';
                })
                ->addColumn('end_date', function ($invoice) {
                    return $invoice->end_date ? $invoice->end_date->format('d/m/Y') : '-';
                })
                ->addColumn('currency', function ($invoice) {
                    $activeCurrency = CurrencyService::getActiveCurrency();
                    return $activeCurrency ? $activeCurrency->name . ' (' . $activeCurrency->code . ')' : 'Base (RP)';
                })
                ->addColumn('action', function ($invoice) {
                    $edit   = '<a href="' . route('invoices.edit', $invoice->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $delete = '<form action="' . route('invoices.destroy', $invoice->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></button>
                          </form>';
                    return '<div class="action d-flex gap-2">' . $edit . $delete . '</div>';
                })
            // Remember to add 'invoice_no' to rawColumns so the HTML badge renders!
                ->rawColumns(['invoice_no', 'fee_display', 'action', 'member_name'])
                ->make(true);
        }

        return view('invoices.manage');
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        // Get all members for the dropdown
        $members = Member::orderBy('full_name', 'asc')->get();
        return view('invoices.add', compact('members'));
    }

    public function store(Request $request)
    {
        // 1. Generate the custom invoice number
        $request->merge([
            'invoice_no' => 'SFG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
        ]);

        // 2. Validate (include invoice_no here!)
        $validated = $request->validate([
            'invoice_no' => 'required|string', // MUST be here to be included in $validated
            'member_id'  => 'required|exists:members,id',
            'fee'        => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        // 3. Create the record
        Invoice::create($validated);

        return redirect()->route('invoices.manage')
            ->with('success', __('invoice_created_successfully'));
    }

    public function edit(Invoice $invoice)
    {
        $members = Member::orderBy('full_name', 'asc')->get();
        return view('invoices.edit', compact('invoice', 'members'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // 1. Check if invoice_no is missing, if so, generate it
        if (empty($invoice->invoice_no)) {
            $request->merge([
                'invoice_no' => 'SFG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            ]);
        }

        $validated = $request->validate([
            // Only require invoice_no if we are generating/updating it
            'invoice_no' => 'nullable|string',
            'member_id'  => 'required|exists:members,id',
            'fee'        => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.manage')
            ->with('success', __('invoice_updated_successfully'));
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.manage')
            ->with('success', __('invoice_deleted_successfully'));
    }
}
