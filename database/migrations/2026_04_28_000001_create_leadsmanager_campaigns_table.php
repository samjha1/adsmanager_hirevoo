<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leadsmanager_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('leadsmanager_users')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('objective', 32)->default('leads');
            $table->string('status', 32)->default('draft');
            $table->decimal('daily_budget', 12, 2)->default(0);
            $table->decimal('total_budget', 12, 2)->default(0);
            $table->decimal('spend', 12, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('objective');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leadsmanager_campaigns');
    }
};
