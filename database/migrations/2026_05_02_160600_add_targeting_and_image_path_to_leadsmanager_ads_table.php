<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leadsmanager_ads', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('body');
            $table->string('target_area', 160)->nullable()->after('status');
            $table->string('target_age_group', 64)->nullable()->after('target_area');
            $table->string('target_audience', 255)->nullable()->after('target_age_group');
        });
    }

    public function down(): void
    {
        Schema::table('leadsmanager_ads', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'target_area', 'target_age_group', 'target_audience']);
        });
    }
};
