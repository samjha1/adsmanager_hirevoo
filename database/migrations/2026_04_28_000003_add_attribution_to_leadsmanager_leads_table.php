<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leadsmanager_leads', function (Blueprint $table) {
            $table->foreignId('campaign_id')
                ->nullable()
                ->after('user_id')
                ->constrained('leadsmanager_campaigns')
                ->nullOnDelete();
            $table->foreignId('ad_id')
                ->nullable()
                ->after('campaign_id')
                ->constrained('leadsmanager_ads')
                ->nullOnDelete();
            $table->string('placement', 64)->nullable()->after('source');
            $table->string('referrer_url', 500)->nullable()->after('placement');
            $table->json('meta')->nullable()->after('notes');

            $table->index(['user_id', 'campaign_id']);
            $table->index('ad_id');
        });
    }

    public function down(): void
    {
        Schema::table('leadsmanager_leads', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
            $table->dropForeign(['ad_id']);
            $table->dropIndex(['user_id', 'campaign_id']);
            $table->dropIndex(['ad_id']);
            $table->dropColumn(['campaign_id', 'ad_id', 'placement', 'referrer_url', 'meta']);
        });
    }
};
