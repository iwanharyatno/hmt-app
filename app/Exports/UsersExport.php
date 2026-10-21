<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class UsersExport extends DefaultValueBinder implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithCustomValueBinder, WithHeadings
{
    private const EXCLUDED_COLUMNS = ['email_verified_at', 'password', 'remember_token'];

    private array $columns = [];

    private array $dateColumns = [];

    private array $textColumns = [];

    public function __construct()
    {
        $schemaColumns = array_values(array_filter(
            Schema::getColumns('users'),
            fn (array $column) => ! in_array($column['name'], self::EXCLUDED_COLUMNS, true)
        ));

        $this->columns = array_column($schemaColumns, 'name');

        foreach ($schemaColumns as $index => $column) {
            $letter = Coordinate::stringFromColumnIndex($index + 1);
            $type = strtolower($column['type_name'] ?? $column['type'] ?? '');

            if (in_array($type, ['date', 'datetime', 'timestamp'], true)) {
                $this->dateColumns[$column['name']] = $letter;
            } elseif (! in_array($type, [
                'bigint', 'int', 'integer', 'smallint', 'tinyint', 'mediumint',
                'decimal', 'float', 'double', 'numeric', 'real', 'boolean', 'bool',
            ], true)) {
                $this->textColumns[$letter] = true;
            }
        }
    }

    public function collection()
    {
        return User::query()->orderBy('id')->get()->map(function (User $user) {
            $row = [];

            foreach ($this->columns as $column) {
                $value = $user->getAttribute($column);

                if (isset($this->dateColumns[$column]) && $value !== null) {
                    $value = ExcelDate::PHPToExcel(
                        $value instanceof \DateTimeInterface ? $value : Carbon::parse($value)
                    );
                }

                $row[$column] = $value;
            }

            return $row;
        });
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function columnFormats(): array
    {
        $formats = [];

        foreach ($this->dateColumns as $column => $letter) {
            $formats[$letter] = $column === 'birthDate'
                ? 'yyyy-mm-dd'
                : 'yyyy-mm-dd hh:mm:ss';
        }

        return $formats;
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($value !== null && isset($this->textColumns[$cell->getColumn()])) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
