@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>{{$featured->title}} - Gündem Detayları </h2>
        <ol class="breadcrumb">
            <li><a href="#">Genel</a></li>
            <li><a href="{{action('FeaturedController@getIndex')}}">Gündem</a></li>
            <li class="active">{{$featured->title}}</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="col-md-12">
            <style>
                .tag_block:nth-child(odd) {
                    clear: both;
                }
            </style>
            <script>
                $(document).ready(function () {
                    $(".contentSelect").select2({
                        minimumInputLength: 3,
                        ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
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
                        var html = '<li data-id="' + e.val + '"><img src="/uploads/'+e.added.img+'_square.jpg"' + Math.floor((Math.random() * 10) + 1) + '/" height="40" style="float: left; margin-right: 10px;" />' + e.added.text + '<span class="pull-right value"><a href="#" id="' + e.val + '" data-enum="' + enumT + '" onclick="return confirm(\'İçerik silinecek!\')" class="btn btn-danger btn-xs btnSil"> Sil</a></span><small>' + getEnumName(e.added) + '</small></li>';
                        var tagid = items.attr('id');
                        $.post("{{action('FeaturedController@postAddItem')}}", {
                            featured_id: "{{$featured->id}}",
                            'tag_id': tagid,
                            'item_id': e.val,
                            'enum': enumT
                        }, function (data) {

                            if (data.status == "success") {
                                console.log('published: ' + data.published);
                                if (data.published == 0) {
                                    $.gritter.add({
                                        title: 'Mesaj',
                                        text: "Eklenen içerik şu an yayında değil. Yayınlanana kadar sitede gözükmeyecektir",
                                        class_name: 'basic'
                                    });
                                }
                                items.prepend(html);

                                $.gritter.add({
                                    title: 'Mesaj',
                                    text: "Gündem'e içerik başarıyla eklendi",
                                    class_name: 'basic'
                                });
                            } else if (data.status == "exists") {
                                $.gritter.add({
                                    title: 'Mesaj',
                                    text: "Bu içerik zaten bu gündem alt başlığı için kaydedilmiş",
                                    class_name: 'basic'
                                });
                            }
                            else {
                                $.gritter.add({
                                    title: 'Hata',
                                    text: "Gündem'e içerik eklenirken bir hata oluştu." + data.error,
                                    class_name: 'basic'
                                });
                            }
                        });
                    });

                    $(document).on('click', '.btnSil', function () {
                        var items = $(this).closest('.content').find('.items');
                        var listElement = $(this).closest('li');
                        var tag_id = items.attr('id');
                        var item_id = $(this).attr('id');
                        var enumNumber = $(this).data('enum');
                        $.post("{{action('FeaturedController@postDeleteItem')}}", {
                            featured_id: "{{$featured->id}}",
                            'tag_id': tag_id,
                            'item_id': item_id,
                            'enum': enumNumber
                        }, function (data) {

                            if (data.status == "success") {
                                $.gritter.add({
                                    title: 'Mesaj',
                                    text: "Gündem'den içerik başarıyla silindi",
                                    class_name: 'basic'
                                });
                                listElement.slideUp();
                                listElement.remove();
                            }
                            else {
                                $.gritter.add({
                                    title: 'Hata',
                                    text: "Gündem içeriği silinirken bir hata oluştu.",
                                    class_name: 'basic'
                                });
                            }
                        });
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
            <?php
            $allTags = $featured->tags()->get()->toArray();
            foreach ($allTags as $tag) {
                if (!isset($featuredTags[$tag['id']]))
                    $featuredTags[$tag['id']] = ['id' => $tag['id'], 'title' => $tag['title'], 'items' => []];
            }

            function getEnum($item)
            {
                if (is_numeric($item['item_enum']))
                    return $item['item_enum'];
                else
                    return \Config::get('enums.' . $item['item_enum' . 's']);
            }
            ?>
            @foreach($featuredTags as $featuredTag)
                <div class="col-md-6 tag_block">
                    <div class="block-flat">
                        <div class="col-md-12">
                            <h3 data-id="{{$featuredTag['id']}}" class="tagTitle">{{$featuredTag['title']}}</h3>
                            <hr style="margin: 0px;"/>
                        </div>
                        <div class="content">
                            <div class="col-md-12 clearfix spacer">
                                <h4>Yeni İçerik Ekle</h4>

                                <div class="row">
                                    <div class="col-md-12">
                                    <input type="hidden" class="contentSelect" style="width:100%"/>
                                </div>
                            </div>

                            <h4>Eklenmiş Olan İçerikler</h4>

                            <div class="block">
                                <div class="content no-padding ">
                                    <ul class="items" id="{{$featuredTag['id']}}">

                                        @foreach($featuredTag['items'] as $item)
                                            <li>
                                                <img src="{{asset('uploads/'. $item['img'] . '_square.jpg')}}" height="40"
                                                     style="float: left; margin-right: 10px;"/> {{$item['title']}}<span
                                                        class="pull-right value"><a href="#" id="{{$item['id']}}"
                                                                                    data-enum="{{getEnum($item)}}"
                                                                                    onclick="return confirm('İçerik silinecek!')"
                                                                                    class="btn btn-danger btn-xs btnSil">
                                                        Sil</a></span>
                                                <small>{{Config::get('alias.'.$item['item_enum'])}}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
        </div>
        @endforeach

    </div>
    </div>
    <script>
        //$('.tagTitle').disableSelection();
        $(document).ready(function () {
            $(document).on('click', '.tagTitle', function () {
                var id = $(this).data('id');
                var tagObject = $(this);
                var tag = tagObject.text();

                var newObject = '<input data-id="' + id + '" type="text" value="' + tag + '" class="form-control tagTitleInput">';
                tagObject.replaceWith(newObject);
                $('input[data-id=' + id + ']').focus();
            });

            $(document).on('blur', '.tagTitleInput', function () {
                var inputObject = $(this);
                var tagName = inputObject.val();
                var id = inputObject.data('id');

                $.post('{{action('FeaturedController@postUpdateTag')}}', {
                    'id': id,
                    'title': tagName
                }, function (response) {
                    if (response.status == "success") {
                        $.gritter.add({
                            title: 'Mesaj',
                            text: "Gündem alt başlığı güncellendi",
                            class_name: 'basic'
                        });
                    } else {
                        $.gritter.add({
                            title: 'Mesaj',
                            text: "Gündem alt başlığı güncellenirken bir hata oluştu",
                            class_name: 'basic'
                        });
                    }
                }, 'json');

                var newObject = '<h3 data-id="' + id + '" class="tagTitle">' + tagName + '</h3>';
                inputObject.replaceWith(newObject);

            });
        });
    </script>
@endsection