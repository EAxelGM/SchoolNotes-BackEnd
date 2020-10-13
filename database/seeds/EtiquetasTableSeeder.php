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
        //Creacion de Etiquetas
        App\Etiqueta::create([
            'nombre' => 'Bienvenida',
            'slug' => 'bienvenida',
            'activo' => 0,
        ]);

        factory(App\Etiqueta::class, 150)->create();
    }
}
