@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
<a href="javascript:history.go(-1)" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-backward"></i></a>
{{$title}} - Galeri
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

  <li class="active">{{$title}} Galerisi</li>
</ol>
</div>
@endsection

@section('content')
<div class="content">
<div class="col-md-12">
    <div class="block-flat">

        <div class="row">
            <div class="col-md-7">
        <div class="row no-margin">
          <div class="box-content" id="inbox">
          	<ul class="orderlist" style="list-style: none" id="arkaplansiralama" >
          	        @forelse($gallery as $item)
          	        <li id="orderArray_{{$item['id']}}">
          	        <span>
                           <img src="{{asset($item['img'])}}" width="50%"/>
                           <div style="float: right; width: 40%">{{str_limit($item['text'], 300)}}</div>
                            <div class="btn-group btn-group-justified no-margin">
                            <a href="{{action('GalleryController@getEdit',['id'=> $item['id']])}}" class="btn btn-info btn-flat btn-xs"><i class="fa fa-edit"></i> </a>
                            <a href="{{action('GalleryController@getDelete',['id'=> $item['id'], 'news' => $id, 'enum' => 1])}}" onclick="return confirm('Görsel silinecek!')"  class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i> </a>
                            </div>

          	        </span>

          	        </li>
                    @empty
                    <center><h5>Galeride hiç görsel bulunamadı</h5></center>
                    @endforelse
            </ul>
          </div>

         </div>
            </div>

            <div class="col-md-5">
                <div class="cl-mcont">
                      <form action="{{action('SerialController@postUpload')}}"
                      class="dropzone"
                      id="my-awesome-dropzone">
                          <div class="fallback">
                            <input name="file" type="file" multiple />
                          </div>
                      </form>

                </div>
                <script>
                $(document).ready(function(){
                    setTimeout(function(){
                     var myAwesomeDropzone = Dropzone.forElement("#my-awesome-dropzone");
                         myAwesomeDropzone.on("success", function(progress) {
                                var res = JSON.parse(progress.xhr.response);
                                if(res.status == "success")
                                saveItem(res.filename);
                            });

                    },3000);


                function saveItem(img){
                    $.post('{{action('GalleryController@postAddItem')}}',{'img':img, 'item':'{{$id}}' , 'enum':'{{Config::get('enums.episodes')}}' },function(response){
                        if(response.status == "success")
                        $.gritter.add({
                              title: 'Tebrikler',
                              text: "Görsel başarıyla yüklendi. Düzenlemek için sayfayı yenileyebilirsiniz.",
                              class_name: 'basic'
                        });
                    },'json');
                }



                $(function() {
                    $("#arkaplansiralama").sortable({ opacity: 0.6, cursor: 'move', update: function() {
                        var order = $("#arkaplansiralama").sortable("serialize");

                        $.post('{{action('GalleryController@postOrderItem')}}', order, function(response){
                            if(response == "success")
                            $.gritter.add({
                                  title: 'Tebrikler',
                                  text: "Galeri sıralama işlemi başarılı",
                                  class_name: 'basic'
                            });
                        });
                    }
                    });


                });


                });


                </script>
            </div>
        </div>

    </div>
</div>

@endsection