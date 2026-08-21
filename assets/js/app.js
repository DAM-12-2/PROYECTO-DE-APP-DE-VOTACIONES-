document.addEventListener('DOMContentLoaded', () => {
    const routerView = document.getElementById('router-view');
    const navLinks = document.querySelectorAll('.nav-link');
    const viewTitle = document.getElementById('view-title');

    const templates = {
        'dashboard': `
            <div class="space-y-stack-lg">
                <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                        <p class="text-xs font-bold text-secondary uppercase mb-2">Padr\u00f3n</p>
                        <h3 class="text-3xl font-bold text-primary">0</h3>
                        <p class="text-[10px] text-secondary mt-2 flex items-center gap-1"><span class="material-symbols-outlined text-xs">pending</span> Pendiente de carga</p>
                    </div>
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                        <p class="text-xs font-bold text-secondary uppercase mb-2">Tribunal Estudiantil</p>
                        <h3 class="text-3xl font-bold text-primary">0</h3>
                        <p class="text-[10px] text-secondary mt-2">Sin miembros asignados</p>
                    </div>
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                        <p class="text-xs font-bold text-secondary uppercase mb-2">Partidos</p>
                        <h3 class="text-3xl font-bold text-primary">0</h3>
                        <p class="text-[10px] text-secondary mt-2">Agrupaciones inscritas</p>
                    </div>
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                        <p class="text-xs font-bold text-secondary uppercase mb-2">JRV</p>
                        <h3 class="text-3xl font-bold text-primary">0</h3>
                        <p class="text-[10px] text-secondary mt-2">Mesas configuradas</p>
                    </div>
                </section>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-headline-md text-primary">Proyecci\u00f3n de Participaci\u00f3n</h3>
                            <span class="text-xs text-secondary">Basado en datos actuales</span>
                        </div>
                        <div class="h-64 relative">
                            <canvas id="graficoVotos"></canvas>
                        </div>
                    </div>
                    <div class="bg-primary text-on-primary rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="font-headline-md mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined">security</span>
                                Salud del Sistema
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2 border-b border-on-primary/10">
                                    <span class="text-sm">Base de Datos</span>
                                    <span class="text-[10px] bg-green-500 text-white px-2 py-0.5 rounded">CONECTADA</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-on-primary/10">
                                    <span class="text-sm">Servicios Web</span>
                                    <span class="text-[10px] bg-green-500 text-white px-2 py-0.5 rounded">OK</span>
                                </div>
                            </div>
                        </div>
                        <button class="mt-8 w-full py-3 bg-on-primary text-primary rounded font-bold hover:bg-surface transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">sync</span>
                            Sincronizar Datos
                        </button>
                    </div>
                </div>
            </div>
        `,
        'estudiantes': `
            <div class="space-y-stack-lg">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="relative flex-1 max-w-md">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary">search</span>
                        <input class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded-lg focus:ring-primary focus:border-primary" placeholder="Buscar por C\u00e9dula o Nombre..." type="text"/>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-surface border border-outline-variant text-primary rounded-lg flex items-center gap-2 hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-sm">upload_file</span> Carga Masiva Excel (Paso 1)
                        </button>
                        <button class="px-4 py-2 bg-primary text-on-primary rounded-lg flex items-center gap-2 shadow-md" data-modal="registro-estudiante">
                            <span class="material-symbols-outlined text-sm">person_add</span> Registro Manual
                        </button>
                    </div>
                </div>
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low border-b border-outline-variant">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-secondary uppercase">Identificaci\u00f3n</th>
                                <th class="px-6 py-4 text-xs font-bold text-secondary uppercase">Nombre Completo</th>
                                <th class="px-6 py-4 text-xs font-bold text-secondary uppercase">Secci\u00f3n</th>
                                <th class="px-6 py-4 text-xs font-bold text-secondary uppercase text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <tr>
                                <td class="px-6 py-12 text-center" colspan="4">
                                    <div class="flex flex-col items-center">
                                        <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">person_search</span>
                                        <p class="text-secondary italic">No hay estudiantes registrados en el padr\u00f3n.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `,
        'tribunal-estudiantil': `
            <div class="space-y-stack-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                        <h3 class="font-headline-md text-primary mb-6">Asignaci\u00f3n de Cargos - Tribunal</h3>
                        <div class="space-y-4">
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-secondary">Presidente(a) (ID)</label>
                                <input class="w-full p-2 border border-outline-variant rounded bg-surface focus:ring-1 focus:ring-primary outline-none" placeholder="C\u00e9dula del estudiante" type="text"/>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-secondary">Secretario(a) (ID)</label>
                                <input class="w-full p-2 border border-outline-variant rounded bg-surface focus:ring-1 focus:ring-primary outline-none" placeholder="C\u00e9dula del estudiante" type="text"/>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-secondary">Vocal 1 (ID)</label>
                                    <input class="p-2 border border-outline-variant rounded bg-surface focus:ring-1 focus:ring-primary outline-none" placeholder="C\u00e9dula" type="text"/>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-secondary">Vocal 2 (ID)</label>
                                    <input class="p-2 border border-outline-variant rounded bg-surface focus:ring-1 focus:ring-primary outline-none" placeholder="C\u00e9dula" type="text"/>
                                </div>
                            </div>
                            <button class="w-full py-3 bg-primary text-on-primary rounded font-bold shadow-md hover:opacity-90 transition-opacity mt-4">Guardar y Validar Tribunal</button>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                        <h3 class="font-headline-md text-primary mb-4">Miembros Activos</h3>
                        <div class="flex flex-col items-center justify-center h-full py-12 text-center text-secondary border-2 border-dashed border-outline-variant rounded-lg">
                            <span class="material-symbols-outlined text-4xl mb-2">group_off</span>
                            <p class="text-sm">No hay miembros asignados actualmente.</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        'partidos': `
            <div class="space-y-stack-lg">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                        <h3 class="font-headline-md text-primary mb-6">Crear Agrupaci\u00f3n Pol\u00edtica</h3>
                        <div class="space-y-4">
                            <div class="w-full h-48 bg-surface-container-low border-2 border-dashed border-outline-variant rounded-lg flex flex-col items-center justify-center text-secondary cursor-pointer hover:bg-surface-container-high transition-colors overflow-hidden">
                                <span class="material-symbols-outlined text-4xl">upload_file</span>
                                <span class="text-sm mt-2 font-bold uppercase">Cargar Bandera / Foto</span>
                                <p class="text-[10px] mt-1">Formato: JPG, PNG (Max 5MB)</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-secondary">Nombre del Partido</label>
                                    <input class="p-2 border border-outline-variant rounded bg-surface" placeholder="Ej. Renace AIRA" type="text"/>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-secondary">Siglas</label>
                                    <input class="p-2 border border-outline-variant rounded bg-surface" placeholder="Ej. RA" type="text"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-secondary">Presidente(a) de la Agrupaci\u00f3n (ID)</label>
                                <input class="w-full p-2 border border-outline-variant rounded bg-surface" placeholder="C\u00e9dula del estudiante" type="text"/>
                            </div>
                            <button class="w-full py-3 bg-primary text-on-primary rounded font-bold shadow-md">Registrar Partido</button>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                        <h3 class="font-headline-md text-primary mb-6">Armado de Gabinete</h3>
                        <p class="text-sm text-secondary mb-4">Seleccione un partido para configurar sus candidatos a puestos espec\u00edficos.</p>
                        <div class="border-2 border-dashed border-outline-variant rounded-lg h-64 flex flex-col items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-4xl mb-2">view_list</span>
                            <p class="text-sm italic">Pendiente de selecci\u00f3n</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        'jrv': `
            <div class="space-y-stack-lg">
                <div class="flex justify-between items-center">
                    <h3 class="font-headline-md text-primary">Gesti\u00f3n de Mesas y Urnas</h3>
                    <button class="px-4 py-2 bg-primary text-on-primary rounded-lg text-sm font-bold flex items-center gap-2 shadow-md" data-modal="nueva-mesa">
                        <span class="material-symbols-outlined text-sm">add</span> Nueva Mesa (JRV)
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                        <h4 class="font-bold text-primary mb-4 flex items-center gap-2"><span class="material-symbols-outlined">settings_input_component</span> Configuraci\u00f3n de Urnas</h4>
                        <div class="p-4 bg-secondary-container/10 border border-secondary/20 rounded-lg mb-4">
                            <p class="text-xs text-secondary italic">Regla: Cada mesa debe contar con 4 urnas electr\u00f3nicas con ID \u00fanico correlativo.</p>
                        </div>
                        <div class="space-y-2 opacity-50">
                            <div class="flex items-center justify-between p-3 border border-outline-variant rounded bg-surface">
                                <span class="text-sm font-bold">Urna #001</span>
                                <span class="text-[10px] bg-outline-variant text-white px-2 py-0.5 rounded">NO ASIGNADA</span>
                            </div>
                            <div class="flex items-center justify-between p-3 border border-outline-variant rounded bg-surface">
                                <span class="text-sm font-bold">Urna #002</span>
                                <span class="text-[10px] bg-outline-variant text-white px-2 py-0.5 rounded">NO ASIGNADA</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                        <h4 class="font-bold text-primary mb-4 flex items-center gap-2"><span class="material-symbols-outlined">assignment_ind</span> Asignaci\u00f3n de Secciones</h4>
                        <p class="text-sm text-secondary mb-4">Defina qu\u00e9 secciones votar\u00e1n en cada mesa configurada.</p>
                        <div class="flex flex-col items-center justify-center py-12 border-2 border-dashed border-outline-variant rounded-lg text-secondary">
                            <span class="material-symbols-outlined text-4xl mb-2">table_restaurant</span>
                            <p class="text-sm">Sin mesas activas para asignar secciones.</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        'votaciones': `
            <div class="space-y-stack-lg">
                <div class="max-w-2xl mx-auto bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-12 bg-orange-50 text-center transition-colors duration-500" id="master-switch-bg">
                        <span class="material-symbols-outlined text-7xl text-orange-500 mb-4" id="master-icon">lock_clock</span>
                        <h3 class="font-headline-xl text-primary" id="master-title">Sistema Cerrado</h3>
                        <p class="text-secondary mt-2" id="master-desc">Las mesas no pueden recibir votos en este estado.</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="flex items-center justify-between p-6 bg-surface rounded-xl border border-outline-variant">
                            <div>
                                <p class="font-bold text-primary">Control Global de Estado</p>
                                <p class="text-xs text-secondary italic">Activa la interfaz de votaci\u00f3n en tiempo real.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input class="sr-only peer" id="master-poll-switch" type="checkbox"/>
                                <div class="w-14 h-7 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold text-secondary uppercase">Rango Horario Permitido</label>
                            <div class="flex items-center gap-4">
                                <input class="flex-1 p-2 border border-outline-variant rounded bg-surface" type="time" value="07:00"/>
                                <span class="text-secondary font-bold">A</span>
                                <input class="flex-1 p-2 border border-outline-variant rounded bg-surface" type="time" value="16:00"/>
                            </div>
                        </div>
                        <div class="bg-error-container/20 border border-error/10 p-4 rounded-xl flex gap-3">
                            <span class="material-symbols-outlined text-error">info</span>
                            <p class="text-xs text-on-error-container font-medium">Al abrir las votaciones, el padr\u00f3n y la configuraci\u00f3n de partidos se bloquear\u00e1n permanentemente.</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        'resultados': `
            <div class="space-y-stack-lg">
                <div class="flex flex-col items-center justify-center p-12 bg-surface-container-low border-2 border-dashed border-outline-variant rounded-2xl text-center" id="resultados-lock">
                    <span class="material-symbols-outlined text-7xl text-outline-variant mb-4">visibility_off</span>
                    <h3 class="font-headline-lg text-primary mb-2">Acceso Restringido</h3>
                    <p class="text-secondary max-w-md mx-auto">Los resultados est\u00e1n deshabilitados por configuraci\u00f3n de seguridad. Consulte con el administrador para habilitarlos en la secci\u00f3n de Ajustes.</p>
                    <div class="mt-6 p-3 bg-primary-container/5 rounded-lg border border-primary-container/20">
                        <p class="text-xs text-primary font-medium">Consulte con el Tribunal Estudiantil para habilitar este m\u00f3dulo.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 hidden" id="resultados-content">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col">
                        <div class="p-6 border-b border-outline-variant"><h3 class="font-headline-md text-primary">Resumen de Escrutinio</h3></div>
                        <table class="w-full text-left">
                            <thead class="bg-surface-container-low border-b border-outline-variant">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-secondary uppercase">Partido</th>
                                    <th class="px-6 py-4 text-xs font-bold text-secondary uppercase text-center">Votos</th>
                                    <th class="px-6 py-4 text-xs font-bold text-secondary uppercase text-right">Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <tr>
                                    <td class="px-6 py-4 font-medium text-primary">Partido A</td>
                                    <td class="px-6 py-4 text-center font-mono">0</td>
                                    <td class="px-6 py-4 text-right font-bold text-secondary">0.00%</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 font-medium text-primary">Partido B</td>
                                    <td class="px-6 py-4 text-center font-mono">0</td>
                                    <td class="px-6 py-4 text-right font-bold text-secondary">0.00%</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-primary text-on-primary">
                                <tr>
                                    <td class="px-6 py-3 font-bold">TOTAL</td>
                                    <td class="px-6 py-3 text-center font-bold">0</td>
                                    <td class="px-6 py-3 text-right font-bold">0.00%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-6">
                        <h3 class="font-headline-md text-primary mb-8">Distribuci\u00f3n Visual</h3>
                        <div class="h-64 relative">
                            <canvas id="graficoResultados" aria-label="Gráfico de votos por partido"></canvas>
                        </div>
                    </div>
                    <div class="lg:col-span-2 flex justify-end">
                        <a class="px-4 py-2 bg-primary text-on-primary rounded-lg text-sm font-bold flex items-center gap-2 shadow-md" href="/resultados/exportar-csv">
                            <span class="material-symbols-outlined text-sm">download</span>
                            Descargar reporte CSV
                        </a>
                    </div>
                </div>
            </div>
        `,
        'estructura': `
            <div class="space-y-stack-lg">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-outline-variant flex items-center justify-between bg-surface-container-low">
                        <div>
                            <h3 class="font-headline-md text-primary">Niveles y Secciones</h3>
                            <p class="text-sm text-secondary">Organizaci\u00f3n acad\u00e9mica para el padr\u00f3n electoral.</p>
                        </div>
                        <button class="px-4 py-2 bg-primary text-on-primary rounded-lg text-sm font-bold flex items-center gap-2 shadow-md" data-modal="nueva-seccion">
                            <span class="material-symbols-outlined text-sm">add</span> A\u00f1adir Secci\u00f3n
                        </button>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="space-y-3">
                                <h4 class="font-bold text-secondary uppercase text-[10px] tracking-widest border-b pb-1">Tercer Ciclo</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">7-1</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">7-2</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">7-3</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">7-4</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">8-1</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">8-2</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">8-3</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">8-4</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">9-1</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">9-2</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">9-3</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">9-4</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="font-bold text-secondary uppercase text-[10px] tracking-widest border-b pb-1">Educaci\u00f3n Diversificada</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">10-1</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">10-2</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">10-3</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">10-4</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">11-1</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">11-2</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">11-3</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">11-4</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">12-1</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">12-2</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">12-3</span>
                                    <span class="px-3 py-1 bg-surface border border-outline-variant rounded text-sm font-medium">12-4</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <h4 class="font-bold text-secondary uppercase text-[10px] tracking-widest border-b pb-1">Programas Especiales</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-primary-container/10 text-primary border border-primary-container/20 rounded text-sm font-bold">Plan Nacional A</span>
                                    <span class="px-3 py-1 bg-primary-container/10 text-primary border border-primary-container/20 rounded text-sm font-bold">Plan Nacional B</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `,
        'ayuda': `
            <div class="space-y-stack-lg">
                <div class="max-w-3xl mx-auto bg-surface-container-lowest border border-outline-variant rounded-2xl p-12 text-center shadow-lg">
                    <div class="w-24 h-24 bg-primary-container/10 text-primary rounded-full flex items-center justify-center mx-auto mb-8">
                        <span class="material-symbols-outlined text-5xl">support_agent</span>
                    </div>
                    <h3 class="font-headline-xl text-primary mb-4">\u00bfNecesitas Soporte T\u00e9cnico?</h3>
                    <p class="text-body-lg text-secondary mb-8">Si experimentas dificultades con la carga de datos, la sincronizaci\u00f3n de urnas o el sistema de votaci\u00f3n:</p>
                    <div class="p-6 bg-surface-container-low rounded-xl border border-outline-variant inline-block">
                        <p class="font-bold text-primary text-xl">Contactar con el Departamento de Inform\u00e1tica</p>
                        <p class="text-secondary font-medium">Colegio T\u00e9cnico Profesional Ambientalista Isa\u00edas Retana Arias (CTP AIRA)</p>
                    </div>
                    <div class="mt-8 flex justify-center gap-4">
                        <button class="px-6 py-3 bg-primary text-on-primary rounded-lg font-bold shadow-md">Enviar Ticket</button>
                        <button class="px-6 py-3 border border-outline-variant text-primary rounded-lg font-bold">Manual de Usuario</button>
                    </div>
                </div>
            </div>
        `,
        'configuracion': `
            <div class="space-y-stack-lg">
                <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                        <h3 class="font-headline-md text-primary mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined">rule</span> Reglas de Victoria
                        </h3>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium">Umbral M\u00ednimo (%)</label>
                                <input class="w-20 p-2 border border-outline-variant rounded bg-surface" type="number" value="40"/>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-outline-variant">
                                <div>
                                    <p class="text-sm font-medium">Habilitar Visualizaci\u00f3n de Resultados</p>
                                    <p class="text-[10px] text-secondary">Permite el acceso al m\u00f3dulo de escrutinio en tiempo real.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input class="sr-only peer" id="toggle-results-visibility" type="checkbox"/>
                                    <div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                        <h3 class="font-headline-md text-error mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined">warning</span> Zona de Peligro
                        </h3>
                        <div class="space-y-4">
                            <button class="w-full py-2.5 border border-error text-error rounded font-bold hover:bg-error/5 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">restart_alt</span> Reiniciar Conteo de Votos
                            </button>
                            <button class="w-full py-2.5 bg-error text-on-error rounded font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">delete_forever</span> Reseteo Total del Sistema
                            </button>
                            <p class="text-[10px] text-secondary italic text-center">Estas acciones son irreversibles y requieren confirmaci\u00f3n administrativa.</p>
                        </div>
                    </div>
                </section>
            </div>
        `
    };

    function attachVotacionesListeners() {
        const pollSwitch = document.getElementById('master-poll-switch');
        const statusDot = document.getElementById('global-status-dot');
        const statusText = document.getElementById('global-status-text');
        const switchBg = document.getElementById('master-switch-bg');
        const masterIcon = document.getElementById('master-icon');
        const masterTitle = document.getElementById('master-title');
        const masterDesc = document.getElementById('master-desc');

        if(pollSwitch) {
            pollSwitch.addEventListener('change', (e) => {
                if(e.target.checked) {
                    statusDot.classList.replace('bg-orange-500', 'bg-green-500');
                    statusText.innerText = 'Votaciones ABIERTAS';
                    statusText.classList.replace('text-primary', 'text-green-700');

                    switchBg.classList.replace('bg-orange-50', 'bg-green-50');
                    masterIcon.innerText = 'how_to_vote';
                    masterIcon.classList.replace('text-orange-500', 'text-green-600');
                    masterTitle.innerText = 'Sistema en Vivo';
                    masterDesc.innerText = 'Los estudiantes pueden emitir sus votos en las urnas sincronizadas.';
                } else {
                    statusDot.classList.replace('bg-green-500', 'bg-orange-500');
                    statusText.innerText = 'En Preparaci\u00f3n';
                    statusText.classList.replace('text-green-700', 'text-primary');

                    switchBg.classList.replace('bg-green-50', 'bg-orange-50');
                    masterIcon.innerText = 'lock_clock';
                    masterIcon.classList.replace('text-green-600', 'text-orange-500');
                    masterTitle.innerText = 'Sistema Cerrado';
                    masterDesc.innerText = 'Las mesas no pueden recibir votos en este estado.';
                }
            });
        }
    }

    let resultsEnabled = false;
    let dashboardChart = null;
    let resultsChart = null;

    async function loadChartData(chart) {
        if (!chart) return;

        try {
            const response = await fetch('/api/ganador', { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

            const payload = await response.json();
            const partidos = Array.isArray(payload.partidos) ? payload.partidos : [];
            chart.data.labels = partidos.map(partido => partido.siglas || partido.nombre);
            chart.data.datasets[0].data = partidos.map(partido => partido.votos || 0);
            chart.update();
        } catch (error) {
            console.error('No se pudieron cargar los resultados:', error);
        }
    }

    function listenForStudentUpdates() {
        if (!window.Echo || !window.Echo.channel) return;

        window.Echo.channel('students-channel')
            .listen('.student.updated', () => {
                loadChartData(dashboardChart);
                loadChartData(resultsChart);
            });
    }

    function setResultsVisibility(enabled) {
        resultsEnabled = enabled;
        const toggle = document.getElementById('toggle-results-visibility');
        if (toggle) toggle.checked = enabled;
        const lock = document.getElementById('resultados-lock');
        const content = document.getElementById('resultados-content');
        if (!lock || !content) return;
        if (enabled) {
            lock.classList.add('hidden');
            content.classList.remove('hidden');
        } else {
            lock.classList.remove('hidden');
            content.classList.add('hidden');
        }
    }

    function attachResultadosListeners() {
        const toggle = document.getElementById('toggle-results-visibility');
        if (toggle) {
            toggle.addEventListener('change', (e) => {
                if (e.target.checked) {
                    e.target.checked = false;
                    showModal('Habilitar Resultados', `
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">visibility</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-primary mb-2">Habilitar Resultados</p>
                                <p class="text-sm text-secondary">Advertencia: Habilitar la visualizaci\u00f3n de resultados permite el acceso a datos sensibles del escrutinio en tiempo real. \u00bfEst\u00e1 seguro de continuar?</p>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button class="px-4 py-2 border border-outline-variant rounded text-sm font-medium text-secondary hover:bg-surface-container-high modal-close-btn">Cancelar</button>
                            <button class="px-4 py-2 bg-primary text-on-primary rounded text-sm font-bold shadow-md" id="confirm-results-btn">S\u00ed, habilitar</button>
                        </div>
                    `);
                    setTimeout(() => {
                        const confirmBtn = document.getElementById('confirm-results-btn');
                        if (confirmBtn) {
                            confirmBtn.addEventListener('click', () => {
                                setResultsVisibility(true);
                                hideModal();
                            });
                        }
                    }, 50);
                } else {
                    setResultsVisibility(false);
                }
            });
        }
        setResultsVisibility(resultsEnabled);
    }

    function showModal(title, formHtml) {
        const existing = document.getElementById('modal-overlay');
        if (existing) existing.remove();

        const overlay = document.createElement('div');
        overlay.id = 'modal-overlay';
        overlay.className = 'fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4';

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) hideModal();
        });

        overlay.innerHTML = `
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-outline-variant">
                    <h3 class="font-headline-md text-primary">${title}</h3>
                    <button class="p-2 text-secondary hover:bg-surface-container-high rounded-full modal-close-btn">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-6">${formHtml}</div>
            </div>
        `;

        document.body.appendChild(overlay);
        overlay.querySelector('.modal-close-btn').addEventListener('click', hideModal);

        const form = overlay.querySelector('form');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const modalType = form.dataset.modalType;
                let url = '';
                let method = 'POST';
                if (modalType === 'registro-estudiante') {
                    url = '/admin/estudiantes';
                } else if (modalType === 'nueva-mesa') {
                    url = '/admin/mesas';
                } else if (modalType === 'nueva-seccion') {
                    url = '/admin/mesas/' + (formData.get('mesa_id') || '') + '/secciones';
                }
                if (url) {
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    formData.append('_method', 'POST');
                    try {
                        const resp = await fetch(url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        if (resp.ok) { hideModal(); location.reload(); }
                        else { const err = await resp.json(); alert(err.message || 'Error al guardar.'); }
                    } catch { alert('Error de conexión.'); }
                } else {
                    hideModal();
                }
            });
        }
    }

    function hideModal() {
        const overlay = document.getElementById('modal-overlay');
        if (overlay) overlay.remove();
    }

    function attachModals() {
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.dataset.modal;
                const forms = {
                    'registro-estudiante': {
                        title: 'Registro Manual de Estudiante',
                        html: `
                            <form data-modal-type="registro-estudiante" class="space-y-4">
                                    <input class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none" type="text" placeholder="Ej. 1-2345-6789" name="identificacion" required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-secondary uppercase mb-1.5 block">Nombre Completo</label>
                                    <input class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none" type="text" placeholder="Nombre y apellidos" name="nombre" required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-secondary uppercase mb-1.5 block">Secci\u00f3n</label>
<select name="seccion" class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                                <option value="">Seleccione una sección</option>
                                <optgroup label="Tercer Ciclo">
                                            <option>7-1</option><option>7-2</option><option>7-3</option><option>7-4</option>
                                            <option>8-1</option><option>8-2</option><option>8-3</option><option>8-4</option>
                                            <option>9-1</option><option>9-2</option><option>9-3</option><option>9-4</option>
                                        </optgroup>
                                        <optgroup label="Educaci\u00f3n Diversificada">
                                            <option>10-1</option><option>10-2</option><option>10-3</option><option>10-4</option>
                                            <option>11-1</option><option>11-2</option><option>11-3</option><option>11-4</option>
                                            <option>12-1</option><option>12-2</option><option>12-3</option><option>12-4</option>
                                        </optgroup>
                                        <optgroup label="Programas Especiales">
                                            <option>Plan Nacional A</option><option>Plan Nacional B</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <button class="w-full py-3.5 bg-primary text-on-primary rounded-xl font-bold shadow-md hover:opacity-90 transition-opacity" type="submit">Guardar Estudiante</button>
                            </form>
                        `
                    },
                    'nueva-mesa': {
                        title: 'Nueva Mesa (JRV)',
                        html: `
                            <form data-modal-type="nueva-mesa" class="space-y-4">
                                <div>
                                    <label class="text-xs font-bold text-secondary uppercase mb-1.5 block">N\u00famero de Mesa</label>
                                    <input class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none" type="text" placeholder="Ej. Mesa #001" name="numero" required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-secondary uppercase mb-1.5 block">Ubicaci\u00f3n</label>
                                    <input class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none" type="text" placeholder="Ej. Aula 12, Pasillo Central" name="ubicacion" required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-secondary uppercase mb-1.5 block">Cantidad de Urnas</label>
                                    <input name="cantidad_urnas" class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none" type="number" min="1" max="8" value="4" required>
                                </div>
                                <div class="p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                                    <p class="text-xs text-secondary italic">Las urnas se crear\u00e1n autom\u00e1ticamente con ID correlativo.</p>
                                </div>
                                <button class="w-full py-3.5 bg-primary text-on-primary rounded-xl font-bold shadow-md hover:opacity-90 transition-opacity" type="submit">Crear Mesa</button>
                            </form>
                        `
                    },
                    'nueva-seccion': {
                        title: 'A\u00f1adir Secci\u00f3n',
                        html: `
                            <form data-modal-type="nueva-seccion" class="space-y-4">
                                <div>
                                    <label class="text-xs font-bold text-secondary uppercase mb-1.5 block">Nombre de la Secci\u00f3n</label>
                                    <input class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none" type="text" placeholder="Ej. 13-1, 13-2, Taller A" name="nombre" required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-secondary uppercase mb-1.5 block">Nivel</label>
                                    <select name="nivel" class="w-full p-3 border border-outline-variant rounded-xl bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none" required>
                                        <option value="">Seleccione un nivel</option>
                                        <option>Tercer Ciclo</option>
                                        <option>Educaci\u00f3n Diversificada</option>
                                        <option>Programas Especiales</option>
                                    </select>
                                </div>
                                <button class="w-full py-3.5 bg-primary text-on-primary rounded-xl font-bold shadow-md hover:opacity-90 transition-opacity" type="submit">Guardar Secci\u00f3n</button>
                            </form>
                        `
                    }
                };

                const config = forms[modal];
                if (config) showModal(config.title, config.html);
            });
        });
    }

    async function switchSection(sectionId) {
        navLinks.forEach(link => {
            if (link.dataset.target === sectionId) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        let displayTitle = sectionId.replace(/-/g, ' ');
        if (sectionId === 'jrv') displayTitle = 'Log\u00edstica JRV';
        if (sectionId === 'tribunal-estudiantil') displayTitle = 'Tribunal Estudiantil';
        if (sectionId === 'configuracion') displayTitle = 'Ajustes';
        if (sectionId === 'votaciones') displayTitle = 'Control de Votaci\u00f3n';

        viewTitle.innerText = displayTitle;

        if (templates[sectionId]) {
            routerView.innerHTML = templates[sectionId];

            // Inicializar grafico del dashboard
            if (sectionId === 'dashboard') {
                const ctx = document.getElementById('graficoVotos');
                if (ctx && typeof Chart !== 'undefined') {
                    if (dashboardChart) dashboardChart.destroy();
                    dashboardChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Votos',
                                data: [],
                                backgroundColor: ['#3b82f6', '#ef4444', '#9ca3af']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });

                    try {
                        const response = await fetch('/api/ganador');
                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }

                        const res = await response.json();
                        const partidos = Array.isArray(res.partidos) ? res.partidos : [];
                        dashboardChart.data.labels = partidos.map(p => p.siglas || p.nombre);
                        dashboardChart.data.datasets[0].data = partidos.map(p => p.votos || 0);
                        dashboardChart.update();
                    } catch (error) {
                        console.error('No se pudieron cargar los votos del dashboard:', error);
                    }
                } else if (ctx) {
                    console.warn('Chart.js no está cargado. Incluya la librería Chart.js para renderizar el gráfico.');
                }
            }

            if (sectionId === 'resultados') {
                const ctx = document.getElementById('graficoResultados');
                if (ctx && typeof Chart !== 'undefined') {
                    if (resultsChart) resultsChart.destroy();
                    resultsChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Votos',
                                data: [],
                                backgroundColor: '#3b82f6'
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                    loadChartData(resultsChart);
                }
            }
        } else {
            routerView.innerHTML = '<p class="text-secondary">Secci\u00f3n no encontrada.</p>';
        }

        if (sectionId === 'votaciones') {
            attachVotacionesListeners();
        }

        if (sectionId === 'resultados' || sectionId === 'configuracion') {
            attachResultadosListeners();
        }

        attachModals();

        document.querySelector('main').scrollTop = 0;
    }

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = link.dataset.target;
            if (target) switchSection(target);
        });
    });

    const logoutBtn = document.getElementById('logout-btn');
    if(logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            if(confirm('\u00bfEst\u00e1 seguro de que desea cerrar la sesi\u00f3n administrativa?')) {
                window.location.href = '/login';
            }
        });
    }

    const pathToSection = {
        '/tribunal': 'dashboard',
        '/tribunal/estudiantes': 'estudiantes',
        '/tribunal/configuracion': 'configuracion',
    };
    const initialSection = pathToSection[window.location.pathname] || 'dashboard';
    listenForStudentUpdates();
    switchSection(initialSection);
});
