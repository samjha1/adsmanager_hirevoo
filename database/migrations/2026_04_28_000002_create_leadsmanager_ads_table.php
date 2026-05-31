<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leadsmanager_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')
                ->constrained('leadsmanager_campaigns')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('leadsmanager_users')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('headline');
            $table->text('body')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('cta_label', 64)->default('Learn More');
            $table->string('destination_url', 500);
            $table->string('placement', 64)->default('hirevo_homepage');
            $table->string('status', 32)->default('draft');
            $table->uuid('public_key')->unique();
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->unsignedBigInteger('leads_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('placement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leadsmanager_ads');
    }
};
