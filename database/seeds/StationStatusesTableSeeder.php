<?php

use Illuminate\Database\Seeder;

class StationStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('station_statuses')->delete();
        
        \DB::table('station_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Відкрита',
                'created_at' => '2019-05-11 09:07:35',
                'updated_at' => '2019-05-11 09:07:35',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Закрита',
                'created_at' => '2019-05-11 09:07:48',
                'updated_at' => '2019-05-11 09:07:48',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Тимчасово закрита',
                'created_at' => '2019-05-11 09:08:03',
                'updated_at' => '2019-05-11 09:08:03',
            ),
        ));
        
        
    }
}