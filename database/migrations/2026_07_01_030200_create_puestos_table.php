<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; //Blueprint es la plantilla de la tabla 
use Illuminate\Support\Facades\Schema; // Facades es una interfaz que me permite interactuar con las tablas

return new class extends Migration {


 public function up(): void // Void me permite editar las cosas
 {
  Schema::create('puestos', function (Blueprint $table) {


   $table->id();

   $table->string('nombre', 50)->unique(); // unique() se asegura de que no se repita en la base de datos


   $table->integer('estado')->default(1)->comment('1: activo, 0: inactivo'); //En cas de que el estado 
   // no se indique el estado será activo, comment ->Inyecta documentación dentro del esquema.

   $table->timestamps(); //

  });
 }

 public function down(): void
 {
  Schema::dropIfExists('puestos');
 }
};
