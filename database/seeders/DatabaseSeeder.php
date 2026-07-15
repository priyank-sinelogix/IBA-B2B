<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\LedgerEntry;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderStageLog;
use App\Models\Sample;
use App\Models\SampleComment;
use App\Models\SampleVersion;
use App\Models\Shipment;
use App\Models\ShipmentTrackingEvent;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Staff / Admin users ---
        $admin = User::firstOrCreate(
            ['email' => 'admin@ibacrafts.com'],
            ['name' => 'Sofia Patel', 'password' => bcrypt('password123'), 'role' => 'super_admin', 'designation' => 'Merchandising Head']
        );

        // --- Client company #1 ---
        $oceanic = Company::firstOrCreate(
            ['code' => 'OCEANIC-APPAREL'],
            ['name' => 'Oceanic Apparel Ltd.', 'credit_limit' => 100000, 'current_balance' => 48750.60]
        );

        $alex = User::firstOrCreate(
            ['email' => 'alex@oceanicapparel.com'],
            ['company_id' => $oceanic->id, 'name' => 'Alex Kumar', 'password' => bcrypt('password123'), 'role' => 'customer', 'designation' => 'Procurement Manager']
        );

        // --- Client company #2 ---
        $northwind = Company::firstOrCreate(
            ['code' => 'NORTHWIND-TEX'],
            ['name' => 'Northwind Textiles Inc.', 'credit_limit' => 60000, 'current_balance' => 12500]
        );

        User::firstOrCreate(
            ['email' => 'priya@northwindtex.com'],
            ['company_id' => $northwind->id, 'name' => 'Priya Sharma', 'password' => bcrypt('password123'), 'role' => 'customer', 'designation' => 'Sourcing Head']
        );

        // --- Samples for Oceanic ---
        $samplesData = [
            ['code' => 'SMP-0247', 'style' => 'Piqué Polo', 'fabric' => 'Piqué 220 GSM', 'color' => 'Sand', 'status' => 'pending'],
            ['code' => 'SMP-0246', 'style' => 'Zip Hoodie', 'fabric' => 'Fleece 320 GSM', 'color' => 'Denim Blue', 'status' => 'pending'],
            ['code' => 'SMP-0245', 'style' => 'Crew Neck Tee', 'fabric' => 'Single Jersey 180 GSM', 'color' => 'Sage Green', 'status' => 'changes_requested'],
        ];

        foreach ($samplesData as $s) {
            $sample = Sample::firstOrCreate(
                ['sample_code' => $s['code']],
                [
                    'company_id' => $oceanic->id, 'style_name' => $s['style'], 'fabric' => $s['fabric'],
                    'color' => $s['color'], 'status' => $s['status'], 'submitted_by' => $admin->id,
                    'submitted_at' => now()->subDays(rand(1, 10)),
                ]
            );

            if ($sample->versions()->count() === 0) {
                SampleVersion::create([
                    'sample_id' => $sample->id, 'version_no' => 1,
                    'image_path' => 'samples/placeholder.jpg', // replace via admin panel upload
                    'notes' => 'Initial submission', 'uploaded_by' => $admin->id,
                ]);

                if ($s['status'] === 'changes_requested') {
                    SampleComment::create([
                        'sample_id' => $sample->id, 'user_id' => $alex->id,
                        'comment' => 'Please adjust the shade slightly darker.', 'action' => 'revise',
                    ]);
                }
            }
        }

        // --- Orders for Oceanic ---
        $ordersData = [
            ['no' => 'ORD-240512', 'style' => 'Piqué Polo', 'stage' => 'cutting', 'qty' => 12000, 'eta' => '2024-05-28'],
            ['no' => 'ORD-240498', 'style' => 'Zip Hoodie', 'stage' => 'sewing', 'qty' => 8000, 'eta' => '2024-06-01'],
            ['no' => 'ORD-240479', 'style' => 'Crew Neck Tee', 'stage' => 'packing', 'qty' => 15000, 'eta' => '2024-05-30'],
            ['no' => 'ORD-240462', 'style' => 'Cargo Pant', 'stage' => 'qc_inspection', 'qty' => 6000, 'eta' => '2024-05-27'],
        ];

        foreach ($ordersData as $o) {
            $order = Order::firstOrCreate(
                ['order_no' => $o['no']],
                ['company_id' => $oceanic->id, 'style_name' => $o['style'], 'quantity' => $o['qty'], 'current_stage' => $o['stage'], 'eta' => $o['eta']]
            );

            if ($order->stageLogs()->count() === 0) {
                OrderStageLog::create(['order_id' => $order->id, 'stage' => $o['stage'], 'changed_by' => $admin->id, 'changed_at' => now()->subDays(2)]);
            }
        }

        // --- Shipments ---
        $shipmentsData = [
            ['awb' => '176-5893 4567', 'carrier' => 'MAERSK', 'origin' => 'Chattogram', 'destination' => 'Los Angeles', 'status' => 'in_transit'],
            ['awb' => '112-4556 7890', 'carrier' => 'MSC', 'origin' => 'Chattogram', 'destination' => 'Hamburg', 'status' => 'in_transit'],
            ['awb' => '235-7788 1122', 'carrier' => 'CMA CGM', 'origin' => 'Chattogram', 'destination' => 'New York', 'status' => 'arrived_at_port'],
        ];

        foreach ($shipmentsData as $s) {
            $shipment = Shipment::firstOrCreate(
                ['awb_number' => $s['awb']],
                ['company_id' => $oceanic->id, 'carrier' => $s['carrier'], 'origin' => $s['origin'], 'destination' => $s['destination'], 'status' => $s['status'], 'status_updated_at' => now()]
            );

            if ($shipment->trackingEvents()->count() === 0) {
                ShipmentTrackingEvent::create([
                    'shipment_id' => $shipment->id, 'status' => $s['status'], 'location' => $s['origin'],
                    'remarks' => 'Departed origin port', 'event_at' => now()->subDays(3),
                ]);
            }
        }

        // --- Ledger ---
        if (LedgerEntry::where('company_id', $oceanic->id)->count() === 0) {
            LedgerEntry::create([
                'company_id' => $oceanic->id, 'type' => 'invoice', 'reference_no' => 'INV-2024-118',
                'amount' => 48750.60, 'balance_after' => 48750.60, 'description' => 'Order ORD-240512 invoice',
            ]);
        }

        // --- Messages ---
        if (Message::where('company_id', $oceanic->id)->count() === 0) {
            Message::create(['company_id' => $oceanic->id, 'sender_id' => $admin->id, 'body' => 'Please find attached the revised tech pack for style ORD-240498.']);
            Message::create(['company_id' => $oceanic->id, 'sender_id' => $alex->id, 'body' => 'Thanks. Please confirm the new delivery date for shipment.']);
        }
    }
}
