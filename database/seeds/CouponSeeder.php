<?php

use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=array(
            array(
                'code'=>'HERALD',
                'type'=>'fixed',
                'value'=>'300',
                'status'=>'active'
            ),
            array(
                'code'=>'FYP',
                'type'=>'percent',
                'value'=>'100',
                'status'=>'active'
            ),
        );

        DB::table('coupons')->insert($data);
    }
}
