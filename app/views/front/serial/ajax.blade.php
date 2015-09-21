@foreach($serials as $serial)
    <li>
        <a href="{{action('front.serial.detail',['permalink' => $serial->permalink])}}">
            <figure><img src="{{asset($serial->img)}}" alt="{{$serial->title}}"></figure>
            <p class="name">{{$serial->title}}</p>
            <!--<span class="serial_marked">{{$serial->title}}</span>-->
        </a>
    </li>
@endforeach