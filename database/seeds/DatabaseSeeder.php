<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriasTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EtiquetasTableSeeder::class);
        $this->call(PublicacionesTableSeeder::class);
        $this->call(ApuntesTableSeeder::class);
        $this->call(ComentariosTableSeeder::class);
        $this->call(PreguntasTableSeeder::class);
        $this->call(RespuestasTableSeeder::class);
    }
}
