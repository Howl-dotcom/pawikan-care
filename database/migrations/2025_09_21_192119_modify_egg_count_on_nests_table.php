<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // egg_count already exists in create_nests_table migration
        // This migration is a no-op to satisfy migration history
        if (Schema::hasColumn('nests', 'egg_count')) {
            return;
        }

        Schema::table('nests', function (Blueprint $table) {
            $table->integer('egg_count')->nullable();
        });
    }

    public function down(): void
    {
        // nothing to reverse
    }
};
