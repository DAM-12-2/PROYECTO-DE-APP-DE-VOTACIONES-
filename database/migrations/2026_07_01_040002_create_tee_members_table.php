<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void
 {
  Schema::create('tee_members', function (Blueprint $table) {
   $table->id();

   $table->foreignId('student_id')->constrained()->cascadeOnDelete();

   $table->string('puesto', 50)->nullable();

   $table->boolean('estado')->default(1);

   $table->timestamps();
  });
 }


 public function down(): void
 {
  Schema::dropIfExists('tee_members');
 }
};
