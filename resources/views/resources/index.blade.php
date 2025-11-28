@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white"><i class="fas fa-truck me-2"></i>Supplier Management</h2>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Supplier
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Company Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact_person ?? '-' }}</td>
                        <td><a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a></td>
                        <td>{{ $supplier->phone ?? '-' }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($suppliers->isEmpty())
                <div class="p-4 text-center text-muted">No suppliers found.</div>
            @endif
        </div>
    </div>
</div>
@endsection