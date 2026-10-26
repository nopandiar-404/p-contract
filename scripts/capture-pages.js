const { chromium } = require('playwright');
const fs = require('fs');

(async() => {
    const outDir = 'storage';
    if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });

    const browser = await chromium.launch({ args: ['--no-sandbox'] });
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
            // small delay to allow CSS animations to reach a visible state
            await page.waitForTimeout(500);
            await page.screenshot({ path: p.file, fullPage: true });
            console.log('Saved', p.file);
        } catch (err) {
            console.error('Failed to capture', url, err.message);
        }
    }

    await browser.close();
})();