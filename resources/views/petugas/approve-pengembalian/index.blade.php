<x-app-layout title="Penyetujuan Pengembalian">
    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Menunggu Pengembalian</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $totalMenunggu }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="fas fa-clock text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Sudah Dikembalikan</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $totalKembali }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-check-circle text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Terlambat</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $totalTerlambat }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                    <i class="fas fa-exclamation-triangle text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">User Terblokir</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $totalUserBlokir }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                    <i class="fas fa-ban text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Scanner Button -->
        <div class="row mb-3">
            <div class="col-12">
                <button type="button" class="btn btn-primary" onclick="openQRScanner()">
                    <i class="fas fa-qrcode me-2"></i>Scan QR Code Pengembalian
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="pengembalianTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu"
                    type="button">
                    Menunggu Pengembalian ({{ $peminjamanDiambil->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="selesai-tab" data-bs-toggle="tab" data-bs-target="#selesai" type="button">
                    History ({{ $peminjamanDikembalikan->count() }})
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="pengembalianTabContent">
            <!-- Tab Menunggu Pengembalian -->
            <div class="tab-pane fade show active" id="menunggu" role="tabpanel">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Daftar Peminjaman Menunggu Pengembalian</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kode</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Peminjam</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tanggal Pinjam</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tanggal Rencana Kembali</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status Keterlambatan</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($peminjamanDiambil as $peminjaman)
                                        @php
                                            $isLate = now()->greaterThan($peminjaman->tanggal_pengembalian_rencana);
                                            $daysLate = $isLate
                                                ? now()->diffInDays($peminjaman->tanggal_pengembalian_rencana)
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 ms-3">{{ $loop->iteration }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $peminjaman->kode_peminjaman }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            {{ $peminjaman->peminjam->nama_lengkap ?? $peminjaman->peminjam->nama_lengkap }}
                                                        </h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $peminjaman->peminjam->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $peminjaman->tanggal_pengajuan->format('d/m/Y') }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $peminjaman->tanggal_pengembalian_rencana->format('d/m/Y') }}
                                                </p>
                                            </td>
                                            <td>
                                                @if ($isLate)
                                                    <span class="badge badge-sm bg-gradient-danger">Terlambat
                                                        {{ $daysLate }} hari</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-success">Tepat Waktu</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('petugas.approve-pengembalian.show', $peminjaman->peminjaman_id) }}"
                                                    class="btn btn-sm btn-info mb-0" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <p class="text-xs text-secondary mb-0 py-3">Tidak ada peminjaman yang
                                                    menunggu pengembalian</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab History -->
            <div class="tab-pane fade" id="selesai" role="tabpanel">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>History Pengembalian</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kode</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Peminjam</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tanggal Kembali</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kondisi</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($peminjamanDikembalikan as $peminjaman)
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 ms-3">{{ $loop->iteration }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $peminjaman->kode_peminjaman }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $peminjaman->peminjam->nama_lengkap ?? $peminjaman->peminjam->name }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $peminjaman->pengembalian?->tanggal_kembali_sebenarnya?->format('d/m/Y H:i') ?? '-' }}
                                                </p>
                                            </td>
                                            <td>
                                                @if ($peminjaman->pengembalian)
                                                    @php
                                                        $kondisi = $peminjaman->pengembalian->kondisi->value;
                                                        $badgeClass = match ($kondisi) {
                                                            'baik' => 'bg-gradient-success',
                                                            'rusak_ringan' => 'bg-gradient-warning',
                                                            'rusak_berat' => 'bg-gradient-danger',
                                                            'hilang' => 'bg-gradient-dark',
                                                        };
                                                    @endphp
                                                    <span class="badge badge-sm {{ $badgeClass }}">
                                                        {{ ucwords(str_replace('_', ' ', $kondisi)) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($peminjaman->status === App\Enums\StatusPeminjaman::KEMBALI)
                                                    <span
                                                        class="badge badge-sm bg-gradient-success">Dikembalikan</span>
                                                @elseif($peminjaman->status === App\Enums\StatusPeminjaman::TERLAMBAT)
                                                    <span class="badge badge-sm bg-gradient-danger">Terlambat</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <p class="text-xs text-secondary mb-0 py-3">Belum ada history
                                                    pengembalian</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
                <div class="modal-header" style="flex-shrink: 0;">
                    <h5 class="modal-title">Scan QR Code Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="flex: 1; overflow-y: auto;">
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div id="qr-reader-results" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal untuk Pengembalian -->
    <div class="modal fade" id="returnConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="returnForm">
                    @csrf
                    <input type="hidden" name="peminjaman_id" id="return_peminjaman_id">

                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="returnDetailsContent">
                            <!-- Content will be filled by JavaScript -->
                        </div>

                        <div class="form-group">
                            <label for="kondisi">Kondisi Alat <span class="text-danger">*</span></label>
                            <select class="form-control" id="kondisi" name="kondisi" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"
                                placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Proses Pengembalian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Include html5-qrcode library -->
        <script src="https://unpkg.com/html5-qrcode"></script>

        <script>
            let html5QrcodeScanner = null;
            let isScanning = false;
            let currentPeminjamanData = null;

            function openQRScanner() {
                const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
                modal.show();

                if (!isScanning) {
                    startScanner();
                }
            }

            function startScanner() {
                if (isScanning) return;

                isScanning = true;
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader", {
                        fps: 10,
                        qrbox: 250,
                        rememberLastUsedCamera: true
                    },
                    false
                );

                html5QrcodeScanner.render(onScanSuccess, onScanError);
            }

            function onScanSuccess(decodedText, decodedResult) {
                console.log(`QR Code detected: ${decodedText}`);

                // Pause scanner sementara proses
                if (html5QrcodeScanner && isScanning) {
                    try {
                        html5QrcodeScanner.pause();
                    } catch (e) {
                        console.log("Scanner pause error:", e);
                    }
                }

                // Kirim ke backend untuk validasi
                fetch("{{ route('petugas.pengembalian.scan-proses') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            qr_token: decodedText
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close QR scanner modal
                            const scannerModal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
                            scannerModal.hide();

                            // Simpan data peminjaman
                            currentPeminjamanData = data.data;

                            // Show confirmation modal
                            showReturnConfirmation(data.data);

                            Swal.fire({
                                icon: 'success',
                                title: 'QR Code Valid!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });

                            // Resume scanner
                            if (html5QrcodeScanner && isScanning) {
                                try {
                                    html5QrcodeScanner.resume();
                                } catch (e) {
                                    console.log("Scanner resume error:", e);
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memvalidasi QR Code'
                        });

                        // Resume scanner
                        if (html5QrcodeScanner && isScanning) {
                            try {
                                html5QrcodeScanner.resume();
                            } catch (e) {
                                console.log("Scanner resume error:", e);
                            }
                        }
                    });
            }

            function onScanError(error) {
                // Silent error untuk menghindari spam console
            }

            function showReturnConfirmation(data) {
                // Fill peminjaman data
                document.getElementById('return_peminjaman_id').value = data.id;

                // Build details HTML
                let alatList = data.alat.map(item => `<li>${item.nama} (${item.jumlah} unit)</li>`).join('');

                let lateWarning = '';
                if (data.is_late) {
                    lateWarning = `
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>PERHATIAN:</strong> Pengembalian ini terlambat ${Math.abs(data.days_late)} hari! 
                        User akan diblokir otomatis.
                    </div>
                `;
                }

                let detailsHTML = `
                <div class="mb-3">
                    <p><strong>Kode Peminjaman:</strong> ${data.kode}</p>
                    <p><strong>Peminjam:</strong> ${data.peminjam}</p>
                    <p><strong>Tanggal Pinjam:</strong> ${data.tanggal_pinjam}</p>
                    <p><strong>Tanggal Rencana Kembali:</strong> ${data.tanggal_rencana}</p>
                    <p><strong>Alat yang Dipinjam:</strong></p>
                    <ul>${alatList}</ul>
                </div>
                ${lateWarning}
            `;

                document.getElementById('returnDetailsContent').innerHTML = detailsHTML;

                // Reset form
                document.getElementById('kondisi').value = '';
                document.getElementById('catatan').value = '';

                // Show modal
                const confirmModal = new bootstrap.Modal(document.getElementById('returnConfirmModal'));
                confirmModal.show();
            }

            // Handle return form submission
            document.getElementById('returnForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);

                fetch("{{ route('petugas.pengembalian.proses') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const confirmModal = bootstrap.Modal.getInstance(document.getElementById(
                                'returnConfirmModal'));
                            confirmModal.hide();

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                showConfirmButton: true
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses pengembalian'
                        });
                    });
            });

            // Cleanup scanner when modal is closed
            document.getElementById('qrScannerModal').addEventListener('hidden.bs.modal', function() {
                if (html5QrcodeScanner && isScanning) {
                    try {
                        html5QrcodeScanner.clear();
                        isScanning = false;
                    } catch (e) {
                        console.log("Scanner cleanup error:", e);
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
