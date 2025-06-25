@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Semua Notifikasi</h5>
                    </div>
                    <div class="card-body p-2">
                        <div class="list-group list-group-flush">
                            @forelse ($notifications as $notification)
                                <a href="{{ route('notification.markAsRead', ['id' => $notification->id]) }}"
                                    class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-light fw-bold' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <p class="mb-1 flex-grow-1 text-truncate" style="max-width:180px;">
    <i class="fas fa-user-plus me-2 text-primary"></i>{{ $notification->data['message'] }}
</p>
<small class="text-muted ms-2 text-nowrap">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if (!$notification->read_at)
                                        <small class="text-primary">Klik untuk tandai sudah dibaca & lihat detail.</small>
                                    @endif
                                </a>
                            @empty
                                <div class="list-group-item">
                                    Tidak ada notifikasi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
@media (max-width: 576px) {
    .list-group-item .mb-1 {
        font-size: 0.97rem;
        max-width: 120px !important;
    }
    .list-group-item .text-muted {
        font-size: 0.92rem;
    }
}
</style>
@endsection
