@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>
            <a href="javascript:history.go(-1)" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-backward"></i></a>
            Manşetler
        </h2>
        <ol class="breadcrumb">
            <li><a href="#">Genel</a></li>
            <li><a href="javascript:history.go(-1)">Manşetler</a></li>
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
                                    @forelse($slides as $item)
                                        <li id="orderArray_{{$item['id']}}">
          	        <span>
                           <img src="{{asset('/uploads/'.$item['img'].'_slideThumb.jpg')}}" width="50%"/>
                           <div style="float: right; width: 40%">{{str_limit($item['title'], 300)}}</div>

          	        </span>

                                        </li>
                                    @empty
                                        <center><h5>Kayıtlı manşet bulunamadı</h5></center>
                                    @endforelse
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-5">
                        <script>
                            $(document).ready(function(){

                                $(function() {
                                    $("#arkaplansiralama").sortable({ opacity: 0.6, cursor: 'move', update: function() {
                                        var order = $("#arkaplansiralama").sortable("serialize");

                                        $.post('{{action('SliderController@postOrderItem')}}', order, function(response){
                                            if(response == "success")
                                                $.gritter.add({
                                                    title: 'Tebrikler',
                                                    text: "Manşet sıralama işlemi başarılı",
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