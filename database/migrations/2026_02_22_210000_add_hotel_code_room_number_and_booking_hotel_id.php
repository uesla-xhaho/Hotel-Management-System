<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addHotelCodeColumn();
        $this->backfillHotelCodes();
        $this->addHotelCodeUniqueIndex();

        $this->addRoomLayoutColumns();
        $this->backfillRoomLayout();
        $this->addRoomLayoutIndexes();

        $this->addBookingHotelColumn();
        $this->backfillBookingHotelId();
        $this->addBookingHotelIndex();
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if ($this->indexExists('bookings', 'bookings_hotel_id_index')) {
                $table->dropIndex('bookings_hotel_id_index');
            }
            if (Schema::hasColumn('bookings', 'hotel_id')) {
                $table->dropColumn('hotel_id');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if ($this->indexExists('rooms', 'rooms_room_number_unique')) {
                $table->dropUnique('rooms_room_number_unique');
            }
            if ($this->indexExists('rooms', 'rooms_hotel_floor_position_unique')) {
                $table->dropUnique('rooms_hotel_floor_position_unique');
            }
            if (Schema::hasColumn('rooms', 'room_number')) {
                $table->dropColumn('room_number');
            }
            if (Schema::hasColumn('rooms', 'room_position')) {
                $table->dropColumn('room_position');
            }
            if (Schema::hasColumn('rooms', 'room_floor')) {
                $table->dropColumn('room_floor');
            }
        });

        Schema::table('hotels', function (Blueprint $table) {
            if ($this->indexExists('hotels', 'hotels_code_unique')) {
                $table->dropUnique('hotels_code_unique');
            }
            if (Schema::hasColumn('hotels', 'code')) {
                $table->dropColumn('code');
            }
        });
    }

    private function addHotelCodeColumn(): void
    {
        if (Schema::hasColumn('hotels', 'code')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            $table->string('code', 3)->nullable()->after('name');
        });
    }

    private function backfillHotelCodes(): void
    {
        $hotels = DB::table('hotels')
            ->select('id', 'name', 'code')
            ->orderBy('id')
            ->get();

        $usedCodes = [];
        foreach ($hotels as $hotel) {
            if (!empty($hotel->code)) {
                $usedCodes[] = strtoupper($hotel->code);
            }
        }

        foreach ($hotels as $hotel) {
            if (!empty($hotel->code)) {
                $normalizedCode = strtoupper($hotel->code);
                if ($hotel->code !== $normalizedCode) {
                    DB::table('hotels')->where('id', $hotel->id)->update(['code' => $normalizedCode]);
                }
                continue;
            }

            $name = strtoupper((string) $hotel->name);
            $letters = preg_replace('/[^A-Z]/', '', $name) ?: 'H';
            $code = '';

            for ($i = 0; $i < strlen($letters); $i++) {
                $candidate = $letters[$i];
                if (!in_array($candidate, $usedCodes, true)) {
                    $code = $candidate;
                    break;
                }
            }

            if ($code === '') {
                for ($i = 0; $i < 26; $i++) {
                    $candidate = chr(ord('A') + $i);
                    if (!in_array($candidate, $usedCodes, true)) {
                        $code = $candidate;
                        break;
                    }
                }
            }

            if ($code === '') {
                // Fallback for very uncommon cases with many hotels.
                $suffix = strtoupper(base_convert((string) $hotel->id, 10, 36));
                $code = 'H' . substr($suffix, -2);
            }

            DB::table('hotels')->where('id', $hotel->id)->update(['code' => $code]);
            $usedCodes[] = strtoupper($code);
        }
    }

    private function addHotelCodeUniqueIndex(): void
    {
        if ($this->indexExists('hotels', 'hotels_code_unique')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            $table->unique('code', 'hotels_code_unique');
        });
    }

    private function addRoomLayoutColumns(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'room_floor')) {
                $table->unsignedTinyInteger('room_floor')->nullable()->after('hotel_id');
            }
            if (!Schema::hasColumn('rooms', 'room_position')) {
                $table->unsignedTinyInteger('room_position')->nullable()->after('room_floor');
            }
            if (!Schema::hasColumn('rooms', 'room_number')) {
                $table->string('room_number', 10)->nullable();
            }
        });
    }

    private function backfillRoomLayout(): void
    {
        $hotels = DB::table('hotels')->select('id', 'code')->orderBy('id')->get();

        foreach ($hotels as $hotel) {
            $rooms = DB::table('rooms')
                ->select('id')
                ->where('hotel_id', $hotel->id)
                ->orderBy('id')
                ->get()
                ->values();

            foreach ($rooms as $index => $room) {
                $floor = intdiv($index, 4) + 1;
                $position = ($index % 4) + 1;
                $roomNumber = strtoupper((string) $hotel->code) . $floor . str_pad((string) $position, 2, '0', STR_PAD_LEFT);

                DB::table('rooms')
                    ->where('id', $room->id)
                    ->update([
                        'room_floor' => $floor,
                        'room_position' => $position,
                        'room_number' => $roomNumber,
                    ]);
            }
        }
    }

    private function addRoomLayoutIndexes(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (!$this->indexExists('rooms', 'rooms_hotel_floor_position_unique')) {
                $table->unique(['hotel_id', 'room_floor', 'room_position'], 'rooms_hotel_floor_position_unique');
            }
            if (!$this->indexExists('rooms', 'rooms_room_number_unique')) {
                $table->unique('room_number', 'rooms_room_number_unique');
            }
        });
    }

    private function addBookingHotelColumn(): void
    {
        if (Schema::hasColumn('bookings', 'hotel_id')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('hotel_id')->nullable()->after('room_id');
        });
    }

    private function backfillBookingHotelId(): void
    {
        DB::statement('
            UPDATE bookings
            INNER JOIN rooms ON rooms.id = bookings.room_id
            SET bookings.hotel_id = rooms.hotel_id
            WHERE bookings.hotel_id IS NULL
        ');
    }

    private function addBookingHotelIndex(): void
    {
        if ($this->indexExists('bookings', 'bookings_hotel_id_index')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('hotel_id', 'bookings_hotel_id_index');
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $results = DB::select(
            'SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
            [$table, $index]
        );

        return !empty($results);
    }
};
