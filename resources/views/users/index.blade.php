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
                            <div class="d-flex justify-content-end gap-2">
                                
                                {{-- LOGIC START --}}

                                @if(Auth::id() === $user->id)
                                    {{-- 1. IT IS ME: Show Edit, Disable Delete --}}
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light text-primary-coffee border-0" title="Edit Profile">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <button disabled class="btn btn-sm btn-light text-muted border-0 opacity-25" style="cursor: not-allowed;">
                                        <i class="fas fa-trash-can"></i>
                                    </button>

                                @elseif($user->role === 'admin')
                                    {{-- 2. OTHER ADMIN: Lock Everything --}}
                                    <button disabled class="btn btn-sm btn-light text-secondary border-0 opacity-50" title="Restricted: Cannot edit other Admins" style="cursor: not-allowed;">
                                        <i class="fas fa-lock"></i>
                                    </button>

                                @else
                                    {{-- 3. STAFF: Full Control --}}
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light text-primary-coffee border-0" title="Edit User">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    
                                    {{-- UPDATED DELETE FORM: No onsubmit, added 'delete-form' class --}}
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border-0" title="Delete User">
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
        // Select all forms with the 'delete-form' class
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Prevent the default form submission (browser alert)
                e.preventDefault();

                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Delete User?',
                    text: "This action cannot be undone. The staff member will lose access immediately.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F', // Danger Red
                    cancelButtonColor: '#EFEBE9',  // Light Gray
                    cancelButtonText: '<span style="color: #6F4E37; font-weight: 600;">Cancel</span>',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true, // Moves confirm to the right side
                    focusCancel: true // Focus on cancel by default to prevent accidents
                }).then((result) => {
                    // If user clicked 'Yes'
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