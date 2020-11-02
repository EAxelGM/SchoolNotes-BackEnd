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
    BIENVENIDO <br /> {{$data->name}}
@endsection

@section('descripcion')
    Gracias por registrarte, solo falta activar tu cuenta  <br /> {{$data->email}} ✔
@endsection

@section('boton')
    <!--a href="{{ url('/validar/'.$data->id.'/'.$data->email) }}" class="es-button" target="_blank" style="background: #00cba0 none repeat scroll 0% 0%; border-color: #00cba0;">
        Verificar Correo
    </a-->
@endsection

@section('asunto')
    ¡YA CASI TERMINAS! 🚧
@endsection

@section('cuerpo1')
    Para activar tu cuenta da clic en este siguiente boton 😋
@endsection

@section('cuerpo2')
    <center>
        <a href="{{ url('/validar/'.$data->id.'/'.$data->token_verificacion['token']) }}" class="es-button" target="_blank" style="background: #ffffff none repeat scroll 0% 0%; border-color: #ffffff; color: #00cba0; border-width: 15px 25px;">
            <button style="background: #C5C5C5 none repeat scroll 0% 0%; border-color: #00cba0;">
                ACTIVAR CUENTA ➡            
            </button>
        </a>
    </center>
@endsection

@section('despedida')
    Gracias por registrarte en nuestra plataforma, esperemos te diviertas, mucha suerte te desea el equipo de SchoolNotes 🥳
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
    <!--schoolnotes.info@gmail.com -->
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


