import { createRequire } from 'node:module';
import fs from 'fs';

const require = createRequire((process.env.NODE_PATH || process.cwd()) + '/');
const { chromium } = require('playwright');

const BASE = process.argv[2] || 'https://127.0.0.1';
const OUT = process.argv[3] || 'docs/produccion/capturas';
const ADMIN_USER = process.env.ADMIN_USER || 'admin';
const ADMIN_PASS = process.env.ADMIN_PASS || 'admin';
const JRV_USER = process.env.JRV_USER || 'jrv';
const JRV_PASS = process.env.JRV_PASS || 'jrv';

fs.mkdirSync(OUT, { recursive: true });

const browser = await chromium.launch({ headless: true, executablePath: '/usr/bin/chromium', args: ['--no-sandbox'] });
const ctx = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 1280, height: 800 } });
const page = await ctx.newPage();

const login = async (user, pass) => {
    await page.goto(`${BASE}/login`);
    await page.fill('#username', user);
    await page.fill('#password', pass);
    await page.click('button[type="submit"]', { noWaitAfter: true });
    await page.waitForTimeout(1800);
};

try {
    await page.goto(`${BASE}/login`, { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(250);
    await page.screenshot({ path: `${OUT}/12-splash-carga.png` });
    console.log('[ok] splash');

    await page.waitForTimeout(2200);
    await page.screenshot({ path: `${OUT}/13-login-creditos.png` });
    console.log('[ok] login con créditos');

    await page.goto(`${BASE}/acerca-de`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(600);
    await page.screenshot({ path: `${OUT}/14-acerca-de.png`, fullPage: true });
    console.log('[ok] acerca de');

    await login(ADMIN_USER, ADMIN_PASS);
    await page.goto(`${BASE}/admin`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(600);
    await page.screenshot({ path: `${OUT}/15-admin-footer.png`, fullPage: true });
    console.log('[ok] admin footer');

    const jrvCtx = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 1280, height: 800 } });
    const jrvPage = await jrvCtx.newPage();
    await jrvPage.goto(`${BASE}/login`);
    await jrvPage.fill('#username', JRV_USER);
    await jrvPage.fill('#password', JRV_PASS);
    await jrvPage.click('button[type="submit"]', { noWaitAfter: true });
    await jrvPage.waitForTimeout(1800);
    await jrvPage.goto(`${BASE}/jrv`, { waitUntil: 'networkidle' });
    await jrvPage.waitForTimeout(600);
    await jrvPage.screenshot({ path: `${OUT}/16-jrv-footer.png`, fullPage: true });
    console.log('[ok] jrv footer');

    const kioskCtx = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 1280, height: 800 } });
    const kioskPage = await kioskCtx.newPage();
    await kioskPage.goto(`${BASE}/kiosko`, { waitUntil: 'networkidle' });
    await kioskPage.waitForTimeout(600);
    await kioskPage.screenshot({ path: `${OUT}/17-urna-footer.png`, fullPage: true });
    console.log('[ok] urna footer');
} catch (error) {
    console.error('[fallo]', String(error?.message || error));
    process.exitCode = 1;
} finally {
    await browser.close();
}
