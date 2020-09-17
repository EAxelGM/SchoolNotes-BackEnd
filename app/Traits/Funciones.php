<?php
namespace App\Traits;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

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
}