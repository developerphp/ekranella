@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>{{$title}}</h2>
<ol class="breadcrumb">
  <li><a href="#">Genel</a></li>
    <li>Gündem</li>
    <li class="active">{{$title}}</li>
</ol>
</div>
@endsection

@section('content')
<div class="content"><div class="col-md-12">
    <div class="block-flat">
        @if (isset($error) && $error && $error !== 0 )
        <div class="alert alert-danger alert-white rounded">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <div class="icon"><i class="fa fa-times-circle"></i></div>
            <strong>Hata!</strong>

            <div>{{ $error }}</div>
        </div>
        @endif
        <form id="content-form" method="post" enctype="multipart/form-data">
            <div class="row no-margin">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">{{$as}} Başlığı</label>
                        <input type="text" name="title" class="form-control title" value="{{{$item->title}}}" />
                    </div>
                </div>
            </div>


            <div class="row no-margin">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">{{$as}} Alt Başlıkları</label>
                        <?php $tags = []; if($item->tags) foreach($item->tags()->get()->toArray() as $tag){ $tags[] = $tag['title'];}  $tags = implode(',',$tags); ?>
                        <input class="tags" type="hidden" name="tags" value="<?= $tags ?>" />
                    </div>
                </div>
            </div>

            @if($item->cover != "")
                <div class="row">
                    <div class="col-md-offset-3">
                        <div class="gallery-cont">
                            <div class="item">
                                <div class="photo">
                                    <div class="img">
                                        <img src="{{asset($item->cover)}}"/>

                                        <div class="over">
                                            <div class="func"><a class="image-zoom"
                                                                 href="{{asset($item->cover)}}"><i
                                                            class="fa fa-search"></i></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row no-margin">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">{{$as}} Kapak Görseli</label>
                        <input type="file" name="cover" class="form-control" value="{{{$item->cover}}}"/>
                    </div>
                </div>
            </div>

            @if($item->img != "")
                <div class="row">
                    <div class="col-md-offset-3">
                        <div class="gallery-cont">
                            <div class="item">
                                <div class="photo">
                                    <div class="img">
                                        <img src="{{asset($item->img)}}"/>

                                        <div class="over">
                                            <div class="func"><a class="image-zoom"
                                                                 href="{{asset($item->img)}}"><i
                                                            class="fa fa-search"></i></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row no-margin">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">{{$as}} Küçük Görseli</label> (Önerilen Boyut 244x207)
                        <input type="file" name="img" class="form-control" value="{{{$item->img}}}"/>
                    </div>
                </div>
            </div>

            <div class="row no-margin">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">{{$as}} Permalink</label>
                        <input type="text" class="form-control permalink" name="permalink"
                               value="{{{$item->permalink}}}"/>
                    </div>
                </div>
            </div>
            <input class="publish" type="hidden" name="published" value="<?= $item->published ?>"/>
            <div class="row no-margin">
                <div class="col-sm-12">
                    <div class="form-group">
                        <a href="javascript:window.history.back();" class="btn btn-primary btn-flat"><i class="fa fa-backward"></i> Geri Dön</a>
                            @if(isset($item->id) && $item->id != null)
                            <button class="btn save episodeBtn btn-success btn-flat"><i class="fa fa-floppy-o"></i> Taslak olarak güncelle
                            @else
                            <button class="btn save episodeBtn btn-success btn-flat"><i class="fa fa-plus"></i> Taslak olarak ekle
                            @endif
                             </button>

                            <button class="btn publish episodeBtn btn-warning btn-flat"><i
                                        class="fa fa-check-circle-o"></i>
                                @if($item->published)
                                    Yayını Güncelle
                                @else
                                    Yayınla
                                @endif
                            </button>

                            @if($item->published)
                                İçerik yayında
                            @else
                                İçerik yayında değil
                            @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection