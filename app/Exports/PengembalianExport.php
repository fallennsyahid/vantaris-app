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

class PengembalianExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Peminjaman::whereIn('status', [
            StatusPeminjaman::KEMBALI->value,
            StatusPeminjaman::TERLAMBAT->value
        ])
            ->with(['peminjam', 'details.alat', 'pengembalian.petugas']);

        // Filter by date range if provided
        if ($this->startDate && $this->endDate) {
            $query->whereHas('pengembalian', function ($q) {
                $q->whereBetween('tanggal_pengembalian_sebenarnya', [
                    $this->startDate,
                    $this->endDate
                ]);
            });
        }

        return $query->latest()->get();
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
            'Tanggal Pinjam',
            'Tanggal Rencana Kembali',
            'Tanggal Kembali Sebenarnya',
            'Status',
            'Keterlambatan (Hari)',
            'Alat yang Dipinjam',
            'Kondisi Pengembalian',
            'Petugas Penerima',
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

        // Calculate late days
        $lateDays = 0;
        if ($peminjaman->pengembalian) {
            $tanggalRencana = $peminjaman->tanggal_pengembalian_rencana;
            $tanggalSebenarnya = $peminjaman->pengembalian->tanggal_pengembalian_sebenarnya;

            if ($tanggalSebenarnya->greaterThan($tanggalRencana)) {
                $lateDays = $tanggalSebenarnya->diffInDays($tanggalRencana);
            }
        }

        // Status display
        $statusDisplay = match ($peminjaman->status) {
            StatusPeminjaman::KEMBALI->value => 'Dikembalikan',
            StatusPeminjaman::TERLAMBAT->value => 'Terlambat',
            default => ucfirst($peminjaman->status)
        };

        return [
            $no,
            $peminjaman->kode_peminjaman,
            $peminjaman->peminjam->nama_lengkap ?? $peminjaman->peminjam->name,
            $peminjaman->peminjam->email,
            $peminjaman->tanggal_pengajuan->format('d/m/Y H:i'),
            $peminjaman->tanggal_pengembalian_rencana->format('d/m/Y'),
            $peminjaman->pengembalian ? $peminjaman->pengembalian->tanggal_pengembalian_sebenarnya->format('d/m/Y H:i') : '-',
            $statusDisplay,
            $lateDays > 0 ? $lateDays : '-',
            $alatList,
            $peminjaman->pengembalian ? ucfirst(str_replace('_', ' ', $peminjaman->pengembalian->kondisi->value)) : '-',
            $peminjaman->pengembalian && $peminjaman->pengembalian->petugas
                ? $peminjaman->pengembalian->petugas->nama_lengkap ?? $peminjaman->pengembalian->petugas->name
                : '-',
            $peminjaman->pengembalian && $peminjaman->pengembalian->catatan
                ? $peminjaman->pengembalian->catatan
                : '-',
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
                    'startColor' => ['rgb' => '4472C4']
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
            'E' => 18,  // Tanggal Pinjam
            'F' => 20,  // Tanggal Rencana
            'G' => 22,  // Tanggal Sebenarnya
            'H' => 15,  // Status
            'I' => 15,  // Keterlambatan
            'J' => 35,  // Alat
            'K' => 18,  // Kondisi
            'L' => 20,  // Petugas
            'M' => 30,  // Catatan
        ];
    }
}
