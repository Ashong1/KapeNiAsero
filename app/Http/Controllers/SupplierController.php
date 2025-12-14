<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'contact_person' => 'nullable|string|max:255',
            'country' => 'required|string',
            'phone_input' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    // Remove any accidental whitespace
                    $val = trim($value);
                    
                    switch ($request->country) {
                        case 'Philippines':
                            // Must be 10 digits, starting with 9 (since +63 is prefix)
                            if (!preg_match('/^9\d{9}$/', $val)) {
                                $fail('For Philippines, please enter 10 digits starting with 9 (e.g., 9171234567).');
                            }
                            break;

                        case 'USA':
                            // Must be exactly 10 digits
                            if (!preg_match('/^\d{10}$/', $val)) {
                                $fail('For USA, please enter exactly 10 digits.');
                            }
                            break;

                        case 'Vietnam':
                            // 9 to 10 digits
                            if (!preg_match('/^\d{9,10}$/', $val)) {
                                $fail('For Vietnam, please enter 9 or 10 digits.');
                            }
                            break;

                        case 'Brazil':
                            // 10 to 11 digits
                            if (!preg_match('/^\d{10,11}$/', $val)) {
                                $fail('For Brazil, please enter 10 or 11 digits.');
                            }
                            break;

                        case 'Colombia':
                            // Exactly 10 digits
                            if (!preg_match('/^\d{10}$/', $val)) {
                                $fail('For Colombia, please enter exactly 10 digits.');
                            }
                            break;

                        case 'Indonesia':
                            // 9 to 12 digits
                            if (!preg_match('/^\d{9,12}$/', $val)) {
                                $fail('For Indonesia, please enter between 9 and 12 digits.');
                            }
                            break;

                        default:
                            if (strlen($val) < 7 || strlen($val) > 15) {
                                $fail('Please enter a valid phone number format.');
                            }
                    }
                },
            ],
        ]);

        // Format and Save
        $prefix = $this->getCountryCode($request->country);
        $fullPhone = "({$prefix}) " . $request->phone_input;

        Supplier::create([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'country' => $request->country,
            'phone' => $fullPhone,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier registered successfully!');
    }

    public function edit(Supplier $supplier)
    {
        // Extract raw number for editing
        $phone_input = $supplier->phone;
        if (preg_match('/\)\s+(.*)$/', $supplier->phone, $matches)) {
            $phone_input = $matches[1];
        }

        return view('suppliers.edit', compact('supplier', 'phone_input'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'contact_person' => 'nullable|string|max:255',
            'country' => 'required|string',
            'phone_input' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    $val = trim($value);
                    switch ($request->country) {
                        case 'Philippines':
                            if (!preg_match('/^9\d{9}$/', $val)) $fail('For Philippines, please enter 10 digits starting with 9.');
                            break;
                        case 'USA':
                            if (!preg_match('/^\d{10}$/', $val)) $fail('For USA, please enter exactly 10 digits.');
                            break;
                        case 'Vietnam':
                            if (!preg_match('/^\d{9,10}$/', $val)) $fail('For Vietnam, please enter 9 or 10 digits.');
                            break;
                        case 'Brazil':
                            if (!preg_match('/^\d{10,11}$/', $val)) $fail('For Brazil, please enter 10 or 11 digits.');
                            break;
                        case 'Colombia':
                            if (!preg_match('/^\d{10}$/', $val)) $fail('For Colombia, please enter exactly 10 digits.');
                            break;
                        case 'Indonesia':
                            if (!preg_match('/^\d{9,12}$/', $val)) $fail('For Indonesia, please enter between 9 and 12 digits.');
                            break;
                    }
                },
            ],
        ]);

        $prefix = $this->getCountryCode($request->country);
        $fullPhone = "({$prefix}) " . $request->phone_input;

        $supplier->update([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'country' => $request->country,
            'phone' => $fullPhone,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier removed.');
    }

    private function getCountryCode($country)
    {
        $codes = [
            'Philippines' => '+63',
            'USA' => '+1',
            'Vietnam' => '+84',
            'Brazil' => '+55',
            'Colombia' => '+57',
            'Indonesia' => '+62',
        ];
        return $codes[$country] ?? '';
    }
}