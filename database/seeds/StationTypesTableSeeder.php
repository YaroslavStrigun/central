<?php

use Illuminate\Database\Seeder;

class StationTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('station_types')->delete();
        
        \DB::table('station_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'підстанція',
                'created_at' => '2019-05-11 09:34:58',
                'updated_at' => '2019-05-11 09:34:58',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'постійне місце базування',
                'created_at' => '2019-05-11 09:35:14',
                'updated_at' => '2019-05-11 09:35:14',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'тимчасове місце базування',
                'created_at' => '2019-05-11 09:35:36',
                'updated_at' => '2019-05-11 09:35:36',
            ),
        ));
        
        
    }
}