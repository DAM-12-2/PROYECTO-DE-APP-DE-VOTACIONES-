<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
 public function up(): void
 {
  Schema::create('incidentes', function (Blueprint $table) {


   $table->id();

   $table->foreignId('mesa_id')->constrained('mesas')->onDelete('cascade');

   $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

   $table->text('detalle');

   $table->timestamps();
  });
 }

 public function down(): void
 {
  Schema::dropIfExists('incidentes');
 }
};
