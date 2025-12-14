@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card card-custom p-4" style="border-radius: 24px; border:none; box-shadow:0 10px 40px rgba(0,0,0,0.05);">
            
            <div class="text-center mb-4">
                <div class="bg-warning-subtle rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                    <i class="fas fa-edit fa-2x text-warning-emphasis"></i>
                </div>
                <h4 class="fw-bold text-dark">Edit Supplier</h4>
            </div>

            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Company Name</label>
                        <input type="text" name="name" class="form-control form-control-lg fs-6" value="{{ old('name', $supplier->name) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control form-control-lg fs-6" value="{{ old('contact_person', $supplier->contact_person) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg fs-6" value="{{ old('email', $supplier->email) }}">
                    </div>

                    {{-- Country --}}
                    <div class="col-md-5">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Country</label>
                        <select class="form-select form-select-lg fs-6" name="country" id="countrySelect" onchange="updatePhonePrefix()">
                            @foreach(['Philippines', 'USA', 'Vietnam', 'Brazil', 'Colombia', 'Indonesia'] as $country)
                                <option value="{{ $country }}" {{ old('country', $supplier->country) == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-7">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light" id="phonePrefix">(+63)</span>
                            <input type="text" 
                                   name="phone_input" 
                                   class="form-control form-control-lg fs-6 @error('phone_input') is-invalid @enderror" 
                                   id="phoneInput"
                                   value="{{ old('phone_input', $phone_input ?? '') }}" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-light w-50 fw-bold py-3" style="border-radius:12px;">Cancel</a>
                    <button type="submit" class="btn btn-warning w-50 fw-bold py-3 text-dark" style="border-radius:12px; border:none; background:#ffc107;">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const countryData = {
        'Philippines': { code: '(+63)', placeholder: '9171234567', maxLength: 10 },
        'USA':         { code: '(+1)',  placeholder: '2025550125', maxLength: 10 },
        'Vietnam':     { code: '(+84)', placeholder: '912345678',  maxLength: 10 },
        'Brazil':      { code: '(+55)', placeholder: '11912345678',maxLength: 11 },
        'Colombia':    { code: '(+57)', placeholder: '3001234567', maxLength: 10 },
        'Indonesia':   { code: '(+62)', placeholder: '81234567890',maxLength: 12 },
    };

    function updatePhonePrefix() {
        const country = document.getElementById('countrySelect').value;
        const data = countryData[country] || { code: '(+??)', placeholder: '', maxLength: 15 };
        document.getElementById('phonePrefix').innerText = data.code;
        document.getElementById('phoneInput').placeholder = data.placeholder;
        document.getElementById('phoneInput').maxLength = data.maxLength;
    }

    document.addEventListener('DOMContentLoaded', updatePhonePrefix);

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Please Check Your Input',
            html: `
                <ul style="text-align: left; list-style: none; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li style="color: #D32F2F;">• {{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endsection