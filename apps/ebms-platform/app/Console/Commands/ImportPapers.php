<?php

namespace App\Console\Commands;

use App\Models\Subject;
use Illuminate\Console\Command;

class ImportPapers extends Command
{
    protected $signature = 'ebms:import-papers
                            {file : Path to the CSV file}
                            {--dry-run : Show what would be imported without saving}';

    protected $description = 'Import exam papers from a CSV file into the subjects table';

    public function handle(): int
    {
        $path = $this->argument('file');
        $dryRun = $this->option('dry-run');

        if (! file_exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        $headers = array_map('trim', fgetcsv($handle));

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;
        $rows     = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($headers)) {
                $skipped++;
                continue;
            }

            $r = array_combine($headers, $row);

            $paperType = match (strtoupper(trim($r['PAPERTYPE'] ?? ''))) {
                'COMMON'   => 'compulsory',
                'ELECTIVE' => 'elective',
                default    => 'compulsory',
            };

            $rows[] = [
                'code'          => strtoupper(trim($r['PAPERCODE'])),
                'name'          => trim($r['PAPERNAME']),
                'course'        => strtoupper(trim($r['COURSE'])),
                'group_code'    => strtoupper(trim($r['GROUPCODE'])),
                'medium'        => strtoupper(trim($r['MEDIUM'])),
                'semester'      => (int) $r['SEM'],
                'paper_type'    => $paperType,
                'elective_group'=> trim($r['ELECTIVEGROUP']) ?: null,
                'part'          => is_numeric(trim($r['PART'])) ? (int) $r['PART'] : null,
                'scheme'        => (int) $r['SCHEME'],
            ];
        }

        fclose($handle);

        $this->info(sprintf('Parsed %d rows from CSV.', count($rows)));

        if ($dryRun) {
            $this->table(
                ['code', 'name', 'course', 'group_code', 'medium', 'sem', 'type', 'scheme'],
                array_map(fn($r) => [
                    $r['code'], $r['name'], $r['course'], $r['group_code'],
                    $r['medium'], $r['semester'], $r['paper_type'], $r['scheme'],
                ], $rows)
            );
            $this->warn('Dry-run — nothing saved.');
            return self::SUCCESS;
        }

        foreach ($rows as $data) {
            $key = [
                'code'       => $data['code'],
                'group_code' => $data['group_code'],
                'medium'     => $data['medium'],
                'semester'   => $data['semester'],
                'scheme'     => $data['scheme'],
            ];

            $existing = Subject::where($key)->first();

            if ($existing) {
                $existing->fill($data)->save();
                $updated++;
            } else {
                Subject::create($data);
                $inserted++;
            }
        }

        $this->info("Done. Inserted: {$inserted}, Updated: {$updated}, Skipped (bad rows): {$skipped}");

        return self::SUCCESS;
    }
}
