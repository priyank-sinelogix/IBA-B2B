<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            // polymorphic link: linked_type = 'sample' | 'order' | 'shipment' | 'ledger_entry' | null (general)
            $table->string('linked_type')->nullable();
            $table->unsignedBigInteger('linked_id')->nullable();
            $table->text('body');
            $table->boolean('is_read')->default(false);
            $table->string('attachment_path')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'linked_type', 'linked_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
