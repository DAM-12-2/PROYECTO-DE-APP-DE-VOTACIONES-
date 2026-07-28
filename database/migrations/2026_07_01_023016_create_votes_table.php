<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { 
    
    public function up(): void {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->text('encrypted_party');
            $table->integer('id_mesa')->nullable();
            $table->timestamps();
        });
    } 

    public function down() {
        Schema::dropIfExists('votes');
    }
};