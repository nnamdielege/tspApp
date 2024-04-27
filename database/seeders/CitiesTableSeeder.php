<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('cities')->insert([
            ['name' => 'Melbourne', 'latitude' => -37.8136, 'longitude' => 144.9631, 'state' => 'VIC'],
            ['name' => 'Geelong', 'latitude' => -38.1499, 'longitude' => 144.3617, 'state' => 'VIC'],
            ['name' => 'Ballarat', 'latitude' => -37.5622, 'longitude' => 143.8503, 'state' => 'VIC'],
            ['name' => 'Bendigo', 'latitude' => -36.7582, 'longitude' => 144.2802, 'state' => 'VIC'],
            ['name' => 'Shepparton', 'latitude' => -36.3833, 'longitude' => 145.4000, 'state' => 'VIC'],
            ['name' => 'Traralgon', 'latitude' => -38.1953, 'longitude' => 146.5348, 'state' => 'VIC'],
            ['name' => 'Wodonga', 'latitude' => -36.1163, 'longitude' => 146.8839, 'state' => 'VIC'],
            ['name' => 'Sunbury', 'latitude' => -37.5833, 'longitude' => 144.7333, 'state' => 'VIC'],
            ['name' => 'Pakenham', 'latitude' => -38.0736, 'longitude' => 145.4833, 'state' => 'VIC'],
            ['name' => 'Warrnambool', 'latitude' => -38.3833, 'longitude' => 142.4833, 'state' => 'VIC'],
            ['name' => 'Frankston', 'latitude' => -38.1469, 'longitude' => 145.1360, 'state' => 'VIC'],
            ['name' => 'Dandenong', 'latitude' => -37.9833, 'longitude' => 145.2167, 'state' => 'VIC'],
            ['name' => 'Ballan', 'latitude' => -37.6000, 'longitude' => 144.2167, 'state' => 'VIC'],
            ['name' => 'Colac', 'latitude' => -38.3333, 'longitude' => 143.6000, 'state' => 'VIC'],
            ['name' => 'Hamilton', 'latitude' => -37.7400, 'longitude' => 142.0233, 'state' => 'VIC'],
            ['name' => 'Castlemaine', 'latitude' => -37.0667, 'longitude' => 144.2167, 'state' => 'VIC'],
            ['name' => 'Ararat', 'latitude' => -37.2833, 'longitude' => 142.9333, 'state' => 'VIC'],
            ['name' => 'Benalla', 'latitude' => -36.5500, 'longitude' => 145.9833, 'state' => 'VIC'],
            ['name' => 'Swan Hill', 'latitude' => -35.3389, 'longitude' => 143.5544, 'state' => 'VIC'],
            ['name' => 'Stawell', 'latitude' => -37.0589, 'longitude' => 142.7739, 'state' => 'VIC'],
            ['name' => 'Kyabram', 'latitude' => -36.3167, 'longitude' => 145.0500, 'state' => 'VIC'],
            ['name' => 'Echuca', 'latitude' => -36.1333, 'longitude' => 144.7500, 'state' => 'VIC'],
            ['name' => 'Portland', 'latitude' => -38.3500, 'longitude' => 141.6167, 'state' => 'VIC'],
            ['name' => 'Warragul', 'latitude' => -38.1600, 'longitude' => 145.9333, 'state' => 'VIC'],
            ['name' => 'Yarrawonga', 'latitude' => -36.0333, 'longitude' => 146.0000, 'state' => 'VIC'],
            ['name' => 'Terang', 'latitude' => -38.2414, 'longitude' => 142.9528, 'state' => 'VIC'],
            ['name' => 'Leopold', 'latitude' => -38.1667, 'longitude' => 144.4500, 'state' => 'VIC'],
            ['name' => 'Rochester', 'latitude' => -36.3600, 'longitude' => 144.7000, 'state' => 'VIC'],
            ['name' => 'Kerang', 'latitude' => -35.7333, 'longitude' => 143.9178, 'state' => 'VIC'],
            ['name' => 'Healesville', 'latitude' => -37.6539, 'longitude' => 145.5139, 'state' => 'VIC'],
            ['name' => 'Moe', 'latitude' => -38.1747, 'longitude' => 146.2625, 'state' => 'VIC'],
            ['name' => 'Horsham', 'latitude' => -36.7167, 'longitude' => 142.1994, 'state' => 'VIC'],
            ['name' => 'Donald', 'latitude' => -36.3667, 'longitude' => 142.9667, 'state' => 'VIC'],
            ['name' => 'Edenhope', 'latitude' => -37.0333, 'longitude' => 141.2833, 'state' => 'VIC'],
            ['name' => 'Euroa', 'latitude' => -36.7500, 'longitude' => 145.5667, 'state' => 'VIC'],
            ['name' => 'Inverloch', 'latitude' => -38.6333, 'longitude' => 145.7333, 'state' => 'VIC'],
            ['name' => 'Bright', 'latitude' => -36.7333, 'longitude' => 146.9667, 'state' => 'VIC'],
            ['name' => 'Alexandra', 'latitude' => -37.1900, 'longitude' => 145.7089, 'state' => 'VIC'],
            ['name' => 'Nagambie', 'latitude' => -36.7875, 'longitude' => 145.1514, 'state' => 'VIC'],
            ['name' => 'Tatura', 'latitude' => -36.4381, 'longitude' => 145.2319, 'state' => 'VIC'],
            ['name' => 'Heathcote', 'latitude' => -36.9167, 'longitude' => 144.7000, 'state' => 'VIC'],
            ['name' => 'Yea', 'latitude' => -37.2167, 'longitude' => 145.4333, 'state' => 'VIC'],
            ['name' => 'Lorne', 'latitude' => -38.5411, 'longitude' => 143.9717, 'state' => 'VIC'],
            ['name' => 'Beaufort', 'latitude' => -37.4297, 'longitude' => 143.3825, 'state' => 'VIC'],
            ['name' => 'Corryong', 'latitude' => -36.1833, 'longitude' => 147.9000, 'state' => 'VIC'],
            ['name' => 'Heyfield', 'latitude' => -37.9806, 'longitude' => 146.7886, 'state' => 'VIC'],
            ['name' => 'Wonthaggi', 'latitude' => -38.6086, 'longitude' => 145.5922, 'state' => 'VIC'],
            ['name' => 'Yarram', 'latitude' => -38.5628, 'longitude' => 146.6778, 'state' => 'VIC'],
            ['name' => 'Nhill', 'latitude' => -36.3333, 'longitude' => 141.6500, 'state' => 'VIC'],
            ['name' => 'Kyneton', 'latitude' => -37.2500, 'longitude' => 144.4500, 'state' => 'VIC'],
            ['name' => 'Clunes', 'latitude' => -37.2861, 'longitude' => 143.7872, 'state' => 'VIC'],
            ['name' => 'Numurkah', 'latitude' => -36.0933, 'longitude' => 145.4417, 'state' => 'VIC'],
            ['name' => 'Heathcote', 'latitude' => -36.9167, 'longitude' => 144.7000, 'state' => 'VIC'],
            ['name' => 'Yea', 'latitude' => -37.2167, 'longitude' => 145.4333, 'state' => 'VIC'],
            ['name' => 'Lorne', 'latitude' => -38.5411, 'longitude' => 143.9717, 'state' => 'VIC'],
            ['name' => 'Beaufort', 'latitude' => -37.4297, 'longitude' => 143.3825, 'state' => 'VIC'],
            ['name' => 'Corryong', 'latitude' => -36.1833, 'longitude' => 147.9000, 'state' => 'VIC'],
            ['name' => 'Heyfield', 'latitude' => -37.9806, 'longitude' => 146.7886, 'state' => 'VIC'],
            ['name' => 'Wonthaggi', 'latitude' => -38.6086, 'longitude' => 145.5922, 'state' => 'VIC'],
            ['name' => 'Yarram', 'latitude' => -38.5628, 'longitude' => 146.6778, 'state' => 'VIC'],
        ]);
    }
}