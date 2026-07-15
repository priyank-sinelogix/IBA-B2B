<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // approval_pending, sample_revised, message_received, shipment_dispatched
            $table->string('channel')->default('email');
            $table->string('subject')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->timestamps();
        });

        // Immutable-style audit trail: every approval / revision / status change
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // e.g. sample.approved, order.stage_changed
            $table->string('subject_type'); // Sample, Order, Shipment, LedgerEntry
            $table->unsignedBigInteger('subject_id');
            $table->json('changes')->nullable(); // {"before": {...}, "after": {...}}
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notification_logs');
    }
};
