import { createRequire } from 'node:module';
import fs from 'fs';

const require = createRequire((process.env.NODE_PATH || process.cwd()) + '/');
const { chromium } = require('playwright');

const BASE = process.argv[2] || 'http://127.0.0.1:8124';
const OUT = process.argv[3] || 'docs/produccion/evidencias';
const ID = process.argv[4] || '1000001';

fs.mkdirSync(OUT, { recursive: true });

const browser = await chromium.launch({
    headless: true,
    executablePath: '/usr/bin/chromium',
    args: ['--no-sandbox'],
});

const resultado = { base: BASE, estudiante: ID, pasos: [] };
const marcar = (paso, extra = {}) => {
    resultado.pasos.push({ paso, ts: new Date().toISOString(), ...extra });
    console.log(`[ok] ${paso}`, Object.keys(extra).length ? JSON.stringify(extra) : '');
};

const login = async (page) => {
    await page.goto(`${BASE}/login`);
    await page.fill('#username', 'jrv');
    await page.fill('#password', 'jrv');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/jrv', { timeout: 15000 });
};

try {
    const c1 = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const c2 = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const c3 = await browser.newContext({ viewport: { width: 1280, height: 800 } });

    const pc1 = await c1.newPage();
    const pc2 = await c2.newPage();
    const pc3 = await c3.newPage();


    // PC3: activar urna
    await pc3.goto(`${BASE}/kiosko`);
    await pc3.fill('#codigo', 'U001');
    await pc3.fill('#usuario', 'jrv');
    await pc3.fill('#password', 'jrv');
    await Promise.all([pc3.waitForNavigation(), pc3.click('button[type="submit"]')]);
    await pc3.waitForSelector('#pantalla-espera:not(.hidden)', { timeout: 15000 });
    await pc3.screenshot({ path: `${OUT}/3pc-01-urna-espera.png` });
    marcar('PC3 urna activa y en espera');

    // PC1: login y registro
    await login(pc1);
    await pc1.goto(`${BASE}/registro`);
    await pc1.fill('#identificacion', ID);
    const tRegistro = Date.now();
    await pc1.click('#registro-form button[type="submit"]');
    await pc1.waitForSelector('#pantalla-espera:not(.hidden)', { timeout: 15000 });
    await pc1.screenshot({ path: `${OUT}/3pc-02-registro-solicitud.png` });
    marcar('PC1 solicitud enviada', { ms: Date.now() - tRegistro });

    // PC2: aprobar
    await login(pc2);
    await pc2.waitForSelector('#cola-solicitudes [data-aprobar]', { timeout: 15000 });
    await pc2.screenshot({ path: `${OUT}/3pc-03-jrv-cola.png` });
    marcar('PC2 recibe la solicitud');

    const tAprobar = Date.now();
    await pc2.click('#cola-solicitudes [data-aprobar]');
    await pc2.waitForSelector('#cola-solicitudes .text-emerald-700, #cola-solicitudes [data-aprobar]', { timeout: 10000 }).catch(() => {});
    await pc2.waitForTimeout(500);
    await pc2.screenshot({ path: `${OUT}/3pc-04-jrv-aprobada.png` });

    // PC1: ver aprobación
    await pc1.waitForSelector('#pantalla-aprobada:not(.hidden)', { timeout: 15000 });
    const urna = await pc1.textContent('#aprobada-urna');
    await pc1.screenshot({ path: `${OUT}/3pc-05-registro-aprobada.png` });
    marcar('PC1 recibe la aprobación', { ms: Date.now() - tAprobar, urna });

    // PC3: votar
    await pc3.waitForSelector('#pantalla-votacion:not(.hidden)', { timeout: 15000 });
    await pc3.screenshot({ path: `${OUT}/3pc-06-urna-papeleta.png` });
    marcar('PC3 recibe la papeleta');

    await pc3.click('#lista-candidatos label:first-of-type');
    await pc3.click('#btn-confirmar');
    await pc3.waitForSelector('#pantalla-confirmacion:not(.hidden)', { timeout: 5000 });
    await pc3.screenshot({ path: `${OUT}/3pc-07-urna-confirmacion.png` });

    const tVoto = Date.now();
    await pc3.click('#btn-enviar-voto');
    await pc3.waitForSelector('#pantalla-exito:not(.hidden)', { timeout: 15000 });
    const msVoto = Date.now() - tVoto;
    await pc3.screenshot({ path: `${OUT}/3pc-08-urna-exito.png` });
    marcar('PC3 voto registrado', { ms: msVoto });

    // PC1: ver votado
    await pc1.waitForSelector('#pantalla-votada:not(.hidden)', { timeout: 15000 });
    const msRetorno = Date.now() - tVoto;
    await pc1.screenshot({ path: `${OUT}/3pc-09-registro-votada.png` });
    marcar('PC1 recibe el voto', { ms: msRetorno });

    resultado.exito = true;
    resultado.latencias = { aprobacion_ms: msRetorno, voto_ms: msVoto };
} catch (error) {
    resultado.exito = false;
    resultado.error = String(error?.message || error);
    console.error('[fallo]', resultado.error);
} finally {
    await browser.close();
    fs.writeFileSync(`${OUT}/e2e-3pc-resultado.json`, JSON.stringify(resultado, null, 2));
    process.exit(resultado.exito ? 0 : 1);
}
