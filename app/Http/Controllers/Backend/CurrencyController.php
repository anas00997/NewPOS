<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('currency_view'), 403);

        if ($request->ajax()) {
            $currencies = Currency::latest()->get();

            return DataTables::of($currencies)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('code', fn($data) => $data->code)
                ->addColumn(
                    'symbol',
                    fn($data) => $data->symbol . ($data->active ? ' (Default Currency)' : '')
                )
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                        <button type="button" class="btn bg-gradient-primary btn-flat">Action</button>
                        <button type="button" class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          <a class="dropdown-item" href="' . route('backend.admin.currencies.edit', $data->id) . '">
                            <i class="fas fa-edit"></i> Edit
                          </a>
                          <div class="dropdown-divider"></div>

                          <form action="' . route('backend.admin.currencies.destroy', $data->id) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="dropdown-item" onclick="return confirm(\'Are you sure?\')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                          </form>

                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" onclick="return confirm(\'Set as default currency?\')" href="' . route('backend.admin.currencies.setDefault', $data->id) . '">
                            <i class="fas fa-check"></i> Set Default
                          </a>
                        </div>
                    </div>';
                })
                ->rawColumns(['symbol', 'action'])
                ->toJson();
        }

        return view('backend.settings.currencies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('currency_create'), 403);

        return view('backend.settings.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('currency_create'), 403);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|unique:currencies,code',
            'symbol' => 'required|string',
        ]);

        Currency::create($validated);

        return redirect()
            ->route('backend.admin.currencies.index')
            ->with('success', 'Currency created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_if(Gate::denies('currency_update'), 403);

        $currency = Currency::findOrFail($id);

        return view('backend.settings.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('currency_update'), 403);

        $currency = Currency::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|unique:currencies,code,' . $currency->id,
            'symbol' => 'required|string',
        ]);

        $currency->update($validated);

        return redirect()
            ->route('backend.admin.currencies.index')
            ->with('success', 'Currency updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('currency_delete'), 403);

        $currency = Currency::findOrFail($id);
        $currency->delete();

        return redirect()->back()->with('success', 'Currency Deleted Successfully');
    }

    /**
     * Set default currency.
     */
    public function setDefault($id)
    {
        Currency::where('active', true)->update(['active' => false]);

        $currency = Currency::findOrFail($id);
        $currency->update(['active' => true]);

        Cache::put('default_currency', $currency, 60 * 24);

        return redirect()->back()->with('success', 'Currency Set as Default Successfully');
    }
}
