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
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('highest_rank')->nullable()->after('rank');
            $table->decimal('winrate', 5, 2)->nullable()->after('highest_rank'); // 0.00 - 100.00
            $table->integer('total_matches')->nullable()->after('winrate');
            $table->integer('honor')->default(0)->after('total_matches');
            $table->integer('glory')->default(0)->after('honor');
            $table->integer('immortal')->default(0)->after('glory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['highest_rank', 'winrate', 'total_matches', 'honor', 'glory', 'immortal']);
        });
    }
};
