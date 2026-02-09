<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!Gate::allows('customer_view'), 403);

        if ($request->ajax()) {
            $customers = Customer::latest()->get();

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('name', fn ($c) => $c->name)
                ->addColumn('phone', fn ($c) => $c->phone)
                ->addColumn('email_address', fn ($c) => $c->email_address)
                ->addColumn('created_at', fn ($c) => $c->created_at->format('d M, Y'))
                ->addColumn('action', function ($c) {

                    $html = '<div class="btn-group">
                        <button class="btn bg-gradient-primary btn-flat">Action</button>
                        <button class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown"></button>
                        <div class="dropdown-menu">';

                    if (Gate::allows('customer_update')) {
                        $html .= '<a class="dropdown-item" href="' . route('backend.admin.customers.edit', $c->id) . '">
                            <i class="fas fa-edit"></i> Edit
                        </a><div class="dropdown-divider"></div>';
                    }

                    if (Gate::allows('customer_delete')) {
                        $html .= '<form method="POST" action="' . route('backend.admin.customers.destroy', $c->id) . '">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="dropdown-item" onclick="return confirm(\'Are you sure?\')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form><div class="dropdown-divider"></div>';
                    }

                    if (Gate::allows('customer_sales')) {
                        $html .= '<a class="dropdown-item" href="' . route('backend.admin.customers.orders', $c->id) . '">
                            <i class="fas fa-cart-plus"></i> Sales
                        </a>';
                    }

                    return $html . '</div></div>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('backend.customers.index');
    }

    public function create()
    {
        abort_if(!Gate::allows('customer_create'), 403);
        return view('backend.customers.create');
    }

    public function store(Request $request)
    {
        abort_if(!Gate::allows('customer_create'), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:11',
            'email_address' => 'nullable|string|max:255',
            'dob' => 'nullable|string|max:255',
        ]);

        Customer::create($request->only([
            'name', 'phone', 'email_address', 'dob'
        ]));

        return to_route('backend.admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        abort_if(!Gate::allows('customer_update'), 403);

        $customer = Customer::findOrFail($id);
        return view('backend.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!Gate::allows('customer_update'), 403);

        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:11',
            'email_address' => 'nullable|string|max:255',
            'dob' => 'nullable|string|max:255',
        ]);

        $customer->update($request->only([
            'name', 'phone', 'email_address', 'dob'
        ]));

        return to_route('backend.admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        abort_if(!Gate::allows('customer_delete'), 403);

        Customer::findOrFail($id)->delete();

        return to_route('backend.admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function orders($id)
    {
        abort_if(!Gate::allows('customer_sales'), 403);

        $customer = Customer::findOrFail($id);
        $orders = $customer->orders()->paginate(100);

        return view('backend.orders.index', compact('orders'));
    }
}
