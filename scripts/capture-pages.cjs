const { chromium } = require('playwright');
const fs = require('fs');

(async() => {
    const outDir = 'storage';
    if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });

    // try to use system Chrome/Edge if Playwright's bundled binary has issues on Windows
    const launcherPaths = [
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
        'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe'
    ];

    let launchOptions = {};
    for (const p of launcherPaths) {
        if (fs.existsSync(p)) {
            launchOptions.executablePath = p;
            break;
        }
    }

    const browser = await chromium.launch(launchOptions);
    const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
    const page = await context.newPage();

    const base = 'http://127.0.0.1:8000';
    const pages = [
        { path: '/login', file: `${outDir}/login.png` },
        { path: '/register', file: `${outDir}/register.png` },
    ];

    for (const p of pages) {
        const url = base + p.path;
        console.log('Capturing', url);
        try {
            await page.goto(url, { waitUntil: 'networkidle', timeout: 15000 });
            await page.waitForTimeout(500);
            await page.screenshot({ path: p.file, fullPage: true });
            console.log('Saved', p.file);
        } catch (err) {
            console.error('Failed to capture', url, err.message);
        }
    }

    await browser.close();
})();