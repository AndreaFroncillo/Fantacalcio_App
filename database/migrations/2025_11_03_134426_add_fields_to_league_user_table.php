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
        Schema::table('league_user', function (Blueprint $table) {
            $table->integer('credits')->default(500);
            $table->integer('goalkeepers')->default(0);
            $table->integer('defenders')->default(0);
            $table->integer('midfielders')->default(0);
            $table->integer('forwards')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('league_user', function (Blueprint $table) {
            $table->dropColumn(['credits', 'goalkeepers', 'defenders', 'midfielders', 'forwards']);
        });
    }
};
