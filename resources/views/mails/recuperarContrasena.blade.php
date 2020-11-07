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
    RECUPERA TU CONTRASEÑA
@endsection

@section('descripcion')
    Al parecer has olvidado tu contraseña pero no te preocupes, con SchoolNotes la puedes restablecer muy facil.
@endsection

@section('boton')
    <!--a href="{{ url('/validar/'.$data->id.'/'.$data->email) }}" class="es-button" target="_blank" style="background: #00cba0 none repeat scroll 0% 0%; border-color: #00cba0;">
        Verificar Correo
    </a-->
@endsection

@section('asunto')
    ¿No fuiste tu quien solicito un cambio de contraseña?
@endsection

@section('cuerpo1')
    </strong>Si tu no hiciste esta solicitud, simplemente ignora este correo, tu contraseña esta totalmente segura al igual que tu cuenta.<strong>
@endsection

@section('cuerpo2')
    Si has solicitado este restablecimiento de contraseña solo tienes que darle clic al boton que dice "Restablecer Contraseña", y te enviara a un formulario para ingresar tu nueva contraseña. <br><br>
    Recuerda que tu cuenta y contraseña son muy importantes, recomendamos no pasarle tu contraseña a tus amigos.
    <br><br>
    <center>
        <a href="{{ url('https://schoolnotes.live/recuperar-password?email='.$data->email.'&token='.$data->token_verificacion['token']) }}" class="es-button" target="_blank" style="background: #ffffff none repeat scroll 0% 0%; border-color: #ffffff; color: #00cba0; border-width: 15px 25px;">
            <button style="background: #C5C5C5 none repeat scroll 0% 0%; border-color: #00cba0;">
                Recuperar Password            
            </button>
        </a>
    </center>
@endsection

@section('despedida')
Te damos las gracias por confiar. El equipo de SchoolNotes
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