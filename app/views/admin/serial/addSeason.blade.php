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
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <div class="icon"><i class="fa fa-times-circle"></i></div>
                        <strong>Hata!</strong>

                        <div>{{ $error }}</div>
                    </div>
                @endif
                <form method="post" enctype="multipart/form-data">
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Sezon Numarası</label>
                                <input type="text" name="number" class="form-control" value="{{{$item->number}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <a href="javascript:window.history.back();" class="btn btn-primary btn-flat"><i
                                            class="fa fa-backward"></i> Geri Dön</a>
                                <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-plus"></i>
                                    @if(isset($item->id) && $item->id != null)
                                        kaydet
                                    @else
                                        ekle
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="content">
                    <div class="col-md-3 clearfix spacer">
                        <h4>Eklenmiş Olan Sezonlar</h4>

                        <div class="block">
                            <div class="content no-padding ">
                                <ul class="items">
                                    @foreach($seasons as $item)
                                        <li class="hold">
                                            {{$item['number']}}.Sezon<span
                                                    class="pull-right value" style="margin-top: 0px"><a href="#" id="{{$item['id']}}"
                                                                                onclick="return false"
                                                                                class="btn btn-danger btn-xs btnSil">
                                                    Kaldır</a></span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
            <script>
                $(document).on('click', '.btnSil', function () {
                    if(confirm('Dikkat! Sezon ve sezonla ilişkili tüm gönderiler kaldırılacak!')) {
                        var items = $(this).closest('.content').find('.items');
                        var listElement = $(this).closest('li');
                        var item_id = $(this).attr('id');
                        $.post("{{action('SerialController@postDeleteSeason')}}", {
                            'item_id': item_id
                        }, function (data) {

                            if (data.status == "success") {
                                $.gritter.add({
                                    title: 'Mesaj',
                                    text: "Sezon başarıyla silindi",
                                    class_name: 'basic'
                                });
                                listElement.slideUp();
                                listElement.remove();
                            }
                            else {
                                $.gritter.add({
                                    title: 'Hata',
                                    text: "Sezon silinirken bir hata oluştu.",
                                    class_name: 'basic'
                                });
                            }
                        });
                    }
                });
            </script>

@endsection