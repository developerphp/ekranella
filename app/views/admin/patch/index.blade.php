@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>İçerik Aktarma</h2>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="block-flat">
                <table id="datatable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Tip</th>
                        <th>Link</th>
                        <th>İlişkili Dizi</th>
                        <th width="150">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($links as $link)
                        <tr>
                            <td>{{$link->type}}</td>
                            <td>{{$link->link}}</td>
                            <td>@if($link->serial!="" ) {{$link->serial}} @elseif($link->serial_id!=0 && $link->serial_id != null) Dizi ID: {{$link->serial_id}}   @else İlişkili Dizi Yok  @endif</td>
                            <td>
                                <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                @if($link->type == "ozet" && $link->object == "dizi" &&  $link->serial != "" )
                                    <a href="{{ action('SerialController@getAddEpisode', ['type' => 0 , 'serial_id' => $link->serial_id, 'edit' => 0, 'message' => 0, 'title' => $link->serial, 'url' => $link->link, 'number' => $link->episode]) }}"
                                       class="btn btn-success btn-flat btn-sm" target="_blank"><i class="fa fa-plus"></i> Siteye Ekle</a>
                                @elseif($link->type == "bolum-ozeti" && $link->object == "dizi" &&  $link->serial != "" )
                                    <a href="{{ action('SerialController@getAddEpisode', ['type' => 0 , 'serial_id' => $link->serial_id, 'edit' => 0, 'message' => 0, 'title' => $link->serial, 'url' => $link->link, 'number' => $link->episode]) }}"
                                           class="btn btn-success btn-flat btn-sm" target="_blank"><i class="fa fa-plus"></i> Siteye Ekle</a>
                                @elseif($link->type == "foto-galeri" && $link->object == "dizi")
                                        <a href="{{ action('SerialController@getAddSGallery', ['type' => 4 , 'serial_id' => $link->serial_id, 'edit' => 0, 'message' => 0, 'title' => 0, 'url' => $link->link, 'number' => $link->episode]) }}"
                                           class="btn btn-success btn-flat btn-sm" target="_blank"><i class="fa fa-plus"></i>  Siteye Ekle </a>
                                @elseif($link->type == "ozel-haber" && $link->object == "haber")
                                        <a href="{{action('NewsController@getAddNews', ['type' => 1, 'edit' => false, 'messages' => false, 'title' => null, 'inputs' => false, 'url' => $link->link])}}"
                                           class="btn btn-success btn-flat btn-sm" target="_blank"><i class="fa fa-plus"></i> Siteye Ekle</a>
                                @endif


                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            <script>
                $('document').ready(function(){
                    $('.btn-group-justified').find('a').click(function(){
                       $(this).closest('tr').hide();
                    });
                });
            </script>
            </div>
        </div>
@endsection