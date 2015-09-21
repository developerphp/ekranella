@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li><a href="#">Genel</a></li>
            @if($type == 1)
                <li><a href="{{action('SerialController@getForeignSeries')}}">Yabancı Diziler</a></li>
            @elseif($type == 2)
                <li><a href="{{action('SerialController@getLocalSeries')}}">Yerli Diziler</a></li>
            @else
                <li><a href="{{action('SerialController@getLocalSeries')}}">Programlar</a></li>
            @endif

            <li class="active">{{$title}}</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="block-flat">
                @if (isset($error) && $error && $error !== 0 )
                    <div class="alert alert-danger alert-white rounded">
                        <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <div class="icon"><i class="fa fa-times-circle"></i></div>
                        <strong>Hata!</strong>

                        <div>{{ $error }}</div>
                    </div>
                @endif
                <form method="post" enctype="multipart/form-data">
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Fragman Başlığı</label>
                                <input type="text" class="form-control title" name="title" value="{{{$item->title}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Bölüm Numarası</label>
                                <input type="text" name="number" class="form-control" value="{{{$item->number}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Sezonu</label>
                                <select name="season_id" class="select2">
                                    @foreach($seasons as $season)
                                        <option value="{{ $season['id'] }}"
                                        @if(isset($item->season_id) && $item->season_id == $season['id'])
                                                selected @endif >{{ $season['number'] }}. Sezon
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">{{$as}} Video Embed Kodu</label>

                                    <div id="contentEditableDiv"
                                         style="border: solid 1px #c0c0c0; height: 150px; outline-color: #ff1493"
                                         contenteditable="true">{{{$item->content}}}</div>
                                    <input type="hidden" name="content" id="contentEditableRef"/>
                                    <script>
                                        $(document).ready(function () {
                                            $(".saveTrailer").click(function (e) {
                                                e.preventDefault();
                                                $('#contentEditableRef').val($('#contentEditableDiv').html());
                                                $(this).parents('form').submit();
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-xs btn-info btn-flat youtubeEmbed">Youtube Görselini
                                Çek
                            </button>
                        </div>

                        <div class="row no-margin" id="img_preview" style="display:none">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <img id="masterImg" width="100%" @if($item->img)
                                                 src="{{{asset('uploads/'.$item->img.'_main.jpg')}}}"
                                                 @endif style="height-max:500px"/>
                                        </div>
                                    </div>
                                </div>
                                <div style="float: left; width: 180px; margin: 20px">
                                    <b style="display: block;float: left">{{$as}} Yatay</b>

                                    <div class="preview img-preview preview-md thumb"></div>
                                <span data-name="thumb" class="thumbButton savePicBtn btn-success btn-flat btn-sm btn "
                                      style="display: block;float: left;">Kaydet</span>
                                    <input type="hidden" value="" name="thumb"/>
                                </div>

                                <div style="float: left; width: 150px; margin: 20px">
                                    <b style="display: block;float: left;">{{$as}} Kare</b>

                                    <div class="preview img-preview preview-md square"></div>
                                <span data-name="square"
                                      class="squareButton savePicBtn btn-success btn-flat btn-sm btn "
                                      style="display: block;float: left;">Kaydet</span>
                                    <input type="hidden" value="" name="square"/>
                                </div>


                                <div style="float: left; width: 150px; margin: 20px;">
                                    <b style="display: block;float: left;">{{$as}} Geniş Yatay</b>

                                    <div class="preview img-preview preview-md-l thumbl"></div>
                                <span data-name="thumbl"
                                      class="thumblButton savePicBtn btn-success btn-flat btn-sm btn "
                                      style="display: block;float: left;">Kaydet</span>
                                    <input type="hidden" value="" name="thumbl"/>
                                </div>


                            </div>
                        </div


                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">{{$as}} Ana Görseli</label>
                                    <input type="file" name="img" class="form-control" value="{{{$item->img}}}"/>
                                    <input type="hidden" name="mainImg" class="mainImg form-control" value=""/>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function () {
                                    var $image = null;
                                    var images = {
                                        "thumb": {"width": 196, height: 136, 'name': 'thumb', 'next': 'square'},
                                        "square": {"width": 136, height: 136, 'name': 'square', 'next': 'thumbl'},
                                        "thumbl": {"width": 296, height: 136, 'name': 'thumbl', 'next': null}

                                    };

                                    var current = 'thumb';
                                    var isEnd = false;

                                    $('.youtubeEmbed').click(function () {
                                        var reg = new RegExp('(?:https?://)?(?:www\\.)?(?:youtu\\.be/|youtube\\.com(?:/embed/|/v/|/watch\\?v=))([\\w-]{10,12})', 'g');
                                        var id = reg.exec($('#contentEditableDiv').html());
                                        if (id == null)
                                            alert('Embed kodunda youtube bilgisi bulunamadı');
                                        else
                                            $.post("{{action('SerialController@postTempBaseImage')}}", {'image': 'http://img.youtube.com/vi/' + id[1] + '/0.jpg'}, function (data) {
                                                $('#masterImg').attr('src', data.src);
                                                $('#img_preview').slideDown();
                                                $('.savePicBtn').text('Düzenle');
                                                current = null;
                                                isEnd = true;
                                                generateCropObject(null);
                                                $image.cropper("reset");
                                            });

                                    });

                                    $("input[name='img']").change(function () {
                                        var input = $(this).get(0);
                                        var file = input.files[0];
                                        var fr = new FileReader();
                                        fr.readAsDataURL(file);
                                        fr.onloadend = function () {
                                            var img = fr.result;
                                            var imgContainer = $('#img_preview');
                                            imgContainer.find('img').attr('src', img);
                                            $('.mainImg').val(img);
                                            imgContainer.slideDown(2000);

                                            if ($image != null)
                                                $image.cropper("reset");

                                            console.log(current);

                                            if (current != null)
                                                generateCropObject(current);

                                        };

                                    });

                                    @if($item->img != null)
                                    $('#img_preview').slideDown(2000);
                                    current = null;
                                    isEnd = true;
                                    $('.savePicBtn').text('Düzenle');
                                    $('.preview.thumb').html('<img src="{{{asset('uploads/'.$item->img.'_thumb.jpg')}}}" style="height:100%" />');
                                    $('.preview.square').html('<img src="{{{asset('uploads/'.$item->img.'_square.jpg')}}}" style="height:100%"/>');
                                    $('.preview.thumbl').html('<img src="{{{asset('uploads/'.$item->img.'_thumbl.jpg')}}}" style="height:100%" />');

                                    @endif

                                    function generateCropObject(current) {
                                        $image = $("#masterImg"),
                                                $dataX = $("#dataX"),
                                                $dataY = $("#dataY"),
                                                $dataHeight = $("#dataHeight"),
                                                $dataWidth = $("#dataWidth");

                                        $image.cropper({
                                            preview: ".img-preview." + images[current]['name'],
                                            aspectRatio: images[current]['width'] / images[current]['height'],
                                            done: function (data) {
                                                $dataX.val(data.x);
                                                $dataY.val(data.y);
                                                $dataHeight.val(data.height);
                                                $dataWidth.val(data.width);
                                            }
                                        });
                                    }

                                    $('.savePicBtn').click(function () {
                                        if (current == null) {
                                            $(this).text('Kaydet');
                                            current = $(this).data('name');
                                            generateCropObject(current);
                                        } else {
                                            current = $(this).data('name');
                                            var currentObject = images[current];

                                            if ($(this).text().search("Düzenle") < 1) {
                                                $(this).text('Düzenle');
                                            }

                                            var thumbSource = $image.cropper("getDataURL", "image/jpeg");
                                            $image.cropper("destroy");
                                            $('input[name="' + currentObject['name'] + '"]').val(thumbSource);
                                            var thumb = $('<img/>', {
                                                'src': thumbSource,
                                                width: currentObject['width'],
                                                height: currentObject['height']
                                            });

                                            $(".img-preview." + currentObject['name']).append(thumb);

                                            if (currentObject['next'] != null && !isEnd) {
                                                current = currentObject['next'];
                                                generateCropObject(current);
                                            } else {
                                                current = null;
                                                isEnd = true;
                                            }
                                        }
                                    });
                                });
                            </script>
                        </div>


                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">{{$as}} Fragman etiketleri</label>
                                    <?php $tags = []; if ($item->tags) foreach ($item->tags()->get()->toArray() as $tag) {
                                        $tags[] = $tag['title'];
                                    }  $tags = implode(',', $tags); ?>
                                    <input class="tags" type="hidden" name="tags" value="<?= $tags ?>"/>
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
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Yazarı devre dışı bırak</label>
                                    <small>Eğer yazarın görünmemesini istiyorsanız veya sistemde bulunmayan bir yazarın
                                        ismini girmek istiyorsanız kapatın
                                    </small>
                                    <div class="switch">
                                        <input class="is_author" type="checkbox"
                                               name="is_author" @if($item->is_author || $item->is_author == "")
                                               checked @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin guest" @if($item->is_author || $item->is_author == "")
                             style="display: none" @endif>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Misafir Yazar İsmi (Opsiyonel)</label>
                                    <input type="text" class="form-control" name="guest_author"
                                           value="{{{$item->guest_author}}}"/>
                                </div>
                            </div>
                        </div>
                        @if(\admin\RoleController::isAdmin())
                            <div class="row no-margin author_alias" @if(!($item->is_author || $item->is_author == ""))
                                 style="display: none" @endif>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label class="control-label">Bu içeriği bir başka yazara ata</label>
                                        <select class="select2" name="created_by">
                                            <option value="0">...</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}" @if($item->created_by == $user->id)
                                                        selected @endif>{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Gönderi Tarihi</label>
                                    <small>Eğer gönderi geçmişte gönderilmişse buradan yayınlanma tarihini
                                        değiştirebilirsiniz
                                    </small>
                                    <input class="form-control datetime" size="16" type="text" name="created_at"
                                           value="@if(isset($item->created_at)){{$item->created_at}}@endif">
                                </div>
                            </div>
                        </div>
                        <input class="publish" type="hidden" name="published" value="<?= $item->published ?>"/>

                        <div class="row no-margin">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <a href="javascript:window.history.back();" class="btn btn-primary btn-flat"><i
                                                class="fa fa-backward"></i> Geri Dön</a>
                                    @if(isset($item->id) && $item->id != null)
                                        <button class="btn save episodeBtn btn-success saveTrailer btn-flat"><i
                                                    class="fa fa-floppy-o"></i> Taslak olarak güncelle
                                            @else
                                                <button class="btn save episodeBtn btn-success saveTrailer btn-flat"><i
                                                            class="fa fa-plus"></i> Taslak olarak ekle
                                                    @endif
                                                </button>
                                                <button class="btn publish episodeBtn btn-warning saveTrailer btn-flat">
                                                    <i
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
                    </div>
                </form>
            </div>
        </div>

@endsection