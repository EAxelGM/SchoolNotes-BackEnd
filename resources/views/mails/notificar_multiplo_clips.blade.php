@extends('layouts.mailWarnBan')

@section('asunto')
    ¡Felicidades {{$data['name']}}, por llegar a los {{$data['clips']}} clips!
@endsection

@section('cuerpo1')
    Estamos muy contentos de que estes usando de la plataforma SchoolNotes.
@endsection

@section('cuerpo2')
    Sin mas que decir gracias por ser un usuario activo, recuerda que muy pronto tendremos muchas sospresas mas, asi que nunca olvides invitar a mas de tus amigos para ganar mas Clips!
    <br><br>
    @if($data['codigo_creador'] != null)
      No olvides compartir tu codigo de creador <a href="https://schoolnotes.live/editar-perfil">{{$data['codigo_creador']['codigo']}}</a>  para ganar mas clips al momento de que un usuario se registre, y/o hagan una compra.
    @else
      Hemos notado que aun no has creado tu codigo de creador, deberias crear uno ahora mismo, es muy sencillo, solo da <a href="https://schoolnotes.live/editar-perfil">Clic Aqui</a> y crea tu codigo de creador para empezarlo a compartir y asi ganaras clips al momento de que alguien se registra o realiza una compra usando tu codigo de creador!
    @endif
@endsection

@section('despedida')
    ¡Nunca pares de aprender!, El equipo de SchoolNotes te da las Gracias por seguir con nosotros.
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
