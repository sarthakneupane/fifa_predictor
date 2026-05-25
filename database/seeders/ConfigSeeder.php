<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $config = Config::updateOrCreate([
            'id' => 1,
        ], [
            'email' => 'contact@gfivetech.com',
            'address' => 'Kupondole, Lalitpur, Nepal',
            'phone' => '9849762280',
        ]);
    }
}
