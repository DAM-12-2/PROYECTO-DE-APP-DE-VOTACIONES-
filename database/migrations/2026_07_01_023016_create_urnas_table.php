<?php 
use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('urnas', function (Blueprint $table) {
            $table->id();
            // Original code used `id` randomly assigned by admin. We can use code instead.
            $table->string('codigo', 10)->unique();
            $table->string('horaactivacion', 20)->nullable();
            $table->integer('estado')->default(1)->comment('0: eliminada, 1: en espera, 2: activa');
            $table->integer('id_mesa')->nullable();
            $table->foreignId('id_estudiante')->nullable()->constrained('students')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('urnas');
    }
};