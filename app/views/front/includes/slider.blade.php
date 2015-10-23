<section id="home_slider" class="container">
            <div class="slider">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">

                    <ol class="carousel-indicators">
                        <?php $i=0; ?>
                        @for($i==0;$i<=count($sliders)-1;$i++)
                        <li data-target="#myCarousel" data-slide-to="{{ $i }}" @if($i==0)class="active"@endif></li>
                        @endfor
                    </ol>

                    <div class="carousel-inner" role="listbox">  
                        <?php $i=0; ?>                   
                        @foreach($sliders as $slide)

                        <?php
                            $url = null;
                            $subfolder = null;
                            switch ($slide['item']['enumNumber']) {
                                case 1:
                                    $url = 'subEnum';
                                    $subfolder = 'bolum';                                    
                                    if ($slide['item']['enum']==1) { $categorie_title= 'ÖZEL'; }
                                    elseif ($slide['item']['enum']==2) { $categorie_title= 'ÖZETLİYORUM'; }
                                    elseif ($slide['item']['enum']==4) { $categorie_title= 'GALERİ'; }
                                    else { $categorie_title= 'BÖLÜM'; }
                                    break;
                                case 2:
                                    $url = action('front.interviews.interviewDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'roportaj';
                                    $categorie_title= 'RÖPORTAJ';
                                    break;
                                case 3:
                                    $url = action('front.news.newsDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'haber';
                                    if ($slide['item']['type']==2) { $categorie_title="ÖZEL"; }
                                    else { $categorie_title= 'HABER'; }                                    
                                    break;
                                case 4:
                                    $url = action('front.article.articleDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'kose';
                                    $categorie_title= 'KÖŞE YAZISI';
                                    break;
                                default;
                            }

                            //TODO enter specials, trailer, sgallery
                            if ($url == 'subEnum') {
                                switch ($slide['item']['enum']) {
                                    case 1:
                                        $url = action('front.serial.specialDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 2:
                                        $url = action('front.serial.episodeDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 3:
                                        $url = action('front.serial.trailerDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 4:
                                        $url = action('front.serial.sgalleryDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    default;
                                }
                            }
                            //Hotfix
                            if ($subfolder == 'haber') {
                                if ($slide['item']['type'] == 2) {
                                    $url = action('front.news.specialNewsDetail', ['permalink' => $slide['item']['permalink']]);
                                }
                            }
                            ?>
                            <a href="{{$url}}" class="item @if($i==0) active @endif">
                                <div class="txt">
                                    <div class="box_title ekranella_title"><?php echo $categorie_title ?></div>
                                    <div class="title">{{$slide['title']}}</div>
                                    <div class="desc">{{strip_tags($slide['text'])}}</div>
                                </div>
                                <img src="{{asset('http://www.ekranella.com/uploads/'.$slide['img'].'_slideThumb.jpg')}}" alt="{{$slide['title']}}">
                            </a> 
                        <?php                         
                        $i++;                        
                        ?>
                        @endforeach                    
                    </div>
                    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="slider_boxes">
                    <div class="col-md-12 col-sm-6 slider_boxes">
                    <?php 
                    $featured = admin\Featured::where('permalink', 'anketler')->with('tags')->first();
                    $tags = $featured->tags()->select(DB::raw('`tags`.`id` as tagid'))->lists('tagid');
                    $polls = $featured->news()->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->orderBy('id','desc')->take(1)->get();
                    foreach($polls as $pool) { ?>
                        <div class="col-md-12 slider_boxes">
                            <a href="{{action('front.news.newsDetail', ['permalink' => $pool['permalink']])}}" class="box" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$pool['img'].'_thumb.jpg')}}););">
                                <div class="txt">
                                    <div class="box_title ekranella_title">ANKET</div>
                                    <div class="desc"><?php echo $pool->title ?></div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>                    
                    </div>
                    <div class="col-md-12 col-sm-6 slider_boxes">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- Slider Yani 250-250 -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:250px;height:250px"
                             data-ad-client="ca-pub-2693683830637074"
                             data-ad-slot="6278302948"></ins>
                        <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                        <!-- <iframe src="http://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fekranella&amp;width=262&amp;height=235&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:235px;" allowtransparency="true"></iframe> -->
                    </div>
            </div>
</section>
