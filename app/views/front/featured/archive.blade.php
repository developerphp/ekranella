@extends('front.layout')

@section('content')
    <div class="header-image">
        <div class="masked"></div>
    </div>
    <div class="listing-wrap">
        <article>
            <h1>{{$as}}</h1>
            <ul>
                <?php $i = 0;   ?>
                @foreach($archives as $archive)
                    @if($i == 4)
                        <li>
                            @if(isset($sqad))
                                @if($sqad->type == 0)
                                    <figure>{{$sqad->embed}}</figure>
                                @else
                                    {{$sqad->embed}}
                                @endif
                            @else
                                <figure><img src="{{asset('a/img/gallery_thumbs/reklam.jpg')}}" alt="FARGO"></figure>
                            @endif
                        </li>
                    @endif
                    <li>
                        <a href="{{action('front.featured.featuredDetail', ['permalink' => $archive->permalink])}}">
                            <figure><img src="{{asset($archive->img)}}" alt="{{$archive->title}}"></figure>
                            <p>{{$archive->title}}</p>
                        </a>
                    </li>

                    <?php ++$i ?>
                @endforeach
            </ul>
        </article>
    </div>
@endsection