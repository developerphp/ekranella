@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>
            {{$title}}

            <a href="{{ action('SerialController@getAddEpisode', ['type' => $type , 'serial_id' => $serial_id, 'edit' => 0, 'message' => 0, 'title' => $title]) }}"
               class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus fa-3x"></i> Yeni Bölüm Özeti Ekle</a>
            <a href="{{ action('SerialController@getAddSpecial', ['type' => $type , 'serial_id' => $serial_id, 'edit' => 0, 'message' => 0, 'title' => $title]) }}"
               class="btn btn-warning btn-flat btn-sm"><i class="fa fa-plus fa-3x"></i> Yeni Özel Yazı Ekle</a>
            <a href="{{ action('SerialController@getAddTrailer', ['type' => $type , 'serial_id' => $serial_id, 'edit' => 0, 'message' => 0, 'title' => $title]) }}"
               class="btn btn-danger btn-flat btn-sm"><i class="fa fa-plus fa-3x"></i> Yeni Fragman Ekle</a>
            <a href="{{ action('SerialController@getAddSGallery', ['type' => $type , 'serial_id' => $serial_id, 'edit' => 0, 'message' => 0, 'title' => $title]) }}"
               class="btn btn-primary btn-flat btn-sm"><i class="fa fa-plus fa-3x"></i> Yeni Galeri Ekle</a>
            <a href="{{ action('SerialController@getAddSeason', ['type' => $type , 'serial_id' => $serial_id, 'edit' => 0]) }}"
               class="btn btn-info btn-flat btn-sm"><i class="fa fa-video-camera fa-3x"> Yeni Sezon Ekle</i></a>
        </h2>
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


        <div class="tab-container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#summary" data-tabname="episodes" data-toggle="tab"><h4>Bölüm Özetleri</h4></a></li>
                <li><a href="#specials" data-tabname="episodes" data-toggle="tab"><h4>Özel Yazılar</h4></a></li>
                <li><a href="#trailer" data-tabname="episodes" data-toggle="tab"><h4>Fragmanlar</h4></a></li>
                <li><a href="#sgallery" data-tabname="episodes" data-toggle="tab"><h4>Galeriler</h4></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="summary">
                    <!-- Episodes -->
                    <table id="datatable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>İsim</th>
                            <th>Bölüm</th>
                            <th>Sezon</th>
                            <th>Kanal</th>
                            <th>Yayın Durumu</th>
                            <th width="150">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($episodes as $episode)
                            <tr>
                                <td>{{$episode['title']}}</td>
                                <td>{{$episode['number']}}</td>
                                <td>{{$episode['season']}}</td>
                                <td>{{$channel}}</td>
                                <td>
                                    @if($episode['published'])
                                        İçerik yayında
                                    @else
                                        İçerik yayında değil
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action('SerialController@getPublishToggle', ['id' => $episode['id'],'serial_id' => $serial_id,'type' => $type ])}}"
                                           onclick="return confirm('İçeriğin yayınlanma durumunu değiştirmek istediğinize emin misiniz?')"
                                           class="btn btn-flat btn-xs @if($episode['published']) btn-warning @endif" title="Yayınla"><i
                                                    class="fa fa-check-circle-o"></i></a>
                                        <a href="{{ action('SerialController@getAddEpisode', ['type' => $type , 'serial_id' => $serial_id, 'edit' => $episode['id'], 'message' => 0, 'title' => $title]) }}"
                                           class="btn btn-primary btn-flat btn-xs" title="Düzenle"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action($actionGallery, ['id' => $episode['id'], 'type' => $type]) }}"
                                           class="btn btn-success btn-flat btn-xs" title="Galeri"><i class="fa fa-picture-o"></i></a>
                                        <a href="{{ action('SerialController@getDeleteEpisode', ['id' => $episode['id'],'serial_id' => $serial_id,'type' => $type ])}} "
                                           onclick="return confirm('İçerik silinecek!')"
                                           class="btn btn-danger btn-flat btn-xs" title="Sil"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="specials">
                    <!-- Specials -->
                    <table id="datatable" class="table table-bordered datatable">
                        <thead>
                        <tr>
                            <th>İsim</th>
                            <th>Bölüm</th>
                            <th>Sezon</th>
                            <th>Kanal</th>
                            <th>Yayın Durumu</th>
                            <th width="150">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($specials as $special)
                            <tr>
                                <td>{{$special['title']}}</td>
                                <td>@if($special['number'] != 0){{$special['number']}}@else Belirlenmemiş @endif</td>
                                <td>{{$special['season']}}</td>
                                <td>{{$channel}}</td>
                                <td>
                                    @if($special['published'])
                                        İçerik yayında
                                    @else
                                        İçerik yayında değil
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action('SerialController@getPublishToggle', ['id' => $special['id'],'serial_id' => $serial_id,'type' => $type ])}}"
                                           onclick="return confirm('İçeriğin yayınlanma durumunu değiştirmek istediğinize emin misiniz?')"
                                           class="btn btn-flat btn-xs @if($special['published']) btn-warning @endif" title="Yayınla"><i
                                                    class="fa fa-check-circle-o"></i></a>
                                        <a href="{{ action('SerialController@getAddSpecial', ['type' => $type , 'serial_id' => $serial_id, 'edit' => $special['id'], 'message' => 0, 'title' => $title]) }}"
                                           class="btn btn-primary btn-flat btn-xs" title="Düzenle"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('SerialController@getDeleteSpecial', ['id' => $special['id'],'serial_id' => $serial_id,'type' => $type ])}} "
                                           onclick="return confirm('İçerik silinecek!')"
                                           class="btn btn-danger btn-flat btn-xs" title="Sil"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="trailer">
                    <!-- Trailer -->
                    <table id="datatable" class="table table-bordered datatable">
                        <thead>
                        <tr>
                            <th>İsim</th>
                            <th>Bölüm</th>
                            <th>Sezon</th>
                            <th>Kanal</th>
                            <th>Yayın Durumu</th>
                            <th width="150">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trailers as $trailer)
                            <tr>
                                <td>{{$trailer['title']}}</td>
                                <td>{{$trailer['number']}}</td>
                                <td>{{$trailer['season']}}</td>
                                <td>{{$channel}}</td>
                                <td>
                                    @if($trailer['published'])
                                        İçerik yayında
                                    @else
                                        İçerik yayında değil
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action('SerialController@getPublishToggle', ['id' => $trailer['id'],'serial_id' => $serial_id,'type' => $type ])}}"
                                           onclick="return confirm('İçeriğin yayınlanma durumunu değiştirmek istediğinize emin misiniz?')"
                                           class="btn btn-flat btn-xs @if($trailer['published']) btn-warning @endif" title="Yayınla"><i
                                                    class="fa fa-check-circle-o"></i></a>
                                        <a href="{{ action('SerialController@getAddTrailer', ['type' => $type , 'serial_id' => $serial_id, 'edit' => $trailer['id'], 'message' => 0, 'title' => $title]) }}"
                                           class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('SerialController@getDeleteTrailer', ['id' => $trailer['id'],'serial_id' => $serial_id,'type' => $type ])}} "
                                           onclick="return confirm('İçerik silinecek!')"
                                           class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="sgallery">
                    <!-- Trailer -->
                    <table id="datatable" class="table table-bordered datatable">
                        <thead>
                        <tr>
                            <th>İsim</th>
                            <th>Kanal</th>
                            <th>Yayın Durumu</th>
                            <th width="150">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sgalleries as $sgallery)
                            <tr>
                                <td>{{$sgallery['title']}}</td>
                                <td>{{$channel}}</td>
                                <td>
                                    @if($sgallery['published'])
                                        İçerik yayında
                                    @else
                                        İçerik yayında değil
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action('SerialController@getPublishToggle', ['id' => $sgallery['id'],'serial_id' => $serial_id,'type' => $type ])}}"
                                           onclick="return confirm('İçeriğin yayınlanma durumunu değiştirmek istediğinize emin misiniz?')"
                                           class="btn btn-flat btn-xs @if($sgallery['published']) btn-warning @endif" title="Yayınla"><i
                                                    class="fa fa-check-circle-o"></i></a>
                                        <a href="{{ action('SerialController@getAddSGallery', ['type' => $type , 'serial_id' => $serial_id, 'edit' => $sgallery['id'], 'message' => 0, 'title' => $title]) }}"
                                           class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('SerialController@getSGalleryGallery', ['id' => $sgallery['id'], 'type' => $type]) }}"
                                           class="btn btn-success btn-flat btn-xs" title="Galeri Öğeleri"><i class="fa fa-picture-o"></i></a>
                                        <a href="{{ action('SerialController@getDeleteSGallery', ['id' => $sgallery['id'],'serial_id' => $serial_id,'type' => $type ])}} "
                                           onclick="return confirm('İçerik silinecek!')"
                                           class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
