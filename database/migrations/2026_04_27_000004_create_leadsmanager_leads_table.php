<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leadsmanager_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('leadsmanager_users')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('source', 32)->default('website');
            $table->string('status', 32)->default('new');
            $table->decimal('value', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('next_followup_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leadsmanager_leads');
    }
};
