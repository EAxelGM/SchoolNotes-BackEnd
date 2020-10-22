<?php
namespace App\Traits;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait Funciones{
    public function paginacionPersonalizada($page, $data, $data_for_page, $objectOrder){
        /*Orden por defecto (ASCendente)*/
        //usort($data, $this->object_sorter($objectOrder));
        
        /*Orden DESCendente (indicándolo en parámetro)*/
        usort($data, $this->object_sorter($objectOrder,'DESC'));

        //paginacion
        $currentPage = $page;
        $perPage = $data_for_page;
        $currentElements = array_slice($data, $perPage * ($currentPage - 1), $perPage);
        $posts = new Paginator($currentElements, $perPage, $currentPage);
        return $posts;
    }

    function object_sorter($clave,$orden=null) {
        return function ($a, $b) use ($clave,$orden) {
              $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
              return $result;
        };
    }

    public function subirFile($user, $file, $title){
        $archivo = $file->getClientOriginalName();
        $separacion = explode(".", $archivo);
        $extension = end($separacion);

        if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png'){
            $nombre = Str::slug($user->name).'_'.Str::slug($user->_id).'_'.time().'.'.$extension;
        }else{
            $nombre = Str::slug($title).'_'.time().'.'.$extension;
        }

        if($extension == 'pptx' || $extension == 'pptm' || $extension == 'ppt'){
            $path = Storage::disk('power_point')->put($nombre, \File::get($file));
            $data = ['message' => 'Archivo Power Point subido con exito', 'path' => asset('documentos-power-point/'.$nombre), 'code' => 200, 'casi_slug' => $nombre];
        }
        else if($extension == 'docx' || $extension == 'docm' || $extension == 'dotx' || $extension == 'dotm,'){
            $path = Storage::disk('word')->put($nombre, \File::get($file));
            $data = ['message' => 'Archivo Word subido con exito', 'path' => asset('documentos-word/'.$nombre), 'code' => 200, 'casi_slug' => $nombre];
        }
        else if($extension == 'pdf'){
            $path = Storage::disk('pdf')->put($nombre, \File::get($file));
            $data = ['message' => 'Archivo PDF subido con exito', 'path' => asset('documentos-pdf/'.$nombre), 'code' => 200, 'casi_slug' => $nombre];
        }
        else if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png'){
            $path = Storage::disk('imagenes')->put($nombre, \File::get($file));
            $data = ['message' => 'Imagen subida con exito', 'path' => asset('documentos-imagenes/'.$nombre), 'code' => 200, 'casi_slug' => $nombre];
        }
        else{
            $data = ['message' => 'Fallo al subir el Archivo', 'path' => null, 'code' => 421, 'casi_slug' => $nombre];
        }
        
        return $data;
    }
}