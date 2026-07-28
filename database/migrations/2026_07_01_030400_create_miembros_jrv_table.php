<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {


 public function up(): void
 {


  Schema::create('miembros_jrv', function (Blueprint $table) {

   $table->id();

   $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

   $table->foreignId('party_id')->nullable()->constrained('parties')->onDelete('cascade')->comment('null significa asignado al TEE');
   //nullable() indica que el campo puede quedar vacio 

   $table->foreignId('mesa_id')->constrained('mesas')->onDelete('cascade');

   $table->integer('puesto')->comment('1: miembro, 2: fiscal, 3: auxiliar, 4: presidente');

   $table->integer('estado')->default(1)->comment('1: activo, 2: inactivo');
   $table->timestamps();
  });
 }



 public function down(): void
 {
  Schema::dropIfExists('miembros_jrv');
 }
};
