<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Creacion de cuenta SchoolNotes 2020
        App\User::create([
            'name' => 'SchoolNotes',
            'email' => 'schoolnotes.info@gmail.com',
            'password' => Hash::make('SchoolNotes2020'),
            'correo_verificado' => true,
            'descripcion_perfil' => '[
                {
                    "type":"header",
                    "data":{
                        "text":"Bienvenido 🥳",
                        "level":2
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"Antes que nada queremos decirte gracias por usar SchoolNotes, tendremos muchas sorpresas para todos ustedes, solo es cuestion de esperar."
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"SchoolNotes, quiere decirte que cada apunte que subas, te regalaremos 25 clips, recuerda que si completas tu perfil podras llevarte muchos mas clips para poder descargar otros apuntes y hacer preguntas!"
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"Por cierto recuerda que hay concecuencias por incumplir algunas reglas... y por cada falta, obtendras un warning (alerta), y si tu cuenta llega a tener 3 warnings tu cuenta podria llegar a ser baneada, aqui una lista de cuales serian los posibles warnings: "
                    }
                },
                {
                    "type":"list",
                    "data":{
                        "style":"ordered",
                        "items":[
                            "Subir documentos vacios: No estaremos revisando apunte por apunte si hay un documento vacio, pero si llega a cierto reportes un apunte, revisaremos lo que contiene y si encontramos algo que no esta bienvenido en las reglas obtendras un warning.",
                            "Subir contenido sexual: Es una plataforma para que otras personas puedan compartir sus conocimientos y generar dinero a base de ello, si encontramos apuntes, publicaciones, preguntas o perfiles con ese contenido, tendra un warning.",
                            "Subir documentos falsos: Si subes un documento con algo que no tenga que ver, la cuenta recibirá un warning",
                            "Ofenzas: Si una persona ofende o agrede a otros usuarios, recibirá otro warning, no toleramos este tipo de acciones."
                        ]
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":""
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"Sin Mas que decir gracias por usar SchoolNotes 📚, recuerda, ¡Nunca dejes de aprender!"
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"Te dejamos un Documento PDF creado por un usuario de SchoolNotes, Karen Zepeda, Gracias! 🥳"
                    }
                }
            
            ]', 
            'fecha_nacimiento' => '2020-12-31',
            'img_perfil' => 'https://schoolnotes.live/020040sn897200/assets/Icono_SchoolNotes.png',
            'seguidos' => [],
            'seguidores' => [],
            'etiquetas_ids' => [App\Etiqueta::pluck('_id')->first()],
            'clips' => 999999999,
            'diamond_clips' => 999999999,
            'apuntes_comprados' => [],
            'tipo' => 'adminsitrador',
            'activo' => 1,
        ]);

        //Creacion de usuario de Axel
        App\User::create([
            'name' => 'Celso Cardenas Macias',
            'email' => 'cardenascelso9@gmail.com',
            'password' => Hash::make('123456789'),
            'correo_verificado' => true,
            'descripcion_perfil' => '',
            'fecha_nacimiento' => '1997-08-02',
            'img_perfil' => 'https://launcher.galaxylifereborn.com/uploads/avatars/70432.png',
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
            'name' => 'Edgar Axel Gonzalez Martinez',
            'email' => 'axel-canelo@hotmail.com',
            'password' => Hash::make('123456789'),
            'correo_verificado' => true,
            'descripcion_perfil' => '[
                {
                    "type":"header",
                    "data":{
                        "text":"Hola Bienvenido a mi perfil 🥳",
                        "level":2
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"Yo no soy muy fan de realizar documentos, mas bien me encanta ayudar a la gente, soy uno de los desarrolladores de SchoolNotes y estoy muy feliz de crear este proyecto"
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"me gusta mucho desarrolladr plataformas para todos ustedes, estoy muy contento de casi anunciar SchoolNotes de manera oficial, estamos terminando apenas la Alpha, para posteriormente, llegar a la beta y empezar a realizar mejores cosas"
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"Espero les guste la plataforma, de antemano mucha gracias!"
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":""
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"¡Nunca dejes de aprender!"
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"PD. Feliz Halloween :D"
                    }
                }
            
            ]', 
            'fecha_nacimiento' => '2000-02-04',
            'img_perfil' => 'https://launcher.galaxylifereborn.com/uploads/avatars/198.png',
            'seguidos' => [],
            'seguidores' => [],
            'etiquetas_ids' => [App\Etiqueta::pluck('_id')->first()],
            'clips' => 9999,
            'diamond_clips' => 99999,
            'apuntes_comprados' => [],
            'tipo' => 'adminsitrador',
            'activo' => 1,
        ]);

        //factory(App\User::class, 150)->create();
    }
}
