<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('marque');
            $table->string('model');
            $table->year('annee');
            $table->decimal('prix', 12, 2);
            $table->string('kilometrage')->nullable();
            $table->string('carburant');
            $table->string('transmission');
            $table->string('couleur')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('disponible'); // available, sold
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
