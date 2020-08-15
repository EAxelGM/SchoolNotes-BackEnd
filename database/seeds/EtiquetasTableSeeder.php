<?php

use Illuminate\Database\Seeder;

class EtiquetasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Etiqueta::class, 150)->create();
    }
}
