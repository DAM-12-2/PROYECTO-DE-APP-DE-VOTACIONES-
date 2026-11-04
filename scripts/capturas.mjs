import { createRequire } from 'node:module';
import fs from 'fs';

const require = createRequire((process.env.NODE_PATH || process.cwd()) + '/');
const { chromium } = require('playwright');

const BASE = process.argv[2] || 'https://127.0.0.1:8443';
const OUT = process.argv[3] || 'docs/produccion/capturas';

fs.mkdirSync(OUT, { recursive: true });

const browser = await chromium.launch({
    headless: true,
    executablePath: '/usr/bin/chromium',
    args: ['--no-sandbox'],
});

const abrir = async (usuario, password) => {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 800 }, ignoreHTTPSErrors: true });
    const page = await ctx.newPage();

    if (usuario) {
        await page.goto(`${BASE}/login`);
        await page.fill('#username', usuario);
        await page.fill('#password', password);
        await page.click('button[type="submit"]', { noWaitAfter: true });
        await page.waitForLoadState('domcontentloaded');
        await page.waitForTimeout(1200);
    }

    return page;
};

const captura = async (page, url, archivo) => {
    await page.goto(`${BASE}${url}`, { waitUntil: 'domcontentloaded' });
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(600);
    await page.screenshot({ path: `${OUT}/${archivo}`, fullPage: true });
    console.log('[ok]', archivo);
};

try {
    const anon = await abrir();
    await captura(anon, '/login', '01-login.png');

    const admin = await abrir(process.env.ADMIN_USER || 'admin', process.env.ADMIN_PASS || 'admin');
    await captura(admin, '/admin', '02-admin-dashboard.png');
    await captura(admin, '/admin/estudiantes', '03-admin-estudiantes.png');
    await captura(admin, '/admin/resultados', '04-admin-resultados.png');
    await captura(admin, '/admin/integridad', '05-admin-integridad.png');
    await captura(admin, '/admin/reportes', '06-admin-reportes.png');

    const tee = await abrir(process.env.TEE_USER || 'tee', process.env.TEE_PASS || 'tee');
    await captura(tee, '/tribunal', '07-tribunal-dashboard.png');

    const jrv = await abrir(process.env.JRV_USER || 'jrv', process.env.JRV_PASS || 'jrv');
    await captura(jrv, '/registro', '08-pc1-registro.png');
    await captura(jrv, '/jrv', '09-pc2-jrv.png');

    const kiosko = await abrir();
    await captura(kiosko, '/kiosko', '10-pc3-urna-activacion.png');

    console.log('capturas completadas');
} catch (error) {
    console.error('[fallo]', String(error?.message || error));
    process.exitCode = 1;
} finally {
    await browser.close();
}
