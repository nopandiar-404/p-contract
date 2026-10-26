<?php

$dbPath = __DIR__ . '/../database/database.sqlite';
$outputPath = __DIR__ . '/../database/database.sql';

if (!file_exists($dbPath)) {
    fwrite(STDERR, "Error: SQLite database file not found at {$dbPath}\n");
    exit(1);
}

$db = new SQLite3($dbPath);
$dump = "PRAGMA foreign_keys=OFF;\nBEGIN TRANSACTION;\n";

$query = <<<SQL
SELECT type, name, tbl_name, sql
FROM sqlite_master
WHERE sql NOT NULL
  AND type IN ('table','index','trigger','view')
ORDER BY type = 'table' DESC, name;
SQL;

$result = $db->query($query);
if (!$result) {
    fwrite(STDERR, "Error: Failed to query sqlite_master\n");
    exit(1);
}

$skipTables = ['sqlite_sequence', 'sqlite_stat1', 'sqlite_stat4'];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $type = $row['type'];
    $name = $row['name'];
    $sql = $row['sql'];

    if ($type === 'table') {
        if (in_array($name, $skipTables, true)) {
            continue;
        }

        $dump .= "DROP TABLE IF EXISTS \"{$name}\";\n";
        $dump .= $sql . ";\n";

        $tableRows = $db->query('SELECT * FROM "' . $name . '"');
        while ($record = $tableRows->fetchArray(SQLITE3_ASSOC)) {
            $columns = [];
            $values = [];

            foreach ($record as $column => $value) {
                $columns[] = '"' . str_replace('"', '""', $column) . '"';

                if ($value === null) {
                    $values[] = 'NULL';
                } else {
                    $escaped = str_replace("'", "''", $value);
                    $values[] = "'{$escaped}'";
                }
            }

            $dump .= sprintf(
                'INSERT INTO "%s" (%s) VALUES (%s);\n',
                $name,
                implode(', ', $columns),
                implode(', ', $values)
            );
        }
    } else {
        $dump .= $sql . ";\n";
    }
}

$dump .= "COMMIT;\n";

if (file_put_contents($outputPath, $dump) === false) {
    fwrite(STDERR, "Error: Failed to write dump to {$outputPath}\n");
    exit(1);
}

fwrite(STDOUT, "SQLite database exported to {$outputPath}\n");
