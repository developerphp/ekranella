@extends('front.layout')

@section('content')
    <section id="fourofour" class="container-fluid">
        <div class="row">
            <div class="banner" style="background-image: url({{asset('assets/img/404.jpg')}});">
                <img src="{{asset('assets/img/404_img.png')}}" alt="404">
                <a href="{{url()}}" class="button">ANA SAYFA</a>
            </div>
        </div>
    </section>
@endsection