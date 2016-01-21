<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SettingsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'index'       => 'security',
            'subindex'    => 'password',
            'parameter'    => 'salt_left',
            'value'       => 'd5Gjh',
            'description' => 'Salt esquerdo da senha',
            'type'        => 'string'
        ]);

        Setting::create([
            'index'       => 'security',
            'subindex'    => 'password',
            'parameter'    => 'salt_right',
            'value'       => 's2Gv0',
            'description' => 'Salt direito da senha',
            'type'        => 'string'
        ]);
    }

}
