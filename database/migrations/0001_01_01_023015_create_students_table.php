<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    
    public function up(): void {
            Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('identificacion', 20)->unique();
            $table->string('nombre', 50)->nullable();
            $table->string('apellidos', 50)->nullable();
            $table->string('seccion', 10)->nullable();
            $table->string('foto')->nullable();
            $table->string('huella')->nullable();
            $table->boolean('voto')->default(0)->comment('0: no ha votado, 1: ya voto');
            $table->boolean('estado')->default(1)->comment('1: activo 0: eliminado');
            $table->timestamps();
        });

    }

    public function down(): void {
        Schema::dropIfExists('students');
    }
    
};