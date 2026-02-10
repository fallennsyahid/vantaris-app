<x-app-layout title="Detail Pengembalian">
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('petugas.approve-pengembalian.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Detail Peminjaman</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-sm mb-1"><strong>Kode Peminjaman:</strong></p>
                                <p class="text-sm text-secondary">{{ $peminjaman->kode_peminjaman }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm mb-1"><strong>Status:</strong></p>
                                @php
                                    $badgeClass = match ($peminjaman->status) {
                                        App\Enums\StatusPeminjaman::PENDING => 'bg-gradient-warning',
                                        App\Enums\StatusPeminjaman::DISETUJUI => 'bg-gradient-info',
                                        App\Enums\StatusPeminjaman::DIAMBIL => 'bg-gradient-primary',
                                        App\Enums\StatusPeminjaman::KEMBALI => 'bg-gradient-success',
                                        App\Enums\StatusPeminjaman::TERLAMBAT => 'bg-gradient-danger',
                                        App\Enums\StatusPeminjaman::DITOLAK => 'bg-gradient-dark',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($peminjaman->status->value) }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-sm mb-1"><strong>Tanggal Pengajuan:</strong></p>
                                <p class="text-sm text-secondary">{{ $peminjaman->tanggal_pengajuan->format('d/m/Y') }}
                                </p>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm mb-1"><strong>Tanggal Rencana Kembali:</strong></p>
                                <p class="text-sm text-secondary">
                                    {{ $peminjaman->tanggal_pengembalian_rencana->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        @if ($peminjaman->pengembalian)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="text-sm mb-1"><strong>Tanggal Kembali Sebenarnya:</strong></p>
                                    <p class="text-sm text-secondary">
                                        {{ $peminjaman->pengembalian->tanggal_kembali_sebenarnya->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-sm mb-1"><strong>Kondisi:</strong></p>
                                    @php
                                        $kondisi = $peminjaman->pengembalian->kondisi->value;
                                        $kondisiBadge = match ($kondisi) {
                                            'baik' => 'bg-gradient-success',
                                            'rusak_ringan' => 'bg-gradient-warning',
                                            'rusak_berat' => 'bg-gradient-danger',
                                            'hilang' => 'bg-gradient-dark',
                                        };
                                    @endphp
                                    <span
                                        class="badge {{ $kondisiBadge }}">{{ ucwords(str_replace('_', ' ', $kondisi)) }}</span>
                                </div>
                            </div>

                            @if ($peminjaman->pengembalian->catatan)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p class="text-sm mb-1"><strong>Catatan:</strong></p>
                                        <p class="text-sm text-secondary">{{ $peminjaman->pengembalian->catatan }}</p>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <p class="text-sm mb-1"><strong>Alasan Meminjam:</strong></p>
                                <p class="text-sm text-secondary">{{ $peminjaman->alasan_meminjam }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header pb-0">
                        <h6>Daftar Alat</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nama Alat</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kategori</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($peminjaman->details as $detail)
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 ms-3">{{ $loop->iteration }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $detail->alat->nama_alat }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ $detail->alat->kategori->nama_kategori }}</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $detail->jumlah }} unit</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Informasi Peminjam</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @php
                                $avatarUrl = \Laravolt\Avatar\Facade::create(
                                    $peminjaman->user->nama_lengkap ?? ($peminjaman->user->name ?? 'User'),
                                )
                                    ->setDimension(80)
                                    ->setFontSize(32)
                                    ->toBase64();
                            @endphp
                            <img src="{{ $avatarUrl }}" class="rounded-circle me-3" alt="Avatar"
                                style="width: 60px; height: 60px;">
                            <div>
                                <h6 class="mb-0">{{ $peminjaman->user->nama_lengkap ?? $peminjaman->user->name }}
                                </h6>
                                <p class="text-xs text-secondary mb-0">{{ $peminjaman->user->email }}</p>
                            </div>
                        </div>

                        <hr class="horizontal dark my-3">

                        <div class="mb-2">
                            <p class="text-sm mb-1"><strong>Role:</strong></p>
                            <span
                                class="badge badge-sm bg-gradient-info">{{ ucfirst($peminjaman->user->role->value) }}</span>
                        </div>

                        @if ($peminjaman->user->status_blokir)
                            <div class="alert alert-danger mt-3">
                                <p class="text-xs mb-1"><strong>Status Akun:</strong></p>
                                <p class="text-xs mb-1">User ini sedang <strong>TERBLOKIR</strong></p>
                                @if ($peminjaman->user->durasi_blokir)
                                    <p class="text-xs mb-0">Hingga:
                                        {{ \Carbon\Carbon::parse($peminjaman->user->durasi_blokir)->format('d M Y, H:i') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
