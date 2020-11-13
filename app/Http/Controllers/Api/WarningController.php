<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\EnviarCorreos;
use App\Warning;
use App\User;

class WarningController extends Controller
{
    use EnviarCorreos;
    public function index()
    {
        $warnings = Warning::orderBy('created_at','desc')->get();
        return response()->json([
            'message' => 'Success',
            'data' => '$warnings',
        ],200);

    }
    
    public function store(Request $request)
    {
        //Validamos que la person quien mande el warning sea un admin.
        if(Auth::user()->tipo != 'administrador'){
            return response()->json([
                'message' => 'No tienes permitido realizar esta accion'
            ],421);
        }
        
        $user = User::find($request->user_id);
        $warning = Warning::where('user_id', $request->user_id)->count();
        if($warning >= 3){
            if($user){
                $user->activo = 0;
                $user->save();
                $user['warnings'] = $warning;
                $this->cuentaBaneada($user);
            }
        }
        $warn = Warning::create([
            'user_id' => $request->user_id,
            'motivo' => $request->motivo,
        ]);
        $warn['total_warnings'] = $warning + 1;
        $this->enviarWarning($user,$warn);

        return response()->json([
            'message' => 'Warning creado con exito, se ha enviado un correo al usuario.',
            'data' => $warn,
        ],200);

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
