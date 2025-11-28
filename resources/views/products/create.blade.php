@extends('layouts.app')

@section('styles')
<style>
    /* Specific Styles for Create Product Page to keep "Old Design" */
    .form-label { 
        font-size: 0.75rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        color: var(--text-secondary); 
        margin-bottom: 0.5rem; 
        letter-spacing: 0.05em; 
    }
    
    .form-control, .form-select { 
        border-radius: 12px; 
        border: 1px solid var(--border-light); 
        padding: 0.8rem 1rem; 
        font-size: 0.95rem; 
        transition: all 0.2s; 
    }
    
    .form-control:focus, .form-select:focus { 
        border-color: var(--primary-coffee); 
        box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); 
    }
    
    /* Upload Box Design */
    .upload-box {
        border: 2px dashed var(--border-light); 
        border-radius: 16px; 
        height: 250px;
        display: flex; 
        flex-direction: column; 
        justify-content: center; 
        align-items: center;
        background: #FAFAFA; 
        transition: all 0.2s; 
        cursor: pointer; 
        position: relative;
        overflow: hidden;
    }
    
    .upload-box:hover { 
        border-color: var(--accent-gold); 
        background: #FFF8E1; 
    }
    
    .upload-box i { 
        font-size: 3rem; 
        color: #D7CCC8; 
        margin-bottom: 1rem; 
    }

    /* Buttons specific to this form */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-coffee) 0%, #3E2723 100%);
        color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 12px; font-weight: 600;
        box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); transition: transform 0.2s;
    }
    .btn-primary-custom:hover { transform: translateY(-2px); color: white; }
    
    .btn-outline-custom {
        border: 1px solid var(--border-light); background: white; color: var(--text-secondary);
        padding: 0.8rem 1.5rem; border-radius: 12px; font-weight: 600; text-decoration: none;
        display: inline-block;
    }
    .btn-outline-custom:hover { background: #F5F5F5; color: var(--text-dark); }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom">
            
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold text-dark">Product Information</h5>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold text-secondary">
                    <i class="fas fa-times me-1"></i> Close
                </a>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5">
                        <div class="col-md-4">
                            <label class="form-label">Product Image</label>
                            <label class="upload-box w-100">
                                <input type="file" name="image" class="d-none" onchange="previewImage(this)">
                                <div id="upload-placeholder" class="text-center">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="fw-bold text-secondary">Click to Upload</div>
                                    <small class="text-muted">JPG, PNG (Max 2MB)</small>
                                </div>
                                <img id="img-preview" src="#" class="d-none w-100 h-100 object-fit-cover" style="position:absolute; inset:0;">
                            </label>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-4">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="name" class="form-control form-control-lg fw-bold" placeholder="e.g. Iced Caramel Macchiato" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Describe the taste profile..."></textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="" disabled selected>Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 fw-bold text-muted">₱</span>
                                        <input type="number" name="price" class="form-control border-start-0 ps-0 fw-bold text-dark" step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                        <a href="{{ route('products.index') }}" class="btn-outline-custom">Cancel</a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save me-2"></i> Save Product
                        </button>
                    </div>
                </form>
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