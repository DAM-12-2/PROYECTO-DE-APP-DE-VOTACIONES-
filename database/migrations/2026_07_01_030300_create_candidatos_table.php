<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration {


 public function up(): void
 {
  Schema::create("candidatos", function (Blueprint $table) {
   $table->id();
   $table->foreignId("puesto_id")->constrained("puestos")->onDelete('cascade');
   $table->foreingId("student_id")->constrained("students")->onDelate('cascade');
   $table->foreingId("party_id")->constrined("parties")->onDelate('cascade');
   $table->timestap();

  });
 }






 public function down()
 {
  Schema::dropIfExists("candidatos");

 }
};