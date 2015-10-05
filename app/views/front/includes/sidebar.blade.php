<div class="col-md-3 sidebar">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select">
                                <div class="button">SON YAZILAR</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php 
                        $latestsnews = admin\News::limit(5)->where('published', 1)->orderBy('id','desc')->with('user')->get();
                        ?>

                        @foreach($latestsnews as $latest)
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url({{asset('http://www.ekranella.com/uploads/'. $latest->img . '_thumb.jpg')}});" href="{{action('FrontNewsController@getNews', ['permalink' => $latest->permalink])}}">
                                <div class="txt">
                                    <div class="box_title news_title">HABERLER</div>
                                    <div class="desc">{{$latest->title}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach

                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select">
                                <div class="button">TAKİP ET</div>
                            </div>
                        </div>
                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select ekranella_selected">
                                <div class="button active">EKRANELLA</div>
                            </div>
                            <a href="http://www.idefix.com/kitap/ekranella-kolektif/tanim.asp?sid=ACNNGKEGG6CYBZ4CNYP2#sthash.HZjGcV0j.dpuf" target="_blank">
                                <img src="{{ asset('assets/img/ekranella_kitap.jpg') }}" alt="kitap" width="100%">
                            </a>
                        </div>
                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select interview_selected">
                                <div class="button active">RÖPORTAJ</div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $latestinterviews=admin\Interviews::limit(1)->where('published', 1)->orderBy('created_at', 'desc')->get();
                    ?>

                    @foreach($latestinterviews as $linterview)
                    <div class="row">
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$linterview->img.'_thumb.jpg')}}" alt="{{$linterview->title}}" href="{{action('front.interviews.interviewDetail',['permalink'=>$linterview->permalink])}}">
                                <div class="txt">
                                    <div class="box_title news_title">RÖPORTAJ</div>
                                    <div class="desc">{{$linterview->title}}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select ekranella_selected">
                                <div class="button active">KÖŞE YAZILARI</div>
                            </div>
                        </div>
                    </div>

                    <?php 
                    $articles = admin\Article::limit(1)->where('published', 1)->orderBy('created_at', 'desc')->get();
                    ?>
                    @foreach($articles as $article)
                    <div class="row">
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$article->img.'_thumb.jpg')}});">
                                <div class="txt">
                                    <div class="box_title news_title">KÖSE YAZILARI</div>
                                    <div class="desc">{{ $article->title }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>