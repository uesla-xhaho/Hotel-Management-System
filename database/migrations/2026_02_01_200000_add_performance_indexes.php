<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! $this->indexExists('bookings', 'bookings_status_index')) {
                $table->index('status', 'bookings_status_index');
            }
            if (! $this->indexExists('bookings', 'bookings_checkin_index')) {
                $table->index('checkin', 'bookings_checkin_index');
            }
            if (! $this->indexExists('bookings', 'bookings_customer_id_index')) {
                $table->index('customer_id', 'bookings_customer_id_index');
            }
            if (! $this->indexExists('bookings', 'bookings_room_id_index')) {
                $table->index('room_id', 'bookings_room_id_index');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (! $this->indexExists('payments', 'payments_status_index')) {
                $table->index('status', 'payments_status_index');
            }
            if (! $this->indexExists('payments', 'payments_booking_id_index')) {
                $table->index('booking_id', 'payments_booking_id_index');
            }
            if (! $this->indexExists('payments', 'payments_customer_id_index')) {
                $table->index('customer_id', 'payments_customer_id_index');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if (! $this->indexExists('rooms', 'rooms_status_index')) {
                $table->index('status', 'rooms_status_index');
            }
            if (! $this->indexExists('rooms', 'rooms_category_index')) {
                $table->index('category', 'rooms_category_index');
            }
            if (! $this->indexExists('rooms', 'rooms_hotel_id_index')) {
                $table->index('hotel_id', 'rooms_hotel_id_index');
            }
        });

        Schema::table('staff', function (Blueprint $table) {
            if (! $this->indexExists('staff', 'staff_hotel_id_index')) {
                $table->index('hotel_id', 'staff_hotel_id_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if ($this->indexExists('bookings', 'bookings_status_index')) {
                $table->dropIndex('bookings_status_index');
            }
            if ($this->indexExists('bookings', 'bookings_checkin_index')) {
                $table->dropIndex('bookings_checkin_index');
            }
            if ($this->indexExists('bookings', 'bookings_customer_id_index')) {
                $table->dropIndex('bookings_customer_id_index');
            }
            if ($this->indexExists('bookings', 'bookings_room_id_index')) {
                $table->dropIndex('bookings_room_id_index');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if ($this->indexExists('payments', 'payments_status_index')) {
                $table->dropIndex('payments_status_index');
            }
            if ($this->indexExists('payments', 'payments_booking_id_index')) {
                $table->dropIndex('payments_booking_id_index');
            }
            if ($this->indexExists('payments', 'payments_customer_id_index')) {
                $table->dropIndex('payments_customer_id_index');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if ($this->indexExists('rooms', 'rooms_status_index')) {
                $table->dropIndex('rooms_status_index');
            }
            if ($this->indexExists('rooms', 'rooms_category_index')) {
                $table->dropIndex('rooms_category_index');
            }
            if ($this->indexExists('rooms', 'rooms_hotel_id_index')) {
                $table->dropIndex('rooms_hotel_id_index');
            }
        });

        Schema::table('staff', function (Blueprint $table) {
            if ($this->indexExists('staff', 'staff_hotel_id_index')) {
                $table->dropIndex('staff_hotel_id_index');
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $results = DB::select(
            'SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
            [$table, $index]
        );

        return ! empty($results);
    }
};
