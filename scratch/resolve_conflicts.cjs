const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');

const files = [
    'app/Http/Controllers/ContractController.php',
    'resources/views/contract-types/index.blade.php',
    'resources/views/contracts/create.blade.php',
    'resources/views/contracts/edit.blade.php',
    'resources/views/contracts/export_pdf.blade.php',
    'resources/views/contracts/index.blade.php',
    'resources/views/contracts/show.blade.php',
    'resources/views/dashboard/index.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'routes/web.php',
];

function resolveConflicts(content) {
    // State machine: keep HEAD side, discard theirs side
    const lines = content.split('\n');
    const result = [];
    let state = 'normal'; // normal | ours | theirs

    for (const line of lines) {
        if (/^<<<<<<< /.test(line)) {
            state = 'ours';
            continue;
        }
        if (/^=======$/.test(line) && state === 'ours') {
            state = 'theirs';
            continue;
        }
        if (/^>>>>>>> /.test(line)) {
            state = 'normal';
            continue;
        }

        if (state === 'normal' || state === 'ours') {
            result.push(line);
        }
        // state === 'theirs' => skip
    }

    return result.join('\n');
}

let fixed = 0;
let skipped = 0;

for (const rel of files) {
    const filePath = path.join(root, rel);
    if (!fs.existsSync(filePath)) {
        console.log(`SKIP (not found): ${rel}`);
        skipped++;
        continue;
    }

    const original = fs.readFileSync(filePath, 'utf8');
    if (!original.includes('<<<<<<<')) {
        console.log(`OK (no conflict): ${rel}`);
        skipped++;
        continue;
    }

    const resolved = resolveConflicts(original);
    fs.writeFileSync(filePath, resolved, 'utf8');
    console.log(`FIXED: ${rel}`);
    fixed++;
}

console.log(`\nDone. Fixed: ${fixed}, Skipped: ${skipped}`);

