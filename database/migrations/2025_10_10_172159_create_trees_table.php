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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id')->unique();
            $table->string('type')->nullable();
            $table->decimal('x', 10, 7);
            $table->decimal('y', 10, 7);
            $table->boolean('dead')->default(false);
            $table->string('latin_name')->nullable();
            $table->string('common_name')->nullable();
            $table->string('crown_height')->nullable();
            $table->string('tree_species')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};
