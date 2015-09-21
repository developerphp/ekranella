<div class="right">
    @if(isset($serial))
        @if($serial['type'] == 1)
            @if(isset($foreignad))
                <div class="banner">
                    <div class="adclick clickable" data-id="{{$foreignad->id}}">
                        @if($foreignad->type == 0)
                            <figure>{{$foreignad->embed}}</figure>
                        @else
                            {{$foreignad->embed}}
                        @endif
                        <?php admin\ViewsController::upAdViews($foreignad); ?>
                    </div>
                </div>
            @else
                @if(isset($sqad))
                    <div class="banner">
                        <div class="adclick clickable" data-id="{{$sqad->id}}">
                            @if($sqad->type == 0)
                                <figure>{{$sqad->embed}}</figure>
                            @else
                                {{$sqad->embed}}
                            @endif
                            <?php admin\ViewsController::upAdViews($sqad); ?>
                        </div>
                    </div>
                @endif
            @endif
        @elseif($serial['type'] == 2)
            @if(isset($domesticad))
                <div class="banner">
                    <div class="adclick clickable" data-id="{{$domesticad->id}}">
                        @if($domesticad->type == 0)
                            <figure>{{$domesticad->embed}}</figure>
                        @else
                            {{$domesticad->embed}}
                        @endif
                        <?php admin\ViewsController::upAdViews($domesticad); ?>
                    </div>
                </div>
            @else
                @if(isset($sqad))
                    <div class="banner">
                        <div class="adclick clickable" data-id="{{$sqad->id}}">
                            @if($sqad->type == 0)
                                <figure>{{$sqad->embed}}</figure>
                            @else
                                {{$sqad->embed}}
                            @endif
                            <?php admin\ViewsController::upAdViews($sqad); ?>
                        </div>
                    </div>
                @endif
            @endif
        @else
            @if(isset($sqad))
                <div class="banner">
                    <div class="adclick clickable" data-id="{{$sqad->id}}">
                        @if($sqad->type == 0)
                            <figure>{{$sqad->embed}}</figure>
                        @else
                            {{$sqad->embed}}
                        @endif
                        <?php admin\ViewsController::upAdViews($sqad); ?>
                    </div>
                </div>
            @endif
        @endif
    @else
        @if(isset($sqad))
            <div class="banner">
                <div class="adclick clickable" data-id="{{$sqad->id}}">
                    @if($sqad->type == 0)
                        <figure>{{$sqad->embed}}</figure>
                    @else
                        {{$sqad->embed}}
                    @endif
                    <?php admin\ViewsController::upAdViews($sqad); ?>
                </div>
            </div>
        @endif
    @endif
    <div class="search" @if(!isset($sqad) && !isset($foreignad) && !isset($domesticad)) style="margin-top: 0px" @endif>
        <form method="get" action="{{action('front.search.index')}}">
            <input type="text" name="q" placeholder="Ara">
            <input type="submit" class="icn-search" value="">
        </form>
    </div>
    <div class="row twitt">
        <a href="https://twitter.com/Ekranella" class="twitter-follow-button" data-show-count="false" data-size="large">Takip
            Et: @Ekranella</a>
        <script>!function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                if (!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.src = p + '://platform.twitter.com/widgets.js';
                    fjs.parentNode.insertBefore(js, fjs);
                }
            }(document, 'script', 'twitter-wjs');</script>
        <p>Twitter hesabımızı takipte<br>kalın, <span class="pink">en yeni haberlerimizi</span><br>ve <span
                    class="pink">duyuruları</span>
            kaçırmayın.</p>
        @if(isset($serial->twitter) &&  $serial->twitter != '')
            <a href="https://twitter.com/{{{$serial->twitter}}}" class="twitter-follow-button" data-show-count="false">Takip
                Et: @{{{$serial->twitter}}}</a>
            <script>!function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = p + '://platform.twitter.com/widgets.js';
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, 'script', 'twitter-wjs');</script><p><span class="blue">Diziye Özel</span> Twitter<br><span
                        class="blue">hesabımızı</span> takip edebilirsiniz.</p>
        @endif
    </div>

    <div class="row">
        <iframe src="http://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fekranella&amp;width=220&amp;height=185&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false"
                scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:220px; height:185px;"
                allowTransparency="true"></iframe>
    </div>
    @if((isset($sidebarOthers) && count($sidebarOthers)>0) || (isset($vad) && count($vad)>0))
        <div class="row">
            @if(isset($sidebarOthers) && count($sidebarOthers)>0)
                <ul class="side-list">
                    @foreach($sidebarOthers as $other)
                        <li><a href="{{action($other->action, ['permalink' => $other->permalink])}}">
                                <figure><img src="{{asset('uploads')}}/{{$other->img}}_thumb.jpg"
                                             alt="{{$other->title}}">
                                </figure>
                                {{$other->title}}
                                @if(isset($other->alias))
                                    <br/>
                                    <small>{{$other->alias}}</small>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
            @if(isset($vad) && count($vad)>0)
                <div class="inner-banner">
                    <div class="adclick" data-id="{{$vad->id}}">
                        @if($vad->url != "")
                            <script>
                                $(document).ready(function(){
                                    $('.adclick').find('a').attr('href', '{{$vad->url}}');
                                });
                            </script>
                        @endif

                                {{$vad->embed}}

                        <?php admin\ViewsController::upAdViews($vad); ?>
                    </div>
                </div>
            @endif

        </div>
    @endif
</div>