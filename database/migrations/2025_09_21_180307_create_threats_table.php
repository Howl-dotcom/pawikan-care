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
    Schema::create('threats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('nest_id')->constrained('nests')->onDelete('cascade');
        $table->string('threat_type');
        $table->string('photo_path')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threats');
    }
};
