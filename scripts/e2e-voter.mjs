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

const resultados = { base: BASE, estudiante: ID, pasos: [] };
const marcar = (paso, detalle = '') => {
    resultados.pasos.push({ paso, detalle, ts: new Date().toISOString() });
    console.log(`[ok] ${paso} ${detalle}`);
};

try {
    const urna = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const jrv = await browser.newContext({ viewport: { width: 1280, height: 800 } });

    const pageUrna = await urna.newPage();
    const pageJrv = await jrv.newPage();

    pageUrna.on('pageerror', (e) => console.error('[urna pageerror]', e.message));
    pageUrna.on('console', (m) => {
        if (m.type() === 'error') console.error('[urna console]', m.text());
    });

    await pageUrna.goto(`${BASE}/kiosko`);
    await pageUrna.screenshot({ path: `${OUT}/01-urna-activacion.png` });
    marcar('Pantalla de activación de urna');

    await pageUrna.fill('#codigo', 'U001');
    await pageUrna.fill('#usuario', 'jrv');
    await pageUrna.fill('#password', 'jrv');
    await pageUrna.click('button[type="submit"]');
    await pageUrna.waitForSelector('#pantalla-espera:not(.hidden)', { timeout: 15000 });
    await pageUrna.screenshot({ path: `${OUT}/02-urna-espera.png` });
    marcar('Urna activada y en espera');

    await pageJrv.goto(`${BASE}/login`);
    await pageJrv.fill('#username', 'jrv');
    await pageJrv.fill('#password', 'jrv');
    await pageJrv.click('button[type="submit"]');
    await pageJrv.waitForURL('**/jrv', { timeout: 15000 });

    await pageJrv.fill('#buscar-input', ID);
    await pageJrv.click('#buscar-form button[type="submit"]');
    await pageJrv.waitForSelector('#resultado-estudiante:not(.hidden)', { timeout: 10000 });
    await pageJrv.screenshot({ path: `${OUT}/03-jrv-busqueda.png` });
    marcar('JRV encuentra al estudiante');

    await pageJrv.click('[data-asignar]');
    await pageJrv.waitForFunction(() => {
        const el = document.querySelector('[data-liberar]');
        return !!el;
    }, { timeout: 10000 });
    await pageJrv.screenshot({ path: `${OUT}/04-jrv-asignado.png` });
    marcar('JRV asigna al estudiante a la urna');

    await pageUrna.waitForSelector('#pantalla-votacion:not(.hidden)', { timeout: 15000 });
    await pageUrna.screenshot({ path: `${OUT}/05-urna-papeleta.png` });
    marcar('La urna muestra la papeleta');

    await pageUrna.click('#lista-candidatos label:first-of-type');
    await pageUrna.click('#btn-confirmar');
    await pageUrna.waitForSelector('#pantalla-confirmacion:not(.hidden)', { timeout: 5000 });
    await pageUrna.screenshot({ path: `${OUT}/06-urna-confirmacion.png` });
    marcar('Pantalla de confirmación');

    const t0 = Date.now();
    await pageUrna.click('#btn-enviar-voto');
    await pageUrna.waitForSelector('#pantalla-exito:not(.hidden)', { timeout: 15000 });
    resultados.latencia_voto_ms = Date.now() - t0;
    await pageUrna.screenshot({ path: `${OUT}/07-urna-exito.png` });
    marcar('Voto registrado', `${resultados.latencia_voto_ms} ms`);

    await pageJrv.waitForTimeout(4500);
    await pageJrv.screenshot({ path: `${OUT}/08-jrv-urna-liberada.png` });
    marcar('La urna se libera tras el voto');

    resultados.exito = true;
} catch (error) {
    resultados.exito = false;
    resultados.error = String(error?.message || error);
    console.error('[fallo]', resultados.error);
} finally {
    await browser.close();
    fs.writeFileSync(`${OUT}/e2e-resultado.json`, JSON.stringify(resultados, null, 2));
    process.exit(resultados.exito ? 0 : 1);
}
