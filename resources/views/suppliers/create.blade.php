@extends('layouts.app')

@section('styles')
<style>
    /* VARIABLES */
    :root { --primary-coffee: #6F4E37; --border-light: #EFEBE9; }
    
    /* CARD & INPUTS */
    .card-custom { border: none; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); background: white; }
    .form-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #8D6E63; margin-bottom: 0.4rem; letter-spacing: 0.05em; }
    .form-control, .form-select { border-radius: 10px; padding: 0.7rem 1rem; border: 1px solid var(--border-light); font-size: 0.95rem; }
    .form-control:focus, .form-select:focus { border-color: var(--primary-coffee); box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1); }
    .input-group-text { background-color: #f8f9fa; border-color: var(--border-light); color: #6c757d; font-weight: 600; }

    /* STRICT BUTTON STYLES (Previously Fixed) */
    .btn-primary-custom { 
        background: #6F4E37 !important; border: none !important; padding: 0.8rem 2rem; border-radius: 12px; font-weight: 600; color: #ffffff !important; box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2); transition: all 0.3s ease; 
    }
    .btn-primary-custom:hover { background: #4E342E !important; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(111, 78, 55, 0.4); }

    .btn-cancel-custom {
        background: #f8f9fa !important; border: 1px solid #dee2e6 !important; color: #6c757d !important; padding: 0.8rem 2rem; border-radius: 12px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-block; text-align: center;
    }
    .btn-cancel-custom:hover { background: #e2e6ea !important; color: #212529 !important; transform: translateY(-2px); }
</style>
@endsection

@section('content')
<div class="container" style="max-width: 700px;">
    
    <div class="card card-custom">
        <div class="card-body p-5">
            <h4 class="fw-bold mb-4">Register New Supplier</h4>
            
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                
                {{-- Company Name --}}
                <div class="mb-4">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="name" class="form-control fw-bold" placeholder="e.g. Beans & Grains Co." value="{{ old('name') }}" required autofocus>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    {{-- COUNTRY & PHONE --}}
                    <div class="col-md-5">
                        <label class="form-label">Country</label>
                        <select class="form-select" name="country" id="countrySelect" onchange="updatePhonePrefix()">
                            <option value="Philippines" selected>Philippines</option>
                            <option value="USA">USA</option>
                            <option value="Vietnam">Vietnam</option>
                            <option value="Brazil">Brazil</option>
                            <option value="Colombia">Colombia</option>
                            <option value="Indonesia">Indonesia</option>
                        </select>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text" id="phonePrefix">(+63)</span>
                            <input type="text" 
                                   name="phone_input" 
                                   class="form-control @error('phone_input') is-invalid @enderror" 
                                   id="phoneInput"
                                   value="{{ old('phone_input') }}" 
                                   oninput="validatePhoneInput(this)"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-cancel-custom">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom"><i class="fas fa-save me-2"></i> Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // UPDATED VALIDATION DATA
    const countryData = {
        'Philippines': { code: '(+63)', placeholder: '9171234567', maxLength: 10 },
        'USA':         { code: '(+1)',  placeholder: '2025550125', maxLength: 10 },
        'Vietnam':     { code: '(+84)', placeholder: '912345678',  maxLength: 10 }, // 9-10 allowed
        'Brazil':      { code: '(+55)', placeholder: '11912345678',maxLength: 11 }, // 10-11 allowed
        'Colombia':    { code: '(+57)', placeholder: '3001234567', maxLength: 10 },
        'Indonesia':   { code: '(+62)', placeholder: '81234567890',maxLength: 12 }, // 9-12 allowed
    };

    function updatePhonePrefix() {
        const country = document.getElementById('countrySelect').value;
        const data = countryData[country] || { code: '(+??)', placeholder: 'Number', maxLength: 15 };
        
        document.getElementById('phonePrefix').innerText = data.code;
        document.getElementById('phoneInput').placeholder = data.placeholder;
        document.getElementById('phoneInput').maxLength = data.maxLength;
    }

    function validatePhoneInput(input) {
        input.value = input.value.replace(/[^0-9]/g, ''); // Numbers only
    }

    document.addEventListener('DOMContentLoaded', updatePhonePrefix);

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Please Check Your Input',
            html: `
                <ul style="text-align: left; list-style: none; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li style="color: #D32F2F; margin-bottom: 5px;">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ $error }}
                        </li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#6F4E37'
        });
    @endif
</script>
@endsection