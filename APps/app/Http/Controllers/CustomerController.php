<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(Customer::latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|string|unique:customers,customer_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'country' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        if (empty($validated['customer_id'])) {
            $validated['customer_id'] = (string) Str::uuid();
        }

        $customer = Customer::create($validated);
        return response()->json($customer, 201);
    }

    public function show(string $customerId)
    {
        $customer = Customer::where('customer_id', $customerId)->firstOrFail();
        return response()->json($customer);
    }

    public function update(Request $request, string $customerId)
    {
        $customer = Customer::where('customer_id', $customerId)->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:customers,email,' . $customer->customer_id . ',customer_id',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'country' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        $customer->update($validated);
        return response()->json($customer);
    }

    public function destroy(string $customerId)
    {
        $customer = Customer::where('customer_id', $customerId)->firstOrFail();
        $customer->delete();
        return response()->json(null, 204);
    }
}


