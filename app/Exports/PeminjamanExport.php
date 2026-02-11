<?php

namespace App\Exports;

use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PeminjamanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Peminjaman::with(['peminjam', 'details.alat', 'pemberi_izin', 'pengembalian']);

        // Filter by date range if provided
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_pengajuan', [
                $this->startDate,
                $this->endDate
            ]);
        }

        // Filter by status if provided
        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Peminjaman',
            'Nama Peminjam',
            'Email Peminjam',
            'Tanggal Pengajuan',
            'Tanggal Rencana Ambil',
            'Tanggal Rencana Kembali',
            'Tanggal Ambil Sebenarnya',
            'Status',
            'Alat yang Dipinjam',
            'Alasan Meminjam',
            'Disetujui/Ditolak Oleh',
            'Catatan',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($peminjaman): array
    {
        static $no = 0;
        $no++;

        // Get detail alat
        $alatList = $peminjaman->details->map(function ($detail) {
            return $detail->alat->nama_alat . ' (' . $detail->jumlah . ' unit)';
        })->implode(', ');

        // Status display
        $statusDisplay = match ($peminjaman->status) {
            StatusPeminjaman::PENDING->value => 'Menunggu Persetujuan',
            StatusPeminjaman::DISETUJUI->value => 'Disetujui',
            StatusPeminjaman::DITOLAK->value => 'Ditolak',
            StatusPeminjaman::DIAMBIL->value => 'Sudah Diambil',
            StatusPeminjaman::KEMBALI->value => 'Sudah Dikembalikan',
            StatusPeminjaman::TERLAMBAT->value => 'Terlambat',
            default => ucfirst($peminjaman->status)
        };

        // Approver name
        $approverName = '-';
        if ($peminjaman->pemberi_izin) {
            $approverName = $peminjaman->pemberi_izin->nama_lengkap ?? $peminjaman->pemberi_izin->name;
        }

        return [
            $no,
            $peminjaman->kode_peminjaman,
            $peminjaman->peminjam->nama_lengkap ?? $peminjaman->peminjam->name,
            $peminjaman->peminjam->email,
            $peminjaman->tanggal_pengajuan->format('d/m/Y H:i'),
            $peminjaman->tanggal_pengambilan_rencana->format('d/m/Y'),
            $peminjaman->tanggal_pengembalian_rencana->format('d/m/Y'),
            $peminjaman->tanggal_pengambilan_sebenarnya
                ? $peminjaman->tanggal_pengambilan_sebenarnya->format('d/m/Y H:i')
                : '-',
            $statusDisplay,
            $alatList,
            $peminjaman->alasan_meminjam ?? '-',
            $approverName,
            $peminjaman->note ?? '-',
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (header)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 18,  // Kode
            'C' => 20,  // Nama Peminjam
            'D' => 25,  // Email
            'E' => 18,  // Tanggal Pengajuan
            'F' => 18,  // Tanggal Rencana Ambil
            'G' => 20,  // Tanggal Rencana Kembali
            'H' => 20,  // Tanggal Ambil Sebenarnya
            'I' => 18,  // Status
            'J' => 35,  // Alat
            'K' => 30,  // Alasan
            'L' => 20,  // Approver
            'M' => 30,  // Catatan
        ];
    }
}
