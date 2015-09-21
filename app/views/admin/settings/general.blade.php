@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>{{$as . ' ' . $title}}</h2>
        <ol class="breadcrumb">
            <li><a href="#">Genel</a></li>
            <li><a href="{{action('SettingsController@getUsers')}}">{{$as}} Düzenleme</a></li>
            <li class="active">{{$as . ' ' . $title}}</li>
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
                                <label class="control-label">Facebook</label>
                                <input type="text" name="facebook" class="form-control"
                                       value="{{{$item->social['facebook']}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Twitter</label>
                                <input type="text" name="twitter" class="form-control"
                                       value="{{{$item->social['twitter']}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Instagram</label>
                                <input type="text" name="instagram" class="form-control"
                                       value="{{{$item->social['instagram']}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Youtube</label>
                                <input type="text" name="youtube" class="form-control"
                                       value="{{{$item->social['youtube']}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Pinterest</label>
                                <input type="text" name="pinterest" class="form-control"
                                       value="{{{$item->social['pinterest']}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Tumblr</label>
                                <input type="text" name="tumblr" class="form-control"
                                       value="{{{$item->social['tumblr']}}}"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Bülten kayıt kutusunu aktifleştir </label> <br>

                                <div class="switch">
                                    <input type="checkbox" name="newsletterbox" @if($item->newsletterbox)
                                           checked="checked" @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Manşet Reyting</label> <br>

                                <div class="switch">
                                    <input type="checkbox"
                                           name="rating_slide" @if($item->rating_slide)
                                           checked="checked" @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Anasayfa Başlığı</label>
                                <input type="text" name="home_title" class="form-control"
                                       value="{{{$item->home_title}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Anasayfa Açıklama</label>
                                <input type="text" name="home_description" class="form-control"
                                       value="{{{$item->home_description}}}"/>
                            </div>
                        </div>
                    </div>

                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Anasayfa Etiketleri</label>
                                <input type="text" name="home_tags" class="form-control"
                                       value="{{{$item->home_tags}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <a href="javascript:window.history.back();" class="btn btn-primary btn-flat"><i
                                            class="fa fa-backward"></i> Geri Dön</a>

                                <button type="submit" class="btn btn-success btn-flat"><i
                                            class="fa fa-floppy-o"></i> Kaydet
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection