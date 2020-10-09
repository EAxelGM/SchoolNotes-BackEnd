@extends('layouts.mailWarnBan')

@section('asunto')
    ¡TU CUENTA HA SIDO BANEADA (SUSPENDIDA)! 🚧
@endsection

@section('cuerpo1')
    ¿Por que suspendimos tu cuenta? 😲
@endsection

@section('cuerpo2')
    Tu cuenta ha recibido {{$data['warnings']}} warnings, lo cual 3 warnings es un <strong>Baneo Automatico</strong>, esto quiere decir muchas cosas, archivos vacios, ofenzas a la comunidad, contenido 🔞, etc. por eso mismo tu baneo ha sido automatico.
    <br><br>
    Igual manera si piensas que tu ultimo warning o los demas warnings han sido un error, puedes contactarnos para solucionar o reactivar tu cuenta, siempre y cuando sea con honestidad y respeto.
@endsection

@section('despedida')
    El equipo de SchoolNotes.
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


