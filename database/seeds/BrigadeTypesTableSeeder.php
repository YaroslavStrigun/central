<?php

use Illuminate\Database\Seeder;

class BrigadeTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('brigade_types')->delete();
        
        \DB::table('brigade_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Фельдшерська',
                'created_at' => '2019-05-11 09:05:19',
                'updated_at' => '2019-05-11 09:05:19',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Лікарська',
                'created_at' => '2019-05-11 09:05:32',
                'updated_at' => '2019-05-11 09:05:32',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Парамедики',
                'created_at' => '2019-05-11 09:05:45',
                'updated_at' => '2019-05-11 09:05:45',
            ),
        ));
        
        
    }
}