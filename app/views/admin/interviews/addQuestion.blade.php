@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>
<a  href="javascript:window.history.back();"  class="btn btn-primary btn-flat btn-sm"><i class="fa fa-backward"></i></a>
{{$title}}
</h2>
<ol class="breadcrumb">
  <li><a href="#">Genel</a></li>
  <li><a href="{{action('InterviewsController@getIndex')}}">Röportajlar</a></li>
  <li class="active">{{$title}}</li>
</ol>
</div>
@endsection

@section('content')
<div class="content"><div class="col-md-12">
    <div class="block-flat">
        @if (isset($error) && $error && $error !== 0 )
        <div class="alert alert-danger alert-white rounded">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <div class="icon"><i class="fa fa-times-circle"></i></div>
            <strong>Hata!</strong>

            <div>{{ $error }}</div>
        </div>
        @endif
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" value="{{$interview_id}}" name="interview_id" />
            <div class="row no-margin">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">Soru</label>
                        <textarea class="ckeditor form-control" name="questionText" >{{{$item->questionText}}}</textarea>
            		</div>
                </div>
            </div>


            <div class="row no-margin">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label">Cevap</label>
                        <textarea class="ckeditor form-control" name="answerText" >{{{$item->answerText}}}</textarea>
                    </div>
                </div>
            </div>


            <div class="row no-margin">
                <div class="col-sm-12">
                    <div class="form-group">
                        <a href="javascript:window.history.back();" class="btn btn-primary btn-flat"><i class="fa fa-backward"></i> Geri Dön</a>
                        <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-plus"></i>
                        @if(isset($item->id) && $item->id != null)
                        kaydet
                        @else
                        ekle
                        @endif
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection