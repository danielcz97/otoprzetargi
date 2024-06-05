<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('komunikaty', function (Blueprint $table) {
            $table->boolean('cyclic')->default(false);
            $table->integer('cyclic_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komunikaty', function (Blueprint $table) {
            $table->dropColumn('cyclic');
            $table->dropColumn('cyclic_day');
        });
    }
};
