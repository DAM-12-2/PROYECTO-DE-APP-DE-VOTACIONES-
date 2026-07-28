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
