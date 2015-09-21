<footer>
    <div class="wrapper" style="width: 100%;">
        <div class="footer-wrapper">
            <div class="left">
                <span class="icn-foot-logo">EKRANELLA</span>

                <p>Bu sitede yer alan yazılardan yazarların kendisi sorumludur.<br>Referans vermeden kullanmayınız.</p>
                <ul class="social-icons">
                    @if($fsocial['facebook'] != '')<li><a target="_blank"  href="{{$fsocial['facebook']}}" class="icn-fb">Facebook</a></li>@endif
                    @if($fsocial['twitter'] != '')<li><a target="_blank" href="{{$fsocial['twitter']}}" class="icn-tw">Twitter</a></li>@endif
                    @if($fsocial['instagram'] != '')<li><a target="_blank" href="{{$fsocial['instagram']}}" class="icn-ig">Instagram</a></li>@endif
                    @if($fsocial['youtube'] != '')<li><a target="_blank" href="{{$fsocial['youtube']}}" class="icn-yt">YouTube</a></li>@endif
                    @if($fsocial['youtube'] != '')<li><a target="_blank" href="{{$fsocial['tumblr']}}" class="icn-tmblr">Tumblr</a></li>@endif
                    @if($fsocial['pinterest'] != '')<li><a target="_blank" href="{{$fsocial['pinterest']}}" class="icn-pin">Pinterest</a></li>@endif
                </ul>
            </div>
            <nav class="foot-nav">
                <ul>
                    <li><a href="{{action('front.serial.localIndex')}}">Yerli Diziler</a></li>
                    <li><a href="{{action('front.news.specialNews')}}">Özel</a></li>
                    <li><a href="{{action('front.rating.index')}}">Reyting</a></li>
                    <li><a href="{{action('front.serial.foreignIndex')}}">Yabancı Diziler</a></li>
                    <li><a href="{{action('front.interviews.index')}}">Röportaj</a></li>
                    <li><a href="{{action('front.authors.index')}}">Yazarlar</a></li>
                    <li><a href="{{action('front.serial.programIndex')}}">Programlar</a></li>
                    <li><a href="{{action('front.news.photoNews')}}">FotoHaber</a></li>
                    <li><a href="{{action('front.home.contact')}}">İletişim</a></li>
                </ul>
            </nav>
        </div>
    </div>
</footer>
<script>
    $(document).ready(function () {
        $('.adclick').click(function () {
            if (!$(this).hasClass('clicked')) {
                var id = $(this).data('id');
                $.get('{{action('front.ad.click')}}' + '/' + id);
                console.log('ad-click-call');
                $(this).addClass('clicked');
            }
        });
    });
</script>