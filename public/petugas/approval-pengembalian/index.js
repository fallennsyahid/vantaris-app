console.log('External script loaded!');

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM Content Loaded!');

    let html5QrcodeScanner = null;
    let isScanning = false;
    let currentPeminjamanData = null;

    // Get CSRF token and routes from window object (set by blade)
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const scanProsesUrl = window.appRoutes.scanProses;
    const prosesUrl = window.appRoutes.proses;

    console.log('CSRF Token:', csrfToken);
    console.log('Routes:', window.appRoutes);

    // Modal helper functions
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            console.log('Modal opened:', modalId);
        } else {
            console.error('Modal not found:', modalId);
        }
    }

    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            console.log('Modal closed:', modalId);

            // Stop scanner if closing QR scanner modal
            if (modalId === 'qrScannerModal' && html5QrcodeScanner && isScanning) {
                try {
                    html5QrcodeScanner.clear();
                    isScanning = false;
                    console.log('Scanner stopped');
                } catch (e) {
                    console.log("Scanner clear error:", e);
                }
            }
        } else {
            console.error('Modal not found:', modalId);
        }
    }

    function startScanner() {
        if (isScanning) {
            console.log('Scanner already running');
            return;
        }

        console.log('Starting scanner...');
        isScanning = true;

        try {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader",
                {
                    fps: 10,
                    qrbox: 250,
                    rememberLastUsedCamera: true
                },
                false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanError);
            console.log('Scanner rendered successfully');
        } catch (error) {
            console.error('Scanner initialization error:', error);
            isScanning = false;
        }
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
        fetch(scanProsesUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                qr_token: decodedText
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close QR scanner modal
                    hideModal('qrScannerModal');

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
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <strong class="font-semibold">PERHATIAN:</strong> Pengembalian ini terlambat ${Math.abs(data.days_late)} hari! 
                                User akan diblokir otomatis.
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }

        let detailsHTML = `
            <div class="space-y-2 text-sm">
                <p><strong class="font-semibold text-gray-700">Kode Peminjaman:</strong> <span class="text-gray-900">${data.kode}</span></p>
                <p><strong class="font-semibold text-gray-700">Peminjam:</strong> <span class="text-gray-900">${data.peminjam}</span></p>
                <p><strong class="font-semibold text-gray-700">Tanggal Pinjam:</strong> <span class="text-gray-900">${data.tanggal_pinjam}</span></p>
                <p><strong class="font-semibold text-gray-700">Tanggal Rencana Kembali:</strong> <span class="text-gray-900">${data.tanggal_rencana}</span></p>
                <p><strong class="font-semibold text-gray-700">Alat yang Dipinjam:</strong></p>
                <ul class="list-disc list-inside ml-4 text-gray-900">${alatList}</ul>
            </div>
            ${lateWarning}
        `;

        document.getElementById('returnDetailsContent').innerHTML = detailsHTML;

        // Reset form
        document.getElementById('kondisi').value = '';
        document.getElementById('catatan').value = '';

        // Show modal
        showModal('returnConfirmModal');
    }

    // Setup button scan QR dengan addEventListener
    console.log('Setting up button listener...');
    const btnScanQR = document.getElementById('btnScanQR');
    console.log('Button found:', btnScanQR);

    if (btnScanQR) {
        console.log('Adding click listener to button...');
        btnScanQR.addEventListener('click', function (e) {
            e.preventDefault();
            console.log("Button clicked! Opening scanner...");
            showModal('qrScannerModal');

            if (!isScanning) {
                setTimeout(() => {
                    startScanner();
                }, 300);
            }
        });
        console.log('Click listener added successfully');
    } else {
        console.error('Button btnScanQR not found!');
    }

    // Close modal when clicking backdrop
    document.addEventListener('click', function (event) {
        if (event.target.id === 'qrScannerModal' || event.target.id === 'returnConfirmModal') {
            hideModal(event.target.id);
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            const qrModal = document.getElementById('qrScannerModal');
            const returnModal = document.getElementById('returnConfirmModal');

            if (qrModal && !qrModal.classList.contains('hidden')) {
                hideModal('qrScannerModal');
            }
            if (returnModal && !returnModal.classList.contains('hidden')) {
                hideModal('returnConfirmModal');
            }
        }
    });

    // Setup close buttons for modals
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const qrModal = document.getElementById('qrScannerModal');
            const returnModal = document.getElementById('returnConfirmModal');

            // Check which modal to close
            if (qrModal && !qrModal.classList.contains('hidden')) {
                hideModal('qrScannerModal');
            }
            if (returnModal && !returnModal.classList.contains('hidden')) {
                hideModal('returnConfirmModal');
            }
        });
    });

    // Tab functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600');
                btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
            });

            // Add active class to clicked tab
            this.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
            this.classList.add('border-blue-600', 'text-blue-600');

            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show target tab content
            document.getElementById(targetTab).classList.remove('hidden');
        });
    });

    // Handle return form submission
    const returnForm = document.getElementById('returnForm');
    if (returnForm) {
        // Add kondisi change listener for conditional validation
        const kondisiSelect = document.getElementById('kondisi');
        const catatanTextarea = document.getElementById('catatan');
        const catatanRequired = document.getElementById('catatanRequired');
        const catatanOptional = document.getElementById('catatanOptional');
        const catatanHelp = document.getElementById('catatanHelp');

        if (kondisiSelect) {
            kondisiSelect.addEventListener('change', function () {
                const isBaik = this.value === 'baik';

                if (isBaik) {
                    // Kondisi baik - catatan opsional
                    catatanTextarea.removeAttribute('required');
                    if (catatanRequired) catatanRequired.classList.add('hidden');
                    if (catatanOptional) catatanOptional.classList.remove('hidden');
                    if (catatanHelp) catatanHelp.classList.add('hidden');
                    catatanTextarea.classList.remove('border-red-300');
                } else {
                    // Kondisi bukan baik - catatan wajib
                    catatanTextarea.setAttribute('required', 'required');
                    if (catatanRequired) catatanRequired.classList.remove('hidden');
                    if (catatanOptional) catatanOptional.classList.add('hidden');
                    if (catatanHelp) catatanHelp.classList.remove('hidden');

                    // Highlight jika kosong
                    if (!catatanTextarea.value.trim()) {
                        catatanTextarea.classList.add('border-red-300');
                    }
                }
            });

            // Hapus highlight saat user mulai mengetik
            catatanTextarea.addEventListener('input', function () {
                if (this.value.trim()) {
                    this.classList.remove('border-red-300');
                }
            });
        }

        returnForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validasi frontend
            const kondisi = document.getElementById('kondisi').value;
            const catatan = document.getElementById('catatan').value.trim();

            if (!kondisi) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih kondisi alat terlebih dahulu'
                });
                return;
            }

            if (kondisi !== 'baik' && !catatan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Catatan wajib diisi jika kondisi alat bukan "Baik"'
                });
                document.getElementById('catatan').classList.add('border-red-300');
                document.getElementById('catatan').focus();
                return;
            }

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            fetch(prosesUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        hideModal('returnConfirmModal');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: true
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        // Close modal juga saat error
                        hideModal('returnConfirmModal');

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Terjadi kesalahan saat memproses pengembalian'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Close modal saat error
                    hideModal('returnConfirmModal');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memproses pengembalian'
                    });
                });
        });
    }

    console.log('All event listeners setup complete');
});
