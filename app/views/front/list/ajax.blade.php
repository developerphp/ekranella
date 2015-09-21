@foreach($list as $item)
    <li>
        <a href="{{action($item['action'],['permalink' => $item['permalink']])}}">
            <div class="img">
                <figure><img src="{{asset('uploads/'.$item['img'].'_thumb.jpg')}}"
                             alt="{{$item['title']}}"></figure>
            </div>
            <div class="text">
                @if(isset($item['serial']))
                    <h3><span class="pink">{{$item['serial']->title}}</span></h3>
                @endif
                <h3>{{$item['title']}}</h3>

                <p>@if(isset($item['summary'])){{\BaseController::shorten($item['summary'], 150)}}@endif</p>
                @if(isset($item['season']))
                    <small><strong>Sezon: {{{$item['season']}}},
                            Bölüm: {{{$item['number']}}}</strong> |
                    </small>@endif
                @if(isset($item['date']) && $item['date'] != '' )
                    <small>@if(isset($item['season'])) | @endif{{{$item['date']}}}</small>
                @endif
                <br/>
                @if(isset($item['alias']))
                    <small><i>{{$item['alias']}}</i></small> @endif
                @if(isset($item['username']))<span
                        class="pink">@if($item['is_author']) {{$item['username']}} @else {{$item['guest_author']}} @endif</span>@endif
            </div>
        </a>
    </li>
@endforeach