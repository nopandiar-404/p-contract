import sqlite3
from pathlib import Path

base = Path(__file__).resolve().parent.parent
source = base / 'database' / 'database.sqlite'
dest = base / 'database' / 'database.sql'

if not source.exists():
    raise SystemExit(f'Error: SQLite database not found at {source}')

conn = sqlite3.connect(str(source))
conn.row_factory = sqlite3.Row
cur = conn.cursor()

# Dump schema objects
cur.execute("SELECT type,name,tbl_name,sql FROM sqlite_master WHERE sql NOT NULL AND type IN ('table', 'index', 'trigger', 'view') ORDER BY type='table' DESC, name")
rows = cur.fetchall()

skip_tables = {'sqlite_sequence', 'sqlite_stat1', 'sqlite_stat4'}
lines = ["PRAGMA foreign_keys=OFF;", "BEGIN TRANSACTION;"]

for row in rows:
    typ = row['type']
    name = row['name']
    sql = row['sql']

    if typ == 'table':
        if name in skip_tables:
            continue
        lines.append(f'DROP TABLE IF EXISTS "{name}";')
        lines.append(sql + ';')

        cur2 = conn.execute(f'SELECT * FROM "{name}"')
        for record in cur2.fetchall():
            cols = [f'"{col}"' for col in record.keys()]
            vals = []
            for value in record:
                if value is None:
                    vals.append('NULL')
                elif isinstance(value, bytes):
                    vals.append("X'" + value.hex() + "'")
                else:
                    s = str(value).replace("'", "''")
                    vals.append(f"'{s}'")
            lines.append(f'INSERT INTO "{name}" ({", ".join(cols)}) VALUES ({", ".join(vals)});')
    else:
        lines.append(sql + ';')

lines.append('COMMIT;')

dest.write_text('\n'.join(lines), encoding='utf-8')
print(f'SQL dump written to {dest}')
