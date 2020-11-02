@extends('layouts.mailWarnBan')

@section('asunto')
    ¡HAS RECIBIDO UN WARNING! 🚧
@endsection

@section('cuerpo1')
    ¿Que es un Warning? 😲
@endsection

@section('cuerpo2')
    Un warning son advertencias que manejamos en SchoolNotes para mantener la plataforma lo mas segura posible gracias a la comunidad,
    @if($warning['total_warnings'] == 1)
        al parecer has recibido tu primer warning, 
    @else
        podemos observar que ya tienes {{$warning['total_warnings']}} warnings en tu cuenta,
    @endif
    pero debemos advertirte que si llegas a 3 tu cuenta podria llegar a ser baneada (prohibida) y nunca jamas podras utilizar SchoolNotes 
    <br><br><br>
    <strong>Motivo nadarrado por un administrador</strong>
    <p>{{$warning->motivo}}</p>
    <br><br>
    Si crees que esto es un error. <br>
    Puedes contactar al correo electronico oficial schoolnotes.info@gmail.com para tratar asuntos sobre tu warning y ver que podemos solucionar en tu caso
@endsection

@section('despedida')
    Sin mas que decir {{$data->name}} , te agradecemos el equipo de SchoolNotes por usar nuestros servicios 🥳.
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


