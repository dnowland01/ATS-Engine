<?php

namespace ATS\Core;

class TableManager
{
    public function __construct(
        private string $mainTable,
        private string $under93Table
    ) {}

    public function load(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }
        return array_map('str_getcsv', file($path));
    }

    public function save(string $path, array $rows): void
    {
        $fp = fopen($path, 'w');
        foreach ($rows as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    }

    public function nextId(array $table): int
    {
        if (empty($table)) {
            return 1;
        }
        $ids = array_column($table, 0);
        return max($ids) + 1;
    }

    public function insert(array $row, bool $main = true): void
    {
        $path = $main ? $this->mainTable : $this->under93Table;
        $table = $this->load($path);
        $table[] = $row;
        $this->save($path, $table);
    }

    public function updateRow(array $table, int $index, array $newRow): array
    {
        $table[$index] = $newRow;
        return $table;
    }
}
