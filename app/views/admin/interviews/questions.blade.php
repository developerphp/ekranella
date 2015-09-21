@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
<a href="{{action('InterviewsController@getIndex')}}" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-backward"></i></a>
<a href="{{action('InterviewsController@getAddQuestion', ['interview_id' => $id])}}" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i></a>
{{$title}} - Sorular
</h2>
<ol class="breadcrumb">
  <li><a href="#">Genel</a></li>
  <li><a href="{{action('InterviewsController@getIndex')}}">Röportajlar</a></li>
  <li class="active">{{$title}}</li>
</ol>
</div>
@endsection


@section('content')
<div class="content">
<div class="col-md-12">
    <div class="block-flat">

        <div class="row">
            <div class="col-md-9">
        <div class="row no-margin">
          <div class="box-content" id="inbox">
          	<ul class="orderlist" style="list-style: none" id="arkaplansiralama" >
          	        @forelse($questions as $item)
          	        <li id="orderArray_{{$item['id']}}">
          	        <span>
                        <div style="margin:5%;width: 90%">{{$item['questionText']}} <br/> <br/> {{$item['answerText']}}</div>
                        <div class="btn-group btn-group-justified no-margin">
                            <a href="{{action('InterviewsController@getAddQuestion',['interview_id' => $id,'id'=> $item['id']])}}" class="btn btn-info btn-flat btn-xs"><i class="fa fa-edit"></i> </a>
                            <a href="{{action('InterviewsController@getDeleteQuestion',['interview_id' => $id,'id'=> $item['id']])}}" onclick="return confirm('Soru-Cevap silinecek!')"  class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i> </a>
                        </div>
          	        </span>

          	        </li>
                    @empty
                    <center><h5>Röportaja ait hiç soru bulunamadı</h5></center>
                    @endforelse
            </ul>
          </div>

         </div>
            </div>

        </div>

    </div>
</div>
<script>
    $(document).ready(function(){
        $(function() {
            $("#arkaplansiralama").sortable({ opacity: 0.6, cursor: 'move', update: function() {
                var order = $("#arkaplansiralama").sortable("serialize");

                $.post('{{action('InterviewsController@postOrderQuestions')}}', order, function(response){
                    if(response == "success")
                    $.gritter.add({
                          title: 'Tebrikler',
                          text: "Soru-Cevap sıralama işlemi başarılı",
                          class_name: 'basic'
                    });
                });
            }
            });


        });
    });
</script>
@endsection