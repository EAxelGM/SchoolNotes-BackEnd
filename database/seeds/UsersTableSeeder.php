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
            'name' => 'Celso',
            'apellidos' => 'Cardenas Macias',
            'email' => 'celso@buxod.com',
            'password' => Hash::make('123456789'),
            'correo_verificado' => true,
            'descripcion_perfil' => 'Soy desarrollador Front End y lider de proyecto en SchoolNotes',
            'fecha_nacimiento' => '1997-08-02',
            //'img_perfil' => asset('img_perfiles/default.png'),
            'img_perfil' => 'https://launcher.galaxylifereborn.com/uploads/avatars/70432.png',
            'seguidos' => [],
            'seguidores' => [],
            'etiquetas_ids' => [],
            'clips' => 99999,
            'diamond_clips' => 9999,
            'tipo' => 'adminsitrador',
            'activo' => 1,
        ]);

        //Creacion de usuario de Axel
        App\User::create([
            'name' => 'Edgar Axel',
            'apellidos' => 'Gonzalez Martinez',
            'email' => 'axel@buxod.com',
            'password' => Hash::make('123456789'),
            'correo_verificado' => true,
            'descripcion_perfil' => 'Soy desarrollador Back End, Desarrollador de SchoolNotes, me gusta jugar videojuegos, y uno de mis juegos favoritos es Galaxy Life Reborn, espero les guste la plataforma',
            'fecha_nacimiento' => '2000-02-04',
            //'img_perfil' => asset('img_perfiles/default.png'),
            'img_perfil' => 'https://launcher.galaxylifereborn.com/uploads/avatars/198.png',
            'seguidos' => [],
            'seguidores' => [],
            'etiquetas_ids' => [],
            'clips' => 9999,
            'diamond_clips' => 999,
            'tipo' => 'usuario',
            'activo' => 1,
        ]);

        factory(App\User::class, 150)->create();
    }
}
