<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

 return new class extends Migration { 
    public function up(): void {
        Schema::create('secciones_mesa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->constrained('mesas')->onDelete('cascade');
            $table->string('seccion', 10);
            $table->timestamps();
  });

    } 

    public function down(): void {
        Schema::dropIfExists('secciones_mesa');
    }
};