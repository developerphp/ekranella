@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>Reklam <a href="{{ action('AdController@getCreate') }}" class="btn btn-success btn-flat btn-sm"><i
                        class="fa fa-plus"></i> Reklam Ekle</a></h2>
        <ol class="breadcrumb">
            <li><a href="#">Genel</a></li>
            <li class="active">Reklam</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="block-flat">
                <table id="datatable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Reklam Adı</th>
                        <th>Reklam Boyutu</th>
                        <th>Aktiflik</th>
                        <th>Görüntülenme</th>
                        <th>Tıklanma</th>
                        <th>Tıklanma/Görüntülenme (CTR)</th>
                        <th width="150">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ads as $ad)
                        <tr>
                            <td>{{{ $ad->name }}}</td>
                            <td>{{{ $ad->place }}}</td>
                            <td>{{{ $ad->is_active ? "Aktif" : "Aktif Değil" }}}</td>
                            <td>{{{ $ad->view }}}</td>
                            <td>{{{ $ad->click }}}</td>
                            <td><?php
                                if (!$ad->click || !$ad->view) {
                                    echo '0%';
                                } else {
                                    echo round(($ad->click/$ad->view)*100, 3, PHP_ROUND_HALF_UP).'%';
                                }  ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                    <a href="{{ action('AdController@getEdit', ['id' => $ad->id]) }}"
                                       class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                    <a href="{{ action('AdController@getDelete', ['id' => $ad->id]) }}"
                                       onclick="return confirm('Reklam silinecek!')"
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
@stop