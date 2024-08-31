<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manpower;
use Illuminate\Support\Facades\DB;


class ManpowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('manpowers')->insert([
            [
                'shift' => 'A',
                'member' => 50
            ],
            [
                'shift' => 'B',
                'member' => 30
            ],
            [
                'shift' => 'C',
                'member' => 40
            ],
            [
                'shift' => 'General',
                'member' => 20
            ],
        ]);
    }

    }
