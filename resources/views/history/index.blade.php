
@extends('layouts.admin')

@section('title', 'Riwayat Aktivitas')

@section('content')

<div class="card shadow-sm border-0">
    <div class="card-body">

        <h5 class="mb-3">Log Aktivitas Administrator</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Admin</th>
                        <th>Aktivitas</th>
                        <th>Deskripsi</th>
                        <th>Waktu</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $loop->index }}</td>

                            <td>
                                {{ $log->user->name ?? 'Pengguna tidak tersedia' }}
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $log->aktivitas }}
                                </span>
                            </td>

                            <td>{{ $log->deskripsi }}</td>

                            <td>
                                {{ $log->created_at->format('d-m-Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada riwayat aktivitas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{ $logs->links() }}

    </div>
</div>

@endsection
