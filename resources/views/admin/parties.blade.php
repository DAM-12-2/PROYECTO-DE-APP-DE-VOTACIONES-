@extends('layouts.admin')

@section('title', 'Facciones / Partidos Políticos')

@section('content')

 @if(session('success'))
  <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
   {{ session('success') }}
  </div>
 @endif
 @if(session('error'))
  <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
   {{ session('error') }}
  </div>
 @endif

 @if(session('photo_import_errors'))
  <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl mb-6 text-sm">
   <p class="font-bold mb-1">Detalles de la importación</p>
   <ul class="list-disc pl-5 space-y-1">
    @foreach(session('photo_import_errors') as $error)
     <li>{{ $error }}</li>
    @endforeach
   </ul>
  </div>
 @endif

 <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

  <!-- Formulario -->
  <div class="lg:col-span-1">
   <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <h2 class="text-lg font-bold text-slate-800 mb-4">Registrar Partido</h2>
    <form method="POST" action="/admin/partidos" enctype="multipart/form-data" class="space-y-4">
     @csrf
     <div>
      <label class="block text-xs font-bold text-slate-500 mb-1">Siglas</label>
      <input type="text" name="siglas" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
       placeholder="PAC">
     </div>
     <div>
      <label class="block text-xs font-bold text-slate-500 mb-1">Nombre Completo</label>
      <input type="text" name="nombre" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
       placeholder="Partido Acción Colegial">
     </div>

     <!-- Bandera / Logo -->
     <div>
      <label class="block text-xs font-bold text-slate-500 mb-1">Bandera / Logo (Opcional)</label>
      <div class="flex items-center space-x-3">
       <img id="banderaPreview" src="{{ asset('img/placeholder-bandera.svg') }}" class="w-16 h-16 rounded-lg object-cover border border-slate-200" alt="Bandera">
       <label for="bandera" class="cursor-pointer">
        <span class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold px-3 py-2 rounded-lg transition-colors">
         Seleccionar archivo
        </span>
        <input type="file" name="bandera" id="bandera" accept="image/jpeg,image/png" class="hidden" onchange="previewBandera(this)">
       </label>
      </div>
      <p class="text-xs text-slate-400 mt-1">JPG o PNG, máx. 2MB</p>
     </div>

     <!-- Foto Presidente -->
     <div>
      <label class="block text-xs font-bold text-slate-500 mb-1">Foto Presidente (Opcional)</label>
      <div class="flex items-center space-x-3">
       <img id="presidentePreview" src="{{ asset('img/placeholder-presidente.svg') }}" class="w-16 h-16 rounded-lg object-cover border border-slate-200" alt="Presidente">
       <label for="fotopresidente" class="cursor-pointer">
        <span class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold px-3 py-2 rounded-lg transition-colors">
         Seleccionar archivo
        </span>
        <input type="file" name="fotopresidente" id="fotopresidente" accept="image/jpeg,image/png" class="hidden" onchange="previewPresidente(this)">
       </label>
      </div>
      <p class="text-xs text-slate-400 mt-1">JPG o PNG, máx. 2MB</p>
     </div>

     <button type="submit"
      class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition-all text-sm">
      Registrar Partido
     </button>
    </form>
   </div>
  </div>

  <!-- Listado -->
  <div class="lg:col-span-2">
   <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    @foreach ($parties as $p)
     <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-start justify-between">
      <div class="flex items-center">
       @if($p->bandera)
         <img src="{{ asset($p->bandera) }}?{{ @filemtime(public_path($p->bandera)) }}" class="w-12 h-12 rounded-xl object-cover mr-4 shrink-0 border border-slate-200" alt="{{ $p->siglas }}">
       @else
        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-lg mr-4 shrink-0 shadow-inner">
         {{ substr($p->siglas, 0, 2) }}
        </div>
       @endif
       <div>
        <h3 class="font-bold text-slate-800 text-lg">{{ $p->siglas }}</h3>
        <p class="text-sm text-slate-500">{{ $p->nombre }}</p>
        @if($p->fotopresidente)
          <img src="{{ asset($p->fotopresidente) }}?{{ @filemtime(public_path($p->fotopresidente)) }}" class="w-8 h-8 rounded-full object-cover mt-1 border border-slate-200" alt="Presidente">
        @endif
        <span
         class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-bold {{ $p->estado == 1 ? 'bg-teal-100 text-teal-700' : 'bg-red-100 text-red-600' }}">
         {{ $p->estado == 1 ? 'Activo' : 'Inactivo' }}
        </span>
       </div>
      </div>
      <div class="flex items-center gap-1">
       <a href="{{ route('admin.parties.edit', $p->id) }}" class="text-slate-400 hover:text-teal-600 transition-colors p-1" title="Editar">
        <i class="ph ph-pencil-simple text-lg"></i>
       </a>
        <form method="POST" action="/admin/partidos/{{ $p->id }}"
         onsubmit="return confirm('¿Eliminar partido {{ $p->siglas }}?')">
         @csrf
         @method('DELETE')
         <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-1" title="Eliminar">
          <i class="ph ph-trash text-lg"></i>
         </button>
        </form>
      </div>
     </div>
    @endforeach

    @if($parties->isEmpty())
     <div class="col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-200 text-center text-slate-400">
      <i class="ph ph-flag-banner text-4xl mb-2"></i>
      <p>No hay partidos registrados.</p>
     </div>
    @endif
   </div>
  </div>
 </div>

 <div class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
  <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
   <div class="max-w-2xl">
    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
     <i class="ph ph-images text-xl text-teal-600"></i> Importar fotos en lote
    </h2>
    <p class="text-sm text-slate-500 mt-1">
     Suba un ZIP con las banderas y fotos de presidente. El sistema reconoce el partido por las siglas en el nombre del archivo.
    </p>
    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600">
     <div class="rounded-lg bg-slate-50 border border-slate-200 px-3 py-2"><code>PVA_bandera.png</code> · bandera</div>
     <div class="rounded-lg bg-slate-50 border border-slate-200 px-3 py-2"><code>PVA_presidente.jpg</code> · presidente</div>
    </div>
    <p class="text-xs text-slate-400 mt-2">También acepta guion: <code>PVA-bandera.png</code>. Máximo 20 MB, 120 archivos y 2 MB por imagen.</p>
    <details class="mt-3 text-xs text-slate-500">
     <summary class="cursor-pointer font-semibold text-slate-700">Usar manifiesto CSV o Excel (opcional)</summary>
     <p class="mt-2">Incluya dentro del ZIP un archivo <code>manifest.csv</code>, <code>manifest.xlsx</code> o <code>manifest.xls</code> con las columnas:</p>
     <pre class="mt-2 bg-slate-50 border border-slate-200 rounded-lg p-3 overflow-x-auto">archivo,siglas,tipo
foto-bandera.png,PVA,bandera
foto-presidente.jpg,PVA,presidente</pre>
     <a href="{{ asset('plantilla-fotos-partidos.csv') }}" class="inline-block mt-2 text-teal-700 underline">Descargar plantilla CSV</a>
    </details>
   </div>

   <form method="POST" action="{{ route('admin.parties.import_photos') }}" enctype="multipart/form-data"
    class="w-full lg:w-96 shrink-0 space-y-3">
    @csrf
    <input type="file" name="zip_file" accept=".zip,application/zip" required
     class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
    @error('zip_file') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
    <button type="submit" class="w-full rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-4 text-sm transition">
     Importar fotos
    </button>
   </form>
  </div>
 </div>

 <script>
  function previewBandera(input) {
   if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
     document.getElementById('banderaPreview').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
   }
  }

  function previewPresidente(input) {
   if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
     document.getElementById('presidentePreview').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
   }
  }
 </script>

@endsection
