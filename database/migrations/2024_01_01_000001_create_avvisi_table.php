<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avvisi', function (Blueprint $table) {
            $table->id();
            $table->string('titolo');
            $table->text('contenuto');
            $table->date('data_pubblicazione');
            $table->boolean('pubblicato')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avvisi');
    }
};
