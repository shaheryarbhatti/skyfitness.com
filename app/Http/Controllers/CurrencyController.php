<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the currencies.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Currency::query();

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('status', function ($currency) {
                    return $currency->is_active
                        ? '<span class="badge rounded-pill bg-success">Active</span>'
                        : '<span class="badge rounded-pill bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($currency) {
                    $edit = '<a href="' . route('currencies.edit', $currency->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $delete = '<form action="' . route('currencies.destroy', $currency->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></button>
                          </form>';

                    return '<div class="action d-flex gap-2">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('currencies.manage');
    }

    /**
     * Show the form for creating a new currency.
     */
    public function create()
    {
        return view('currencies.add');
    }

    /**
     * Store a newly created currency in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:currencies,code',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // If setting as active, deactivate others
        if ($request->is_active) {
            Currency::where('is_active', true)->update(['is_active' => false]);
        }

        Currency::create($validated);

        return redirect()->route('currencies.manage')
            ->with('success', 'Currency created successfully.');
    }

    /**
     * Display the specified currency.
     */
    public function show(Currency $currency)
    {
        return view('currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified currency.
     */
    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

    /**
     * Update the specified currency in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:currencies,code,' . $currency->id,
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // If setting as active, deactivate others
        if ($request->is_active && !$currency->is_active) {
            Currency::where('is_active', true)->update(['is_active' => false]);
        }

        $currency->update($validated);

        return redirect()->route('currencies.manage')
            ->with('success', 'Currency updated successfully.');
    }

    /**
     * Remove the specified currency from storage.
     */
    public function destroy(Currency $currency)
    {
        // Prevent deleting active currency
        if ($currency->is_active) {
            return redirect()->back()->with('error', 'Cannot delete active currency.');
        }

        $currency->delete();

        return redirect()->route('currencies.manage')
            ->with('success', 'Currency deleted successfully.');
    }
}
