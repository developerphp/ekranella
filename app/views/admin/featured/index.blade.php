@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
    {{$title}}
    <a href="{{action('FeaturedController@getAddFeatured')}}" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i> Gündem Ekle </a>
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
                            <th>Alt Başlıklar</th>
                            <th>Yayın Durumu</th>
                            <th width="150">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($featuredList as $featured)
                            <tr>
                                <td>{{$featured->title}}</td>
                                <td>{{$featured->created_at}}</td>
                                <td>{{$featured->user->name}}</td>
                                <?php $tags = []; foreach($featured->tags()->get()->toArray() as $tag){ $tags[] = $tag['title'];}  $tags = implode(', ',$tags); ?>
                                <td>{{$tags}}</td>
                                <?php unset($tags); ?>
                                <td>
                                    @if($featured->published)
                                        İçerik yayında
                                    @else
                                        İçerik yayında değil
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action('FeaturedController@getPublishToggle', ['id' => $featured->id])}}"
                                           onclick="return confirm('İçeriğin yayınlanma durumunu değiştirmek istediğinize emin misiniz?')"
                                           class="btn btn-flat btn-xs @if($featured->published) btn-warning @endif" title="Yayınla"><i
                                                    class="fa fa-check-circle-o"></i></a>
                                        <a href="{{ action('FeaturedController@getFeaturedDetails', ['id' => $featured->id]) }}" class="btn btn-success btn-flat btn-xs"><i class="fa fa-list-ul"></i></a>
                                        <a href="{{ action('FeaturedController@getAddFeatured', ['edit' => $featured->id,'message' => 0]) }}"  class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('FeaturedController@getDeleteFeatured', ['id' => $featured->id]) }}" onclick="return confirm('İçerik silinecek!')" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></a>
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
