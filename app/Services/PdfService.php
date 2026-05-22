<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Merges a report with its submission attachments.
     */
    public function mergeReportWithSubmissions($ukm, $data)
    {
        $fpdi = new Fpdi();
        
        // Prepare Title if not set
        $titles = [
            'events' => 'LAPORAN KEGIATAN',
            'lpj' => 'LAPORAN PERTANGGUNGJAWABAN (LPJ)'
        ];
        $data['report_title'] = $data['report_title'] ?? ($titles[$data['type']] ?? 'LAPORAN UKM');
        
        // 1. Generate and import Main Report Part
        $submissions = $data['submissions'] ?? collect([]);
        $data['submissions'] = []; // Clear for main part
        
        $mainPdfContent = Pdf::loadView('ukm.reports.pdf.unified', $data)->output();
        $this->addPdfContentToFpdi($fpdi, $mainPdfContent);
        
        if ($submissions->count() > 0) {
            // 2. Add "V. LAMPIRAN SURAT KELUAR" Main Section Page
            $sectionStartHtml = view('ukm.reports.pdf.lpj_group_header', [
                'ukm' => $ukm,
                'isSectionStart' => true
            ])->render();
            $this->addPdfContentToFpdi($fpdi, Pdf::loadHTML($sectionStartHtml)->output());

            // 3. Process Submissions grouped by Agenda
            foreach ($submissions as $agendaTitle => $agendaSubmissions) {
                // Add an Agenda Separator Page
                $headerHtml = view('ukm.reports.pdf.lpj_group_header', [
                    'title' => $agendaTitle,
                    'ukm' => $ukm,
                    'isSectionStart' => false,
                    'submissions' => $agendaSubmissions
                ])->render();
                $this->addPdfContentToFpdi($fpdi, Pdf::loadHTML($headerHtml)->output());
                
                foreach ($agendaSubmissions as $sub) {
                    if ($sub->attachment) {
                        $filePath = storage_path('app/public/' . $sub->attachment);
                        if (file_exists($filePath)) {
                            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                            if (strtolower($ext) === 'pdf') {
                                try {
                                    $this->addPdfFileToFpdi($fpdi, $filePath);
                                } catch (\Exception $e) {
                                    \Log::error("Failed to merge PDF: " . $e->getMessage());
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $fpdi->Output('S');
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

    private function addPdfContentToFpdi($fpdi, $content)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'pdf_main');
        file_put_contents($tmpFile, $content);
        
        $this->addPdfFileToFpdi($fpdi, $tmpFile);
        
        unlink($tmpFile);
    }

    private function addPdfFileToFpdi($fpdi, $filePath)
    {
        $pageCount = $fpdi->setSourceFile($filePath);
        for ($i = 1; $i <= $pageCount; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            
            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($template);
        }
    }
}
