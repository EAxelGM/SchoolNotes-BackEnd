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
            'nombre' => 'Bienvenido',
            'slug' => 'bienvenido',
            'created_by' => App\User::first()->_id,
            'activo' => 0,
        ]);
    
        App\Etiqueta::create([
            "nombre" => "Matemáticas",
            "slug" => "matematicas",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Programación",
            "slug" => "programacion",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Ciencias De La Salud",
            "slug" => "ciencias-de-la-salud",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Anatomía",
            "slug" => "anatomia",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Cirugía Oral",
            "slug" => "cirugia-oral",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Bioquimica",
            "slug" => "bioquimica",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Estructuras",
            "slug" => "estructuras",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Diseño",
            "slug" => "diseno",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Nutrición",
            "slug" => "nutricion",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Materiales",
            "slug" => "materiales",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Psicología",
            "slug" => "psicologia",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Inglés",
            "slug" => "ingles",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Entorno Socio-económico",
            "slug" => "entorno-socio-economico",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Análisis De Señales",
            "slug" => "analisis-de-senales",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Formación Sociocultural",
            "slug" => "formacion-sociocultural",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Arquitectura De Software",
            "slug" => "arquitectura-de-software",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Diseño Arquitectónico",
            "slug" => "diseno-arquitectonico",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Odontologia",
            "slug" => "odontologia",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Patología Oral",
            "slug" => "patologia-oral",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Marketing",
            "slug" => "marketing",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Informatica",
            "slug" => "informatica",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Ciberseguridad",
            "slug" => "ciberseguridad",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Startups",
            "slug" => "startups",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Python",
            "slug" => "python",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Programación Básica",
            "slug" => "programacion-basica",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
            "nombre" => "Proyectos De Ingeniería Civil",
            "slug" => "proyectos-de-ingenieria-civil",
            "created_by" => App\User::first()->_id,
            "activo" => 1,
        ]);
        App\Etiqueta::create([
                "nombre" => "Entrevistas",
                "slug" => "entrevistas",
                "created_by" => App\User::first()->_id,
                "activo" => 1,
        ]);

        $users = App\User::all();
        $etiqueta = App\Etiqueta::first();
        foreach($users as $user){
            $compras = $user->etiquetas_ids;
            array_push($compras,$etiqueta->_id);
            $user->etiquetas_ids = $compras;
            $user->save();
        }

        //factory(App\Etiqueta::class, 150)->create();
    }
}
