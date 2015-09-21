@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
    {{$title}}
    <a href="{{action('SerialController@getAddSeries', ['type' => $type])}}" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i>@if($type==3) Program Ekle @else Dizi Ekle @endif</a>
    <a href="{{action('SerialController@getAddChannel')}}" class="btn btn-info btn-flat btn-sm"><i class="fa fa-plus"></i> Kanal Ekle </a>
</h2>
<ol class="breadcrumb">
  <li><a href="{{action('DashController@getDash')}}">Genel</a></li>
  <li>Diziler</li>
  <li class="active">{{$title}}</li>
</ol>
</div>
@endsection

@section('content')

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="block-flat">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>İsim</th>
                            <th>Kanal</th>
                            <th>Yayınlanma Saati</th>
                            <th width="150">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($serials as $serial)
                            <tr>
                                <td>{{$serial->title}}</td>
                                <td>{{$serial->channel->title}}</td>
                                <td>{{$serial->date}}</td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action($actionEpisodes, ['id' => $serial->id, 'type' => $serial->type]) }}"  class="btn btn-warning btn-flat btn-xs"><i class="fa fa-list-ul"></i></a>
                                        <a href="{{ action('SerialController@getAddSeries', ['type' => $type , 'edit' => $serial->id,'message' => 0]) }}"  class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('SerialController@getDeleteSeries', ['id' => $serial->id, 'type' => $serial->type]) }}" onclick="return confirm('İçerik silinecek!')" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></a>
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
