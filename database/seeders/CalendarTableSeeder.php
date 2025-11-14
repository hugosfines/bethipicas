<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Calendar;
use App\Models\RaceCalendar;
use App\Models\Racing;
use App\Models\RacingBet;

class CalendarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$tracks= Track::whereIn('id', [4,8,9,18,22,23,25,26,27,30,32,38,52,55,59,60,61,68,76,82,83,89,90,91,103,105,116,119])->get();

        for ($i=1; $i <= 6 ; $i++) { 
            $calendar = Calendar::create([
                'track_id' => $i,
                'date_at' => now()->format('Y-m-d'),
                'total_races' => rand(5, 10),
                'is_active' => true,
            ]);

            RaceCalendar::create([
                'calendar_id' => $calendar->id,
                'race_current' => 1,
                'user_id' => 1
            ]);

            for ($b=1; $b <= $calendar->total_races ; $b++) { 
                $race = Racing::create([
                    'calendar_id' => $calendar->id,
                    'race' => $b,
                    'total_horses' => rand(5, 12),
                    'start_time' => $calendar->date_at->format('Y-m-d') . ' ' . now()->addMinutes($b * 10)->format('H:i'),
                    'distance' => rand(1000, 2000),
                    'status' => 'open',
                ]);

                for ($j=1; $j <= 11 ; $j++) { 
                    RacingBet::create([
                        'racing_id' => $race->id,
                        'bet_type_id' => $j, // Assuming bet types are from 1 to 11
                    ]);
                }
            }
        }
    }
}
