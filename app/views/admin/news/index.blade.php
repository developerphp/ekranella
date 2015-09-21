@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
    {{$title}}
    <a href="{{action('NewsController@getAddNews', ['type' => $type])}}" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i> Ekle </a>
</h2>
<ol class="breadcrumb">
  <li><a href="{{action('DashController@getDash')}}">Genel</a></li>
  <li>Haberler</li>
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
                            <th>Başlık</th>
                            <th>Eklenme Tarihi</th>
                            <th>Yazar</th>
                            <th>Yayın Durumu</th>
                            <th width="150">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($news as $news)
                            <tr>
                                <td>{{$news->title}}</td>
                                <td>{{$news->created_at}}</td>
                                <td>{{$news->user->name}}@if(!$news->is_author) (*{{$news->guest_author}}) @endif</td>
                                <td>
                                    @if($news->published)
                                        İçerik yayında
                                    @else
                                        İçerik yayında değil
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action('NewsController@getPublishToggle', ['id' => $news->id, 'type' => $type ])}}"
                                           onclick="return confirm('İçeriğin yayınlanma durumunu değiştirmek istediğinize emin misiniz?')"
                                           class="btn btn-flat btn-xs @if($news->published) btn-warning @endif" title="Yayınla"><i
                                                    class="fa fa-check-circle-o"></i></a>
                                        <a href="{{ action($actionNews, ['id' => $news->id, 'type' => $news->type]) }}" class="btn btn-success btn-flat btn-xs"><i class="fa fa-picture-o"></i></a>
                                        <a href="{{ action('NewsController@getAddNews', ['type' => $type , 'edit' => $news->id,'message' => 0]) }}"  class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('NewsController@getDeleteNews', ['id' => $news->id, 'type' => $news->type]) }}" onclick="return confirm('İçerik silinecek!')" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <small>(*) Yazarın ismi sitede görünmüyor veya yazıyı sistemde olmayan bir kullanıcıya atamış</small>
</div>

@endsection
