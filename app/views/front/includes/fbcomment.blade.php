    <div id="commentBoxFb">
        <div id="facebookLoginBtn"></div>
        <div class="fb-comments" data-href="{{Request::url()}}" data-width="488" data-numposts="10" data-colorscheme="light"></div>
    </div>
    <br />
    <br />
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = 'ekranella';

        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>