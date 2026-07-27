<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('hero_tagline')->nullable()->after('excerpt');
            $table->json('benefits')->nullable()->after('content');
            $table->json('process_steps')->nullable()->after('benefits');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['hero_tagline', 'benefits', 'process_steps']);
        });
    }
};
