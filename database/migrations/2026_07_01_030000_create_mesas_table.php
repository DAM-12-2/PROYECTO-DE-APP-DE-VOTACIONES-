<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
return new class extends Migration { 
    public function up(): void {
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->nullable();
            $table->string('ubicacion', 100)->nullable();
            $table->boolean('estado')->default(1)->comment('1: activo 2: eliminado');
            $table->timestamps();
        });
    } 
    public function down():void {
        Schema::dropIfExists('mesas');
    } 
};