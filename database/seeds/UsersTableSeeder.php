<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Creacion de usuario de Axel
        App\User::create([
            'name' => 'Axel',
            'apellidos' => 'Gonzalez',
            'email' => 'axel@buxod.com',
            'password' => Hash::make('123456789'),
            'correo_verificado' => true,
            'descripcion_perfil' => 'Soy desarrollador Back End',
            'fecha_nacimiento' => '2000-02-04',
            //'img_perfil' => asset('img_perfiles/default.png'),
            'img_perfil' => 'https://cdn.galaxylifereborn.com/content/img/photo_friend.png',
            'seguidos' => [],
            'seguidores' => [],
            'etiquetas_ids' => [],
            'clips' => 99999,
            'diamond_clips' => 9999,
            'tipo' => 'usuario',
            'activo' => 1,
        ]);

        factory(App\User::class, 150)->create();
    }
}
