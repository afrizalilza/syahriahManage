@extends('layouts.main')

@section('title', 'User Pending Approval')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-user-clock"></i> User Pending Approval</h1>
    </div>
    <p>Kelola dan setujui pendaftaran user baru.</p>

    <div class="card">
        <div class="card-header">
            Daftar User Menunggu Persetujuan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-blue">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th style="width: 450px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingUsers as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('user.approve', $user->id) }}" method="POST"
                                        class="form-confirm"
                                        data-message="Yakin ingin menyetujui user {{ $user->name }}?">
                                        @csrf
                                        <div class="d-flex align-items-center">
                                            <select name="role"
                                                class="form-select form-select-sm d-inline w-auto me-2 role-select"
                                                required>
                                                <option value="admin">Admin</option>
                                                <option value="bendahara">Bendahara</option>
                                                <option value="wali_santri">Wali Santri</option>
                                                <option value="santri">Santri</option>
                                            </select>
                                            <select name="unit"
                                                class="form-select form-select-sm d-inline w-auto me-2 unit-select"
                                                style="display:none;">
                                                <option value="" disabled selected>Pilih Unit</option>
                                                <option value="putra">Putra</option>
                                                <option value="putri">Putri</option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada user pending.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to toggle unit select visibility and requirement
            function toggleUnitSelect(roleSelect) {
                const form = roleSelect.closest('form');
                const unitSelect = form.querySelector('.unit-select');

                if (roleSelect.value === 'bendahara') {
                    unitSelect.style.display = 'inline-block';
                    unitSelect.required = true;
                } else {
                    unitSelect.style.display = 'none';
                    unitSelect.required = false;
                    unitSelect.value = ''; // Reset value when hidden
                }
            }

            // Add event listener to all role-select dropdowns
            document.querySelectorAll('.role-select').forEach(function(roleSelect) {
                // Initial check on page load
                toggleUnitSelect(roleSelect);

                // Add change event listener
                roleSelect.addEventListener('change', function() {
                    toggleUnitSelect(this);
                });
            });
        });
    </script>
@endpush
