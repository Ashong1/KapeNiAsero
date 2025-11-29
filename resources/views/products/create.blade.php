@extends('layouts.app')

@section('styles')
<style>
    /* Reusing the Premium Design Tokens */
    :root {
        --primary-coffee: #6F4E37; 
        --surface-glass: rgba(255, 255, 255, 0.92);
        --text-dark: #2C1810; 
        --text-secondary: #6D5E57; 
        --border-light: #EFEBE9; 
    }

    .card-custom { 
        border: none; border-radius: 20px; background: white; 
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08); overflow: hidden; 
    }
    
    .form-label { 
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase; 
        color: var(--text-secondary); margin-bottom: 0.5rem; letter-spacing: 0.05em; 
    }
    .form-control, .form-select { 
        border-radius: 12px; border: 1px solid var(--border-light); 
        padding: 0.8rem 1rem; font-size: 0.95rem; transition: all 0.2s; 
    }
    .form-control:focus, .form-select:focus { 
        border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); 
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-coffee) 0%, #3E2723 100%);
        color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 12px; font-weight: 600;
        box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); transition: transform 0.2s;
    }
    .btn-primary-custom:hover { transform: translateY(-2px); color: white; }

    /* Modern Upload Box */
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
        <a href="{{ route('products.index') }}" class="btn btn-light border fw-bold text-secondary rounded-pill px-4">
            <i class="fas fa-times me-2"></i> Cancel
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-custom">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-5">
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

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="name" class="form-control fw-bold @error('name') is-invalid @enderror" placeholder="e.g. Caramel Macchiato" value="{{ old('name') }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Description of the drink...">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 fw-bold text-muted">₱</span>
                                            <input type="number" name="price" class="form-control border-start-0 ps-0 fw-bold @error('price') is-invalid @enderror" step="0.01" placeholder="0.00" value="{{ old('price') }}" required>
                                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center mt-5 pt-4 border-top">
                            <span class="text-muted small me-3"><i class="fas fa-info-circle"></i> You can add ingredients after saving.</span>
                            <button type="submit" class="btn btn-primary-custom px-5">
                                <i class="fas fa-arrow-right me-2"></i> Next: Add Recipe
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