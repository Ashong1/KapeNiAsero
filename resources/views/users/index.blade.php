@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Staff Management</h2>
            <p class="text-secondary small mb-0">Manage employee accounts and permissions</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary-coffee rounded-4 px-4 shadow-sm">
            <i class="fas fa-user-plus me-2"></i> Add New User
        </a>
    </div>

    <div class="card card-custom">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-secondary text-uppercase small">Name</th>
                        <th class="text-secondary text-uppercase small">Email</th>
                        <th class="text-secondary text-uppercase small">Role</th>
                        <th class="text-secondary text-uppercase small">Date Added</th>
                        <th class="text-end pe-4 text-secondary text-uppercase small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary-coffee fw-bold me-3" style="width: 40px; height: 40px;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">
                                        {{ $user->name }}
                                        @if(Auth::id() === $user->id)
                                            <span class="badge bg-primary-coffee ms-2" style="font-size: 0.65rem;">YOU</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-10">
                                    <i class="fas fa-shield-alt me-1"></i> Admin
                                </span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-10">
                                    <i class="fas fa-user me-1"></i> Staff
                                </span>
                            @endif
                        </td>
                        <td class="text-secondary small">
                            <i class="far fa-calendar me-1"></i>
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="text-end pe-4">
                            {{-- FLEX CONTAINER: Centers items vertically --}}
                            <div class="d-flex justify-content-end align-items-center">
                                
                                {{-- LOGIC START --}}

                                @if(Auth::id() === $user->id)
                                    {{-- 1. IT IS ME --}}
                                    <a href="{{ route('users.edit', $user->id) }}" title="Edit Profile" 
                                       style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: none; background-color: #F3F4F6; color: #4B5563; margin-left: 4px; text-decoration: none;">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" disabled title="Cannot delete yourself"
                                            style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: none; background-color: #F3F4F6; color: #9CA3AF; margin-left: 4px; opacity: 0.5; cursor: not-allowed;">
                                        <i class="fas fa-trash-can"></i>
                                    </button>

                                @elseif($user->role === 'admin')
                                    {{-- 2. OTHER ADMIN --}}
                                    <button type="button" disabled title="Restricted: Cannot edit other Admins"
                                            style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: none; background-color: #F3F4F6; color: #9CA3AF; margin-left: 4px; cursor: not-allowed;">
                                        <i class="fas fa-lock"></i>
                                    </button>

                                @else
                                    {{-- 3. STAFF ACTION BUTTONS --}}
                                    
                                    {{-- EDIT BUTTON --}}
                                    <a href="{{ route('users.edit', $user->id) }}" title="Edit User" 
                                       style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: none; background-color: #F3F4F6; color: #4B5563; margin-left: 4px; text-decoration: none; transition: all 0.2s;"
                                       onmouseover="this.style.backgroundColor='#6F4E37'; this.style.color='white';"
                                       onmouseout="this.style.backgroundColor='#F3F4F6'; this.style.color='#4B5563';">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    
                                    {{-- DELETE FORM & BUTTON --}}
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-form" style="display: inline-block; margin: 0; padding: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Delete User"
                                                style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: 0 !important; outline: none !important; background-color: #FEF2F2; color: #DC2626; margin-left: 4px; cursor: pointer; transition: all 0.2s;"
                                                onmouseover="this.style.backgroundColor='#DC2626'; this.style.color='white';"
                                                onmouseout="this.style.backgroundColor='#FEF2F2'; this.style.color='#DC2626';">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- LOGIC END --}}

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all forms that have the class 'delete-form'
        const deleteForms = document.querySelectorAll('.delete-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete User?',
                    text: "This action cannot be undone. The staff member will lose access immediately.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#EFEBE9',
                    cancelButtonText: '<span style="color: #6F4E37; font-weight: 600;">Cancel</span>',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection

@endsection