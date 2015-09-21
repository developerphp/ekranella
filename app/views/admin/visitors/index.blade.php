@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
    {{$title}}
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
                            <th>Ad - Soyad</th>
                            <th>Email</th>
                            <th>Kaynak</th>
                            <th>Kayıt Tarihi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                            <tr>
                                <td>@if($visitor->fbID != null && $visitor->fbID != 0) {{$visitor->name.' '.$visitor->surname}} @else Newsletter @endif</td>
                                <td>{{$visitor->email}}</td>
                                <td>@if($visitor->fbID != null && $visitor->fbID != 0) Facebook @else Newsletter @endif</td>
                                <td>{{$visitor->created_at}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
