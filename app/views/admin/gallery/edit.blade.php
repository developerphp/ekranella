@extends('admin.layout')

@section('subheader')
<div class="page-head">
<h2>Galeri Düzenleme
</h2>
<ol class="breadcrumb">
  <li><a href="#">Genel</a></li>
  <li><a>Galeri Düzenleme</a></li>

  <li class="active">Galeri Düzenleme</li>
</ol>
</div>
@endsection

@section('content')
<div class="content">
<div class="col-md-12">
    <div class="block-flat">

        <div class="row">
            <div class="col-md-offset-4">
               <div class="gallery-cont">
                    <div class="item">
                      <div class="photo">
                        <div class="img">
                          <img src="{{asset($item['img'])}}" />
                          <div class="over">
                            <div class="func"><a class="image-zoom" href="{{asset($item['img'])}}"><i class="fa fa-search"></i></a></div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="post" enctype="multipart/form-data">

            <div class="row no-margin">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Görsel Metini</label>
                        <textarea class="ckeditor form-control" name="text" >{{{$item['text']}}}</textarea>
            		</div>
                </div>
            </div>

            <div class="row ">
                <div class="col-sm-12">
                    <div class="form-group">
                        <a href="javascript:window.history.back();" class="btn btn-primary btn-flat"><i class="fa fa-backward"></i> Geri Dön</a>
                        <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-plus"></i>
                        @if(isset($item['id']) && $item['id'] != null)
                        kaydet
                        @else
                        ekle
                        @endif
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{ URL::previous() }}" name="ref">
        </form>
    </div>
</div>

@endsection