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
        $table->foreignId("student_id")->constrained("students")->onDelete('cascade');
        $table->foreignId("party_id")->constrained("parties")->onDelete('cascade');
        $table->timestamps();

  });
 }






 public function down()
 {
  Schema::dropIfExists("candidatos");

 }
};