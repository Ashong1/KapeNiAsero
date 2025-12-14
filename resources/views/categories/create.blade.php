@extends('layouts.app')

@section('styles')
<style>
    /* --- RESPONSIVE BUTTON STYLES --- */
    .btn-primary-custom { 
        background: #6F4E37 !important; 
        border: none !important; 
        padding: 0.8rem 2rem; 
        border-radius: 12px; 
        font-weight: 600; 
        color: #ffffff !important; 
        box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); 
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-primary-custom:hover,
    .btn-primary-custom:active,
    .btn-primary-custom:focus { 
        background: #4E342E !important; 
        color: #ffffff !important; 
        transform: translateY(-2px); 
        box-shadow: 0 6px 20px rgba(111, 78, 55, 0.4); 
    }

    .btn-cancel-custom {
        background: #f8f9fa !important; 
        border: 1px solid #dee2e6 !important; 
        color: #6c757d !important; 
        padding: 0.8rem 2rem; 
        border-radius: 12px; 
        font-weight: 600; 
        transition: all 0.3s ease; 
        text-decoration: none; 
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-cancel-custom:hover { 
        background: #e2e6ea !important; 
        color: #212529 !important; 
        transform: translateY(-2px); 
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4" style="border: none; border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);">
            
            <div class="text-center mb-4">
                <div class="bg-success-subtle rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                    <i class="fas fa-plus fa-2x text-success"></i>
                </div>
                <h4 class="fw-bold text-dark">New Category</h4>
                <p class="text-secondary small">Create a new menu category</p>
            </div>

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf 
                
                <div class="mb-4">
                    <label class="form-label text-uppercase small fw-bold text-secondary">Category Name</label>
                    <input type="text" name="name" 
                           value="{{ old('name') }}" 
                           class="form-control form-control-lg fs-6" 
                           placeholder="e.g. Iced Coffee"
                           required 
                           style="border-radius: 12px; padding: 0.8rem; border: 1px solid #EFEBE9;">
                </div>

                {{-- FIX: Split Button Layout (w-50 and gap-2) --}}
                <div class="d-flex gap-2 mt-5 pt-3 border-top">
                    <a href="{{ route('categories.index') }}" class="btn btn-cancel-custom w-50">
                       Cancel
                    </a>
                    
                    <button type="submit" class="btn btn-primary-custom w-50">
                        Create Category
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection