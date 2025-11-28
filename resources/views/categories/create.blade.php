@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-custom p-4">
            
            <div class="text-center mb-4">
                <div class="bg-warning-subtle rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                    <i class="fas fa-edit fa-2x text-warning-emphasis"></i>
                </div>
                <h4 class="fw-bold text-dark">Edit Category</h4>
                <p class="text-secondary small">Update category details</p>
            </div>

            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf 
                @method('PUT')
                
                <div class="mb-4">
                    <label class="form-label text-uppercase small fw-bold text-secondary">Category Name</label>
                    <input type="text" name="name" 
                           value="{{ $category->name }}" 
                           class="form-control form-control-lg fs-6" 
                           required 
                           style="border-radius: 12px; padding: 0.8rem;">
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('categories.index') }}" 
                       class="btn btn-light w-50 text-secondary fw-bold d-flex align-items-center justify-content-center" 
                       style="border-radius: 12px; padding: 0.8rem;">
                       Cancel
                    </a>
                    
                    <button type="submit" 
                            class="btn btn-warning w-50 fw-bold d-flex align-items-center justify-content-center text-dark" 
                            style="border-radius: 12px; padding: 0.8rem; background-color: #ffc107; border: none;">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection