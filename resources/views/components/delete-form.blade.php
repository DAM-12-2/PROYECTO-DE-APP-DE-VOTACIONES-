@props(['action', 'confirmMessage' => '¿Estás seguro de que deseas eliminar este elemento?'])

<form method="POST" action="{{ $action }}" class="inline" onsubmit="return confirm('{{ $confirmMessage }}')">
 @csrf
 @method('DELETE')
 <button type="submit" class="text-red-400 hover:text-red-600 transition-color-colors" title="Eliminar">
  <i class="ph ph-trash text-lg"></i>
 </button>
</form>
