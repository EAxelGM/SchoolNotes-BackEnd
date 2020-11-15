<?php

use Illuminate\Database\Seeder;

class ApuntesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Apunte::create([
            'titulo' => 'Bienvenido',  
            'slug' => 'bienvenida-'.time(),  
            'descripcion' => '[
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
            'archivo' => 'https://schoolnotes.live/020040sn897200/documentos-bienvenida/bienvenida.pdf',
            'reacciones' => [],
            'activo' => 1, 
            'user_id' => App\User::first()->_id,
            'etiquetas_ids' => [App\Etiqueta::pluck('_id')->first()],
        ]);

        $users = App\User::all();
        $apunte = App\Apunte::first();
        foreach($users as $user){
            $compras = $user->apuntes_comprados;
            array_push($compras,$apunte->_id);
            $user->apuntes_comprados = $compras;
            $user->save();
        }

        //factory(App\Apunte::class, 200)->create();
    }
}
