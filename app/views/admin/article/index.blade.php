@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
    {{$title}}
    <a href="{{action('ArticleController@getAddArticle')}}" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i> Ekle </a>
</h2>
<ol class="breadcrumb">
  <li><a href="{{action('DashController@getDash')}}">Genel</a></li>
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
                    @foreach($articles as $article)
                        <tr>
                            <td>{{$article->title}}</td>
                            <td>{{$article->created_at}}</td>
                            <td>{{$article->user->name}}@if(!$article->is_author) (*{{$article->guest_author}}) @endif</td>
                            <td>
                                @if($article->published)
                                    İçerik yayında
                                @else
                                    İçerik yayında değil
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                    <a href="{{ action('ArticleController@getSelectTodays', ['id' => $article->id])}}"
                                       @if(!$article->todays) onclick="return confirm('Bu yazıyı Günün Köşe Yazısı olarak belirlemek istediğinize emin misiniz?')"
                                       @else onclick="return confirm('Bu yazı artık günün köşe yazısı olmayacak. Devam etmek istiyor musunuz?')" @endif
                                       class="btn btn-flat btn-xs @if($article->todays) btn-success @endif" title="Günün Köşe Yazısı olarak belirle"><i
                                                class="fa fa-hand-o-up"></i></a>
                                    <a href="{{ action('ArticleController@getPublishToggle', ['id' => $article->id])}}"
                                       onclick="return confirm('İçeriğin yayınlanma durumunu değiştirmek istediğinize emin misiniz?')"
                                       class="btn btn-flat btn-xs @if($article->published) btn-warning @endif" title="Yayınla"><i
                                                class="fa fa-check-circle-o"></i></a>
                                    <a href="{{ action('ArticleController@getAddArticle', ['edit' => $article->id,'message' => 0]) }}"  class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                    <a href="{{ action('ArticleController@getDeleteArticle', ['id' => $article->id]) }}" onclick="return confirm('İçerik silinecek!')" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></a>
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
