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

 <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

  <!-- Formulario -->
  <div class="lg:col-span-1">
   <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <h2 class="text-lg font-bold text-slate-800 mb-4">Registrar Partido</h2>
    <form method="POST" action="/admin/partidos" enctype="multipart/form-data" class="space-y-4">
     @csrf
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Siglas</label>
      <input type="text" name="siglas" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none uppercase"
       placeholder="PAC">
     </div>
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre Completo</label>
      <input type="text" name="nombre" required
       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
       placeholder="Partido Acción Colegial">
     </div>

     <!-- Bandera / Logo -->
     <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Bandera / Logo (Opcional)</label>
      <div class="flex items-center space-x-3">
       <img id="banderaPreview" src="{{ asset('storage/img/placeholder-bandera.svg') }}" class="w-16 h-16 rounded-lg object-cover border border-slate-200" alt="Bandera">
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
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Foto Presidente (Opcional)</label>
      <div class="flex items-center space-x-3">
       <img id="presidentePreview" src="{{ asset('storage/img/placeholder-presidente.svg') }}" class="w-16 h-16 rounded-lg object-cover border border-slate-200" alt="Presidente">
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
