<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('item_units', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); 
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); 
            $table->enum('status', ['available', 'borrowed', 'unknown'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_units');
    }
};
