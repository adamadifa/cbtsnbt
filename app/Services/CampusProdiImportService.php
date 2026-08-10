<?php

namespace App\Services;

use App\Models\CampusProdi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class CampusProdiImportService
{
    /**
     * Get sheet names from an uploaded file.
     */
    public function getSheetNames(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        return $reader->listWorksheetNames($filePath);
    }

    /**
     * Import a specific sheet from the Excel file.
     */
    public function importSheet(string $filePath, string $sheetName): int
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly($sheetName);
        $spreadsheet = $reader->load($filePath);
        
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $importedCount = 0;
        $lastCampusName = null;

        $headerIndex = -1;
        $campusCol = 1; // Default
        $prodiCol = 2; // Default
        $jenjangCol = 3; // Default

        // Detect column positions from the table header
        foreach ($rows as $index => $row) {
            $rowLower = array_map(function($val) {
                return strtolower(trim((string)$val));
            }, $row);

            $hasProdi = false;
            $hasJenjang = false;
            foreach ($rowLower as $colIdx => $val) {
                if (str_contains($val, 'program studi') || $val === 'prodi' || str_contains($val, 'jurusan')) {
                    $hasProdi = true;
                    $prodiCol = $colIdx;
                }
                if (str_contains($val, 'jenjang')) {
                    $hasJenjang = true;
                    $jenjangCol = $colIdx;
                }
                if (str_contains($val, 'nama kampus') || str_contains($val, 'kampus') || str_contains($val, 'universitas')) {
                    $campusCol = $colIdx;
                }
            }

            if ($hasProdi && $hasJenjang) {
                $headerIndex = $index;
                break;
            }
        }

        $startIndex = $headerIndex !== -1 ? $headerIndex + 1 : 0;

        // Cache existing data for faster lookups and preventing duplicates
        $existingRecords = CampusProdi::select('campus_name', 'prodi_name', 'jenjang')
            ->get()
            ->mapWithKeys(function ($item) {
                $key = strtolower(trim($item->campus_name)) . '|' . strtolower(trim($item->prodi_name)) . '|' . strtolower(trim($item->jenjang));
                return [$key => true];
            })
            ->toArray();

        for ($i = $startIndex; $i < count($rows); $i++) {
            $row = $rows[$i];

            if (empty($row) || count($row) <= max($campusCol, $prodiCol, $jenjangCol)) {
                continue;
            }

            $campusVal = trim((string)($row[$campusCol] ?? ''));
            $prodiVal = trim((string)($row[$prodiCol] ?? ''));
            $jenjangVal = trim((string)($row[$jenjangCol] ?? ''));

            // Clean campus name (remove vertical newlines and collapse multiple spaces)
            $campusVal = str_replace(["\r", "\n"], "", $campusVal);
            $campusVal = preg_replace('/\s+/', ' ', $campusVal);
            $campusVal = trim($campusVal);

            // Clean prodi name
            $prodiVal = str_replace(["\r", "\n"], " ", $prodiVal);
            $prodiVal = preg_replace('/\s+/', ' ', $prodiVal);
            $prodiVal = trim($prodiVal);

            // Clean jenjang
            $jenjangVal = strtoupper(preg_replace('/\s+/', '', $jenjangVal));

            // Handle merged cells for campus name
            if ($campusVal !== '') {
                $lastCampusName = $campusVal;
            } else {
                $campusVal = $lastCampusName;
            }

            // Skip empty/invalid/divider rows
            if (empty($prodiVal) || empty($jenjangVal) || empty($campusVal)) {
                continue;
            }

            $prodiLower = strtolower($prodiVal);
            if (str_contains($prodiLower, 'prodi') || str_contains($prodiLower, 'jurusan') || str_contains($prodiLower, 'program studi')) {
                continue;
            }
            if ($jenjangVal === 'JENJANG') {
                continue;
            }

            // Generate lookup key to check for duplicates
            $lookupKey = strtolower($campusVal) . '|' . strtolower($prodiVal) . '|' . strtolower($jenjangVal);

            // If it doesn't exist in our cached existing records, insert it
            if (!isset($existingRecords[$lookupKey])) {
                CampusProdi::create([
                    'campus_name' => $campusVal,
                    'prodi_name' => $prodiVal,
                    'jenjang' => $jenjangVal,
                ]);

                // Update cache for consecutive duplicate checks inside the same import batch
                $existingRecords[$lookupKey] = true;
                $importedCount++;
            }
        }

        return $importedCount;
    }
}
