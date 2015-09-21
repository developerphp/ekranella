@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>Reklam</h2>
        <ol class="breadcrumb">
            <li><a href="#">Genel</a></li>
            <li class="active">Reklam</li>
        </ol>
    </div>
@endsection

@section('content')
    <style>
        input[type=text], input[type=file] {
            height: 36px;
        }
    </style>

    <div class="content">
        <div class="col-md-12">
            <div class="block-flat">
                @if (isset($error))
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
                                <label class="control-label">Reklam Adı</label>
                                <input type="text" class="form-control" name="ad[name]" value="{{{ $ad->name }}}"/>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Reklam Boyutu</label>
                                <select name="ad[place]" class="select2">
                                    @foreach(['Kare - 240x201', 'Yabancı - Kare', 'Yerli - Kare', 'Dikey - 184x449', 'Üst Banner - 100x1000', 'Alt Banner  - 100x1000'] as $place)
                                        <option value="{{ $place }}"
                                        @if($ad->place == $place) selected @endif >{{ $place }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Aktiflik</label>

                                <div class="clear">
                                    <div class="switch">
                                        <input type="checkbox" name="ad[is_active]" @if($ad->is_active) checked @endif >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Reklam Linki</label>
                                <input type="text" name="ad[link]" class="form-control" value="{{{$ad->url}}}"/>
                                <small>@if($ad->type == 0 || $ad->type == 1)Reklamın linkini güncellemek için dosyayı tekrar seçmeniz gerekmektedir.@endif</small>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8 adFileInput" @if($ad->type ==2) style="display: none;" @endif>
                            <div class="form-group">
                                <label class="control-label">Reklam Dosyası</label>
                                <input type="file" name="adfile" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-8 adCodeInput" @if($ad->type !=2) style="display: none;" @endif>
                            <div class="form-group">
                                <label class="control-label">Reklam Kodu</label>
                                <input id="codeInput" type="text" cols="40" rows="5" style="height: 200px" name="ad[code]"
                                       value="@if($ad->type == 2){{{$ad->embed}}}@endif" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Reklam Türü</label>
                                <select class="select2 adType" name="ad[type]">
                                    <option value="0" @if($ad->type == 0) selected @endif>Resim</option>
                                    <option value="1" @if($ad->type == 1) selected @endif>Flash</option>
                                    <option value="2" @if($ad->type == 2) selected @endif>Kod</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="prevType" value="{{$ad->type}}">
                        <script>
                            $('document').ready(function () {
                                $('.adType').change(function () {
                                    if ($(this).val() == 2) {
                                        var prevType = $('#prevType').val('');
                                        if(prevType == 0 || prevType == 1)
                                            $('#codeInput').val('');
                                        $('.adFileInput').slideUp();
                                        $('.adCodeInput').slideDown();
                                    } else {
                                        $('.adFileInput').slideDown();
                                        $('.adCodeInput').slideUp();
                                    }
                                });
                            });
                        </script>
                    </div>
                    @if(isset($ad->id))
                        <div class="row no-margin">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Önizleme</label>

                                    <div>
                                        {{$ad->embed}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row no-margin">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <a href="{{action('AdController@getIndex') }}"
                                   class="btn btn-primary btn-flat"><i class="fa fa-backward"></i> Geri Dön</a>
                                <button type="submit" class="btn btn-success btn-flat"><i
                                            class="fa fa-plus"></i> {{ isset($ad->id) ? 'Düzenle' : 'Ekle' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection