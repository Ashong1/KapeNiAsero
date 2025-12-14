@extends('layouts.app')

@section('styles')
<style>
    /* --- CORE VARIABLES --- */
    :root { 
        --primary-coffee: #6F4E37; 
        --primary-coffee-dark: #5D4037;
        --border-light: #EFEBE9; 
    }
    
    .card-custom { 
        border: none; border-radius: 20px; background: white; 
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08); overflow: hidden; 
    }
    
    .form-label { 
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase; 
        color: #6D5E57; margin-bottom: 0.5rem; letter-spacing: 0.05em; 
    }
    
    .form-control, .form-select { 
        border-radius: 12px; border: 1px solid var(--border-light); 
        padding: 0.8rem 1rem; font-size: 0.95rem; transition: all 0.2s; 
    }
    
    .form-control:focus, .form-select:focus { 
        border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); 
    }

    /* --- RESPONSIVE BUTTON STYLES --- */
    
    /* SAVE BUTTON */
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

    /* CANCEL BUTTON */
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

    /* Image Upload Style */
    .image-upload-wrapper {
        position: relative; width: 100%; height: 250px; border-radius: 16px; overflow: hidden;
        cursor: pointer; border: 2px dashed var(--border-light); background: #FAFAFA;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        transition: all 0.2s;
    }
    .image-upload-wrapper:hover { border-color: var(--primary-coffee); background: #FFF8E1; }
    .image-upload-wrapper img { width: 100%; height: 100%; object-fit: contain; position: absolute; inset: 0; z-index: 10; }
    .upload-content { position: relative; z-index: 1; text-align: center; }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Create Product</h4>
            <p class="text-secondary small m-0">Add a new item to the menu</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-custom">
                {{-- FIX: p-4 for mobile, p-md-5 for desktop --}}
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            {{-- Left Column: Image --}}
                            <div class="col-md-4">
                                <label class="form-label">Product Image</label>
                                <label class="image-upload-wrapper text-secondary">
                                    <input type="file" name="image" class="d-none" onchange="previewImage(this)">
                                    <div class="upload-content" id="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold">Upload Image</div>
                                        <small class="opacity-75">Max 2MB</small>
                                    </div>
                                    <img id="img-preview" src="#" class="d-none">
                                </label>
                                @error('image') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Right Column: Details --}}
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="name" class="form-control fw-bold @error('name') is-invalid @enderror" placeholder="e.g. Caramel Macchiato" value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Description...">{{ old('description') }}</textarea>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Category</label>
                                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                            <option value="" disabled selected>Select...</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 fw-bold text-muted">₱</span>
                                            <input type="number" name="price" class="form-control border-start-0 ps-0 fw-bold @error('price') is-invalid @enderror" step="0.01" placeholder="0.00" value="{{ old('price') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- FIX: Split Button Layout (w-50 and gap-2) --}}
                        <div class="d-flex gap-2 mt-5 pt-3 border-top">
                            <a href="{{ route('products.index') }}" class="btn btn-cancel-custom w-50">
                                Cancel
                            </a>
                            
                            <button type="submit" class="btn btn-primary-custom w-50">
                                <i class="fas fa-save me-2"></i> Save Product
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('upload-placeholder').classList.add('d-none');
                var img = document.getElementById('img-preview');
                img.src = e.target.result;
                img.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection