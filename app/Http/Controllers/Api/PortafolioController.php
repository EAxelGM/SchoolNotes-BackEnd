<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Portafolio;
use Illuminate\Support\Facades\Auth;
use App\Traits\Funciones;


class PortafolioController extends Controller
{
    use Funciones;

    public function index()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $user = Auth::user();
        $portafolios = [];

        foreach($user->etiquetas_ids as $etiqueta){
            $portafolios_for = Portafolio::where('etiquetas_ids', $etiqueta)->get();
            foreach($portafolios_for as $i){
                array_push($portafolios, $i);
            }
        }

        $portafolios = $this->paginacionPersonalizada($page, $portafolios, 12, 'created_at');
        
        return response()->json([
            'message' => 'Success',
            'data' => $portafolios,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
