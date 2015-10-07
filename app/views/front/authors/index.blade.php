@extends('front.layout')

@section('content')
    <section id="editors" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select editors_selected">
                    <div class="button active">YAZARLAR</div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($authors as $author)
                @if($author->username != 'lupos')
                    <div class="col-md-3 home_boxes authors_box">
                        <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/'.$author->pp)}});" href="{{action('front.authors.detail',['permalink' => $author->id])}}">
                            <div class="txt">
                                <div class="box_title editors_title">YAZAR</div>
                                <?php
                                    $names = explode(' ', $author->name);
                                    $names[0] = '<strong>' . $names[0];
                                    $names[count($names) - 1] = '</strong>' . $names[count($names) - 1];
                                    $name = implode(' ', $names);
                                ?>
                                <div class="desc">{{$name}}</div>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </section>
@endsection
