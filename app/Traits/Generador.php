<?php

namespace App\Traits;

use Carbon\Carbon;

//Uso del Faker para respaldar contraseña
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Apunte;
use App\Publicacion;
use App\Traits\Transacciones;
use App\Etiqueta;
use App\User;

trait Generador{
    use Transacciones;
    public function tokenCorreo($user){
        $token = Str::random(100);

        $expira = Carbon::now()->format('d');
        $expira = $expira+1;

        $user->token_verificacion = [
            'token' => $token,
            'expira' => Carbon::now()->format('Y-m-'.$expira.' H:i:s')
        ];
        $user->save();

        return $user;
    }

    public function bienvenida($user, $clips_free){
        $users_totales = User::where('activo', 1)->count();
        $apunte = Apunte::create([
            'titulo' => 'Bienvenido',  
            'slug' => 'bienvenida-'.Str::slug($user->name).'-'.time(),  
            'descripcion' => '[
                {
                    "type":"header",
                    "data":{
                        "text":"Bienvenido '.$user->name.' 🥳",
                        "level":2
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"Antes que nada queremos decirte gracias por usar SchoolNotes, contigo ya somos '.$users_totales.' usuarios, tendremos muchas sorpresas para todos ustedes, solo es cuestion de esperar."
                    }
                },
                {
                    "type":"paragraph",
                    "data":{
                        "text":"SchoolNotes, quiere decirte que cada apunte que subas, te regalaremos 25 clips, con este documento te regalamos '.$clips_free.' clips como incio, recuerda que si completas tu perfil podras llevarte muchos mas clips para poder descargar otros apuntes y hacer preguntas!"
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
            'archivo' => asset('documentos-bienvenida/bienvenida.pdf'),
            'reacciones' => [],
            'activo' => 1, 
            'user_id' => $user->_id,
            'etiquetas_ids' => [Etiqueta::pluck('_id')->first()],
        ]);

        $publicacion = Publicacion::create([
            'contenido' => 'Bienvenido '.$user->name.', muchas gracias por registrarte en SchoolNotes 🥳🥳',  
            'reacciones' => [],
            'activo' => 1, 
            'user_id' => $user->_id,
        ]);
            
        $this->desbloquearApunte($user, $apunte, 0, $clips_free);
        
    }
}