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
            'email' => 'cardenascelso9@gmail.com',
            'password' => Hash::make('123456789'),
            'correo_verificado' => true,
            'descripcion_perfil' => '',
            'fecha_nacimiento' => '1997-08-02',
            //'img_perfil' => asset('img_perfiles/default.png'),
            'img_perfil' => 'https://schoolnotes.live/020040sn897200/img_perfiles/default3.png',
            'seguidos' => [],
            'seguidores' => [],
            'etiquetas_ids' => [App\Etiqueta::pluck('_id')->first()],
            'clips' => 99999,
            'diamond_clips' => 9999,
            'apuntes_comprados' => [],
            'tipo' => 'adminsitrador',
            'activo' => 1,
        ]);

        //Creacion de usuario de Axel
        App\User::create([
            'name' => 'Edgar Axel',
            'apellidos' => 'Gonzalez Martinez',
            'email' => 'axel-canelo@hotmail.com',
            'password' => Hash::make('123456789'),
            'correo_verificado' => true,
            'descripcion_perfil' => '',
            'fecha_nacimiento' => '2000-02-04',
            //'img_perfil' => asset('img_perfiles/default.png'),
            'img_perfil' => 'https://schoolnotes.live/020040sn897200/img_perfiles/default3.png',
            'seguidos' => [],
            'seguidores' => [],
            'etiquetas_ids' => [App\Etiqueta::pluck('_id')->first()],
            'clips' => 9999,
            'diamond_clips' => 999,
            'apuntes_comprados' => [],
            'tipo' => 'usuario',
            'activo' => 1,
        ]);

        //factory(App\User::class, 150)->create();
    }
}
