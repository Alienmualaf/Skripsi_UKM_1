<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /**
     * Merges a report with its submission attachments (Simplifed to direct render as submissions are removed).
     */
    public function mergeReportWithSubmissions($ukm, $data)
    {
        // Prepare Title if not set
        $titles = [
            'events' => 'LAPORAN KEGIATAN',
            'lpj' => 'LAPORAN PERTANGGUNGJAWABAN (LPJ)'
        ];
        $data['report_title'] = $data['report_title'] ?? ($titles[$data['type']] ?? 'LAPORAN UKM');
        
        return Pdf::loadView('ukm.reports.pdf.unified', $data)->output();
    }

    /**
     * Generates a single professional report (non-LPJ)
     */
    public function generateGeneralReport($ukm, $data)
    {
        $titles = [
            'events' => 'LAPORAN KEGIATAN',
            'finances' => 'LAPORAN KEUANGAN',
            'memberships' => 'LAPORAN KEANGGOTAAN'
        ];
        
        $data['report_title'] = $titles[$data['type']] ?? 'LAPORAN UKM';
        
        return Pdf::loadView('ukm.reports.pdf.unified', $data)->output();
    }
}

