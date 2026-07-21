<?php

namespace App\Exports;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TodosExport implements FromQuery, WithHeadings, WithMapping, WithEvents, WithStrictNullComparison
{
    use RegistersEventListeners;

    public function __construct(protected array $filters) {}

    public function query()
    {
        return Todo::filtered($this->filters);
    }

    public function headings(): array
    {
        return [
            'ID', 'Title', 'Assignee',
            'Due Date', 'Time Tracked',
            'Status', 'Priority'
        ];
    }

    public function map($todo): array
    {
        return [
            $todo->id,
            $todo->title,
            $todo->assignee ?? '-',
            $todo->due_date->format('Y-m-d'),
            $todo->time_tracked,
            $todo->status->value,
            $todo->priority->value
        ];
    }

    public function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet->getDelegate();
        $lastRow = $sheet->getHighestRow();
        $totalTodos = $lastRow - 1;
        $totalTimeTracked = $this->query()->sum('time_tracked');
        $summaryRow = $lastRow + 2;

        // Styling heading (bold + border)
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G' . $lastRow)->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Summary
        $sheet->setCellValue("A{$summaryRow}", 'Total Todos');
        $sheet->setCellValue("B{$summaryRow}", $totalTodos);
        $sheet->setCellValue('A' . ($summaryRow + 1), 'Total Time Tracked');
        $sheet->setCellValue('B' . ($summaryRow + 1), $totalTimeTracked);

        // Styling summary (bold + border)
        $sheet->getStyle("A{$summaryRow}:B" . ($summaryRow + 1))
            ->getFont()->setBold(true);
        $sheet->getStyle("A{$summaryRow}:B" . ($summaryRow + 1))
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Auto-size column
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
