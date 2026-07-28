<?php 
use Illuminate\Database\Migrations\Migration; 
return new class extends Migration { 
    public function up(): void {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('siglas', 20)->nullable();
            $table->string('nombre', 100)->nullable();
            $table->string('fotopresidente', 255)->nullable();
            $table->string('bandera', 255)->nullable();
            $table->boolean('estado')->default(1)->comment('1: activo 2: eliminado');
            $table->timestamps();
        });
    }

    public function down(): void {
        schema::dropIfExists('parties');
    }
};