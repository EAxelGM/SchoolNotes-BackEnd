@extends('layouts.mail')

@section('pestana1')
    Sitio Oficial
@endsection

@section('pestana2')
    <a href="#" class="view" target="_blank" style="font-family: 'arial', 'helvetica neue', 'helvetica', 'sans-serif';">
        SchoolNotes
    </a>
@endsection

@section('logo')
    <a href="#" target="_blank">
        <img src="{{$imagenes['large']}}" alt style="display: block;" width="150">
    </a>
@endsection

@section('titulo')
    BIENVENIDO <br /> {{$data->name}} {{$data->apellidos}}
@endsection

@section('descripcion')
    Gracias por registrarte, solo falta activar tu cuenta de correo electronico <br /> {{$data->email}}
@endsection

@section('boton')
    <!--a href="{{ url('/validar/'.$data->id.'/'.$data->email) }}" class="es-button" target="_blank" style="background: #00cba0 none repeat scroll 0% 0%; border-color: #00cba0;">
        Verificar Correo
    </a-->
@endsection

@section('asunto')
    ¡TU CUENTA NO ESTA ACTIVA!
@endsection

@section('cuerpo1')
    ¿Que pasaria si no activo mi cuenta?
@endsection

@section('cuerpo2')
    No tendrias acceso a tu cuenta <strong>{{$data->email}}</strong>, tampoco podras ver los apuntes de otras personas. <br />
    Porfavor activa tu cuenta y muchas gracias por registrarte en SchoolNotes. <br><br>
    La validacion de este email expira en un dia. Si ya ha pasado mas de un dia y no verificaste tu correo, entra a SchoolNotes y reenvia el correo.
@endsection

@section('despedida')
    Te damos las gracias el equipo de SchoolNotes
@endsection

@section('iconoEmpresa')
    <a target="_blank">
        <img src="{{$imagenes['icono']}}" alt style="display: block;" width="40">
    </a>
@endsection

@section('nombreEmpresa')
    SchooNotes
@endsection

@section('descripcionEmpresa')
    schoolnotes.info@gmail.com
@endsection

@section('titulo2')
    ACTIVA TU CORREO ELETRONICO <br /> {{$data->email}}
@endsection

@section('descripcion2')
    Para activarla es muy sencillo, solo tienes que dar clic a este boton. 
@endsection

@section('boton2')
    <a href="{{ url('/validar/'.$data->id.'/'.$data->token_verificacion['token']) }}" class="es-button" target="_blank" style="background: #ffffff none repeat scroll 0% 0%; border-color: #ffffff; color: #00cba0; border-width: 15px 25px;">
        ACTIVAR CUENTA
    </a>
@endsection

@section('facebook')
    <!--a target="_blank">
        <img title="Facebook" src="https://tlr.stripocdn.email/content/assets/img/social-icons/circle-white/facebook-circle-white.png" alt="Fb" width="24" height="24">
    </a-->
@endsection

@section('twitter')
    <!--a target="_blank" href>
        <img title="Twitter" src="https://tlr.stripocdn.email/content/assets/img/social-icons/circle-white/twitter-circle-white.png" alt="Tw" width="24" height="24">
    </a-->
@endsection

@section('instagram')
    <!--a target="_blank" href>
        <img title="Instagram" src="https://tlr.stripocdn.email/content/assets/img/social-icons/circle-white/instagram-circle-white.png" alt="Inst" width="24" height="24">
    </a-->
@endsection

@section('linkedin')
    <!--a target="_blank" href>
        <img title="Linkedin" src="https://tlr.stripocdn.email/content/assets/img/social-icons/circle-white/linkedin-circle-white.png" alt="In" width="24" height="24">
    </a-->
@endsection

@section('footer1')
    schoolnotes.info@gmail.com
@endsection

@section('footer2')
    Este mensaje fue enviado desde la empresa SchoolNotes
@endsection

@section('footer3')
    <!--a target="_blank" href="https://viewstripo.email/">Preferencias</!--a> | 
    <a target="_blank" href="https://viewstripo.email/">Browser</a> | 
    <a target="_blank" href="https://viewstripo.email/">Forward</a> | 
    <a-- target="_blank" class="unsubscribe">Unsubscribe</a-->
@endsection

@section('copyrigth')
    Copyright © 2020 <strong>SchoolNotes</strong>, Todos los derechos reservados
@endsection

@section('logo2')
    <a target="_blank" href="#">
        <img src="{{$imagenes['book']}}" alt width="125">
    </a>
@endsection


