@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li><a href="{{action('DashController@getDash')}}">Genel</a></li>
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
                                <input type="text" name="title" class="form-control title" value="{{{$item->title}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Sırası</label>
                                <input type="number" min="0" name="order" class="form-control title" value="{{{$item->order}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Metni</label>
                                <textarea class="ckeditor form-control" name="text">{{{$item->text}}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin" id="img_preview" style="display:none">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <img id="masterImg" width="100%" src="{{{asset('uploads/'.$item->img.'_main.jpg')}}}" style="height-max:500px"/>
                                    </div>
                                </div>
                            </div>

                            <div style="float: left; width: 150px; margin: 20px;">
                                <b style="display: block;float: left;">{{$as}} Görseli</b>

                                <div class="preview img-preview preview-md-l slideThumb" style="zoom:.3"></div>
                                <span data-name="slideThumb" class="slideThumbButton savePicBtn btn-success btn-flat btn-sm btn "
                                      style="display: block;float: left;">Kaydet</span>
                                <input type="hidden" value="" name="slideThumb" />
                            </div>


                        </div>
                    </div>

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
                                    "slideThumb": {"width": 936, height: 457, 'name': 'slideThumb', 'next': null}

                                };

                                var current = 'slideThumb';
                                var isEnd = false;

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

                                        if(current != null)
                                            generateCropObject(current);

                                    };

                                });

                                @if($item->img != null)
                                $('#img_preview').slideDown(2000);
                                current = null;
                                isEnd = true;
                                $('.savePicBtn').text('Düzenle');
                                $('.preview.slideThumb').html('<img src="{{{asset('uploads/'.$item->img.'_slideThumb.jpg')}}}" style="height:100%" />');

                                @endif

                                function generateCropObject(current){
                                    $image = $("#masterImg"),
                                            $dataX = $("#dataX"),
                                            $dataY = $("#dataY"),
                                            $dataHeight = $("#dataHeight"),
                                            $dataWidth = $("#dataWidth");

                                    $image.cropper({
                                        preview: ".img-preview."+images[current]['name'],
                                        aspectRatio: images[current]['width'] / images[current]['height'],
                                        done: function (data) {
                                            $dataX.val(data.x);
                                            $dataY.val(data.y);
                                            $dataHeight.val(data.height);
                                            $dataWidth.val(data.width);
                                        }
                                    });
                                }

                                $('.savePicBtn').click(function(){
                                    if(current == null){
                                        $(this).text('Kaydet');
                                        current = $(this).data('name');
                                        generateCropObject(current);
                                    }else{
                                        current = $(this).data('name');
                                        var currentObject = images[current];

                                        if($(this).text().search("Düzenle") < 1){
                                            $(this).text('Düzenle');
                                        }

                                        var thumbSource = $image.cropper("getDataURL", "image/jpeg");
                                        $image.cropper("destroy");
                                        $('input[name="'+currentObject['name']+'"]').val(thumbSource);
                                        var thumb = $('<img/>', {
                                            'src': thumbSource,
                                            width: currentObject['width'],
                                            height: currentObject['height']
                                        });

                                        $(".img-preview."+currentObject['name']).append(thumb);

                                        if(currentObject['next'] != null && !isEnd){
                                            current = currentObject['next'];
                                            generateCropObject(current);
                                        }else{
                                            current = null;
                                            isEnd = true;
                                        }
                                    }
                                });
                            });
                        </script>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $(".contentSelect").select2({
                                minimumInputLength: 3,
                                ajax: {
                                    url: "{{action('FeaturedController@getSearchItem')}}",
                                    dataType: 'json',
                                    data: function (term, page) {
                                        return {
                                            q: term // search term
                                        };
                                    },
                                    results: function (data, page) {
                                        console.log(data);

                                        return {results: data};
                                    },
                                    cache: true
                                },
                                formatResult: format,
                                formatSelection: format
                            });

                            $(".contentSelect").on("change", function (e) {
                                var items = $(this).closest('.content').find('.items');
                                var enumT = getEnumCode(e.added);
                                var html = '<li data-id="' + e.val + '"><img src="/uploads/'+e.added.img+'_square.jpg' + Math.floor((Math.random() * 10) + 1) + '/" height="40" style="float: left; margin-right: 10px;" />' + e.added.text + '<br/><small>' + getEnumName(e.added) + '</small></li>';
                                var tagid = items.attr('id');
                                $('input[name="enum"]').val(getEnumCode(e.added));
                                $('input[name="item_id"]').val(e.val);
                                $('#slide_item').html(html);
                            });

                        });

                        function format(data) {
                            if (data.children != undefined)
                                return '<div style="with:100%;">' + data.text + '</div>';
                            else
                                return '<div style="with:100%;height: 40px;"><img src="/uploads/'+data.img+'_square.jpg"' + Math.floor((Math.random() * 10) + 1) + '/" height="40" style="float: left; margin-right: 10px;"> ' + data.text + '<br/> <small style="float: left;">' + getEnumName(data) + '</small></div>';
                        }

                        function getEnumName(data) {
                            var enumNumber = data.enumNumber;
                            var enums =  <?php
                                $arr = Config::get('alias');

                                echo json_encode($arr);
                            ?>;

                            var belongsTo = enums[enumNumber];

                            if (belongsTo instanceof Object)
                                var belongsTo = enums[enumNumber][data.enum];


                            return belongsTo;
                        }

                        function getEnumCode(data) {
                            var enumNumber = data.enumNumber;
                            var enums =  <?php
                            $tempArr = [];
                            $arr = Config::get('enums');
                            foreach($arr as $key => $value)
                            if(!is_array($value))
                            $tempArr[$key] = $value;

                            echo json_encode($tempArr);
                           ?>;

                            if (isNumeric(enumNumber))
                                return enumNumber;
                            else {
                                return enums[enumNumber + 's'];
                            }
                        }

                        function isNumeric(num) {
                            return !isNaN(num)
                        }
                    </script>

                    <div class="row no-margin">
                        <div class="col-md-12 clearfix spacer">
                            <div class="form-group">

                            <h4>Slider İçeriği</h4>

                            <div class="row">
                                <div class="col-md-8">
                                <input type="hidden" class="contentSelect" style="width:100%"/>
                                </div>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" name="item_id" @if(count($relation)>0) value="{{{$relation['id']}}}" @endif/>
                            <input type="hidden" name="enum"  @if(count($relation)>0)  value="{{{$relation['enumNumber']}}}" @endif/>
                            <ul id="slide_item">
                                @if(count($relation)>0)
                                <li data-id="{{$relation['id']}}"><img src="{{asset('uploads/' . $relation['img'] . '_square.jpg')}}" height="40" style="float: left; margin-right: 10px;" />{{$relation['title']}}<br/><small>{{\ Config::get('alias')[$relation['enumNumber']]}}</small></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="row no-margin">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <a href="javascript:window.history.back();" class="btn btn-primary btn-flat"><i
                                            class="fa fa-backward"></i> Geri Dön</a>
                                @if(isset($item->id) && $item->id != null)
                                    <button class="btn save episodeBtn btn-success btn-flat"><i
                                                class="fa fa-floppy-o"></i> Güncelle
                                @else
                                    <button class="btn save episodeBtn btn-success btn-flat"><i
                                                class="fa fa-plus"></i> Ekle
                                @endif
                                    </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection