<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('awb_number'); // 176-5893 4567
            $table->string('carrier');    // MAERSK, MSC, CMA CGM
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->enum('status', ['booked', 'in_transit', 'arrived_at_port', 'delivered'])
                ->default('booked');
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
        });

        Schema::create('shipment_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->string('location')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('event_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_tracking_events');
        Schema::dropIfExists('shipments');
    }
};
