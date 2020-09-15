<?php
namespace App\Traits;

use Illuminate\Pagination\Paginator;

trait Funciones{
    public function paginacionPersonalizada($page, $data, $data_for_page, $path){
        /*Orden por defecto (ASCendente)*/
        //usort($data, $this->object_sorter('created_at'));

        /*Orden DESCendente (indicándolo en parámetro)*/
        usort($data, $this->object_sorter('created_at','DESC'));
        
        $posts = new Paginator($data, $data_for_page, $page);
    
        $posts->setPath($path);
        return $posts;
    }

    function object_sorter($clave,$orden=null) {
        return function ($a, $b) use ($clave,$orden) {
              $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
              return $result;
        };
    }
}