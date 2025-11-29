@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary-coffee btn-action shadow-sm"><i class="fas fa-plus-circle"></i> Add Supplier</a>
    </div>

    <div class="card card-custom">
        <div class="card-header bg-white border-bottom p-4">
            <h5 class="fw-bold m-0 text-dark"><i class="fas fa-truck me-2 text-primary-coffee"></i> Supplier Database</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Company</th>
                        <th class="text-secondary text-uppercase small fw-bold">Contact Person</th>
                        <th class="text-secondary text-uppercase small fw-bold">Email</th>
                        <th class="text-secondary text-uppercase small fw-bold">Phone</th>
                        <th class="text-end pe-4 text-secondary text-uppercase small fw-bold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supp)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            <span class="d-inline-block bg-light rounded p-2 me-2 text-primary-coffee shadow-sm"><i class="fas fa-building"></i></span>
                            {{ $supp->name }}
                        </td>
                        <td class="text-secondary">{{ $supp->contact_person }}</td>
                        <td><a href="mailto:{{ $supp->email }}" class="text-decoration-none text-secondary hover-primary"><i class="far fa-envelope me-1"></i> {{ $supp->email }}</a></td>
                        <td class="text-secondary font-monospace">{{ $supp->phone }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('suppliers.edit', $supp->id) }}" class="btn btn-sm btn-light text-primary me-1 border" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('suppliers.destroy', $supp->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                {{-- UPDATED --}}
                                <button class="btn btn-sm btn-light text-danger border" title="Delete" onclick="confirmDelete(event)"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted"><div class="opacity-50 mb-2"><i class="fas fa-truck-loading fa-3x"></i></div><p class="mb-0 fw-medium">No suppliers registered.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection