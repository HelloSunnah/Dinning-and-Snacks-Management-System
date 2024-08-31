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
                'member' => 100
            ],
            [
                'shift' => 'B',
                'member' => 20
            ],
            [
                'shift' => 'C',
                'member' => 10
            ],
            [
                'shift' => 'General',
                'member' => 50
            ],
        ]);
    }

    }
