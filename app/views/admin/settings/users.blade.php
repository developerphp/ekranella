@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>
            {{$title}}
            <a href="{{action('SettingsController@getAddUser')}}" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i> Ekle </a>
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
                            <th>İsim</th>
                            <th>Kullanıcı Adı</th>
                            <th>Email</th>
                            <th>Yetki</th>
                            <th>Oluşturulma Tarihi</th>
                            <th width="150">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            @if($user->username != 'lupos')

                                <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->role}}</td>
                                <td>{{$user->created_at}}</td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action('SettingsController@getAddUser', ['edit' => $user->id]) }}"  class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('SettingsController@getDeleteUser', ['id' => $user->id]) }}" onclick="return confirm('Kullanıcı eğer varsa istatistikleri ile birlikte silinecek!')" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
