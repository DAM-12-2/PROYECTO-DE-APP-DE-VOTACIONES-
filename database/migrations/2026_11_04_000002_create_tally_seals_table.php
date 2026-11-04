<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tally_seals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mesa_id')->unique();
            $table->string('hash', 64);
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('blancos')->default(0);
            $table->unsignedInteger('nulos')->default(0);
            $table->timestamp('sealed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tally_seals');
    }
};
