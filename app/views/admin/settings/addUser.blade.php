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
                    @if($item->pp)
                        <div class="row">
                            <div class="col-md-offset-4">
                                <div class="gallery-cont">
                                    <div class="item">
                                        <div class="photo">
                                            <div class="img">
                                                <img src="{{asset($item->pp)}}"/>

                                                <div class="over">
                                                    <div class="func"><a class="image-zoom" href="{{asset($item->pp)}}"><i
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
                                <label class="control-label">Profil Fotoğrafı</label>
                                <small>Tasarıma uygun olması için yüklenecek fotoğraflar <em>kare</em> olmalıdır.</small>
                                <input type="file" name="pp" class="form-control" value="{{{$item->pp}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Tam Adı</label>
                                <input type="text" class="form-control" name="name" value="{{{$item->name}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Email</label>
                                <input type="email" name="email" class="form-control" value="{{{$item->email}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Adı</label>
                                <input type="text" name="username" class="form-control" value="{{{$item->username}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Şifresi</label>
                                <input type="password" name="password" class="form-control" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Kullanıcı Sınıfı</label>
                                <select name="role" class="select2">
                                    @foreach($roles as $key => $role)
                                        <option value="{{ $key }}" @if($item->role == $key)
                                                selected @endif>{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Özgeçmiş/Biyografi</label>
                                    <textarea class="form-control"
                                              name="bio">@if(isset($item->bio)){{{$item->bio}}}@endif</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Facebook</label>
                                <input type="url" name="facebook" class="form-control"
                                       value="@if(isset($item->social)){{{$item->social['facebook']}}}@endif"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Twitter</label>
                                <input type="url" name="twitter" class="form-control"
                                       value="@if(isset($item->social)){{{$item->social['twitter']}}}@endif"/>
                            </div>
                        </div>
                    </div>
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Instagram</label>
                                    <input type="url" name="instagram" class="form-control"
                                           value="@if(isset($item->social)){{{$item->social['instagram']}}}@endif"/>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Google+</label>
                                    <input type="url" name="googleplus" class="form-control"
                                           value="@if(isset($item->social)){{{$item->social['googleplus']}}}@endif"/>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Tumblr</label>
                                    <input type="url" name="tumblr" class="form-control"
                                           value="@if(isset($item->social)){{{$item->social['tumblr']}}}@endif"/>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Blog</label>
                                    <input type="url" name="blog" class="form-control"
                                           value="@if(isset($item->social)){{{$item->social['blog']}}}@endif"/>
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
            </div>
            </form>
        </div>
    </div>
@endsection