@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-custom p-4">
            
            <div class="text-center mb-4">
                {{-- Changed Icon and Color to Green/Primary for "Create" context --}}
                <div class="bg-success-subtle rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                    <i class="fas fa-plus fa-2x text-success"></i>
                </div>
                <h4 class="fw-bold text-dark">New Category</h4>
                <p class="text-secondary small">Create a new menu category</p>
            </div>

            {{-- FIXED: Route points to 'store', removed ID parameter --}}
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf 
                {{-- REMOVED: @method('PUT') is only for updates --}}
                
                <div class="mb-4">
                    <label class="form-label text-uppercase small fw-bold text-secondary">Category Name</label>
                    {{-- FIXED: Value uses old('name') to keep input if validation fails, instead of $category->name --}}
                    <input type="text" name="name" 
                           value="{{ old('name') }}" 
                           class="form-control form-control-lg fs-6" 
                           placeholder="e.g. Iced Coffee"
                           required 
                           style="border-radius: 12px; padding: 0.8rem;">
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('categories.index') }}" 
                       class="btn btn-light w-50 text-secondary fw-bold d-flex align-items-center justify-content-center" 
                       style="border-radius: 12px; padding: 0.8rem;">
                       Cancel
                    </a>
                    
                    {{-- Changed button color to Coffee/Primary for consistency --}}
                    <button type="submit" 
                            class="btn btn-primary-coffee w-50 fw-bold d-flex align-items-center justify-content-center text-white" 
                            style="border-radius: 12px; padding: 0.8rem; background: var(--primary-coffee); border: none;">
                        Create
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection