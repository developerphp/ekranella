@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>
            {{$title}}
            <a href="{{action('SliderController@getAddSlider')}}" class="btn btn-success btn-flat btn-sm"><i
                        class="fa fa-plus"></i> Manşet Ekle </a>

            <a href="{{action('SliderController@getOrder')}}" class="btn btn-info btn-flat btn-sm"><i
                        class="fa icon-sort-by-order"></i> Manşetleri Sırala </a>
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
                            <th>Sıra</th>
                            <th>Görsel</th>
                            <th>Eklenme Tarihi</th>
                            <th width="150">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sliderList as $slider)
                            <tr>
                                <td>{{$slider->order}}</td>
                                <td style=" width:600px">
                                    @if($slider->img != "")
                                        <div class="gallery-cont">
                                            <div class="item" style="width: 100%;">
                                                <div class="photo">
                                                    <div class="img">
                                                        <img src="{{asset('uploads/'.$slider->img.'_slideThumb.jpg')}}"/>

                                                        <div class="over">
                                                            <div class="func"><a class="image-zoom"
                                                                                 href="{{asset('uploads/'.$slider->img.'_slideThumb.jpg')}}"><i
                                                                            class="fa fa-search"></i></a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>{{$slider->created_at}}</td>
                                <td>
                                    <div class="btn-group btn-group-justified" style="padding-right: 8px;">
                                        <a href="{{ action('SliderController@getAddSlider', ['edit' => $slider->id,'message' => 0]) }}"
                                           class="btn btn-primary btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('SliderController@getDeleteSlider', ['id' => $slider->id]) }}"
                                           onclick="return confirm('İçerik silinecek!')"
                                           class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="block-flat">
                    <form id="content-form" method="post" action="{{action('SliderController@postUpdateRatingSlider')}}" enctype="multipart/form-data">
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Reyting Manşet Başlığı</label>
                                    <input type="text" name="title" class="form-control title"
                                           value="{{{$ratingSlider['title']}}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Reyting Manşet Metni</label>
                                    <textarea class="ckeditor form-control" name="text" style="height: 200px">{{{$ratingSlider['text']}}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row no-margin">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Reyting Manşet Görseli</label>
                                    <input type="file" name="img" class="form-control" value="{{{$ratingSlider['img']}}}"/>
                                </div>
                                <img height="300" src="{{$ratingSlider['img']}}" />
                                <input type="hidden" name="imghide" value="{{$ratingSlider['img']}}" />
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-12">
                                <div class="form-group">
                                <button class="btn save episodeBtn btn-success btn-flat"><i class="fa fa-plus"></i> Güncelle </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
