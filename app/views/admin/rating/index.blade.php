@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
    {{$date}} tarihine ait  {{$title}}
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
                            <th>Sıralama</th>
                            <th>Rating Puanı</th>
                            <th>Sharing Puanı</th>
                            <th>Tip</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ratings as $rating)
                            <tr>
                                <td>{{$rating->title}}</td>
                                <td>{{$rating->order}}</td>
                                <td>{{$rating->rating}}</td>
                                <td>{{$rating->share}}</td>
                                <td>@if($rating->type == 1) TOTAL @else AB @endif</td>
                                <td>{{$rating->date}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
