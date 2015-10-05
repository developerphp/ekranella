@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li><a href="#">Genel</a></li>
            @if($type == 1)
                <li><a href="{{action('SerialController@getForeignSeries')}}">Yabancı Diziler</a></li>
            @elseif($type == 2)
                <li><a href="{{action('SerialController@getLocalSeries')}}">Yerli Diziler</a></li>
            @else
                <li><a href="{{action('SerialController@getLocalSeries')}}">Programlar</a></li>
            @endif

            <li class="active">{{$title}}</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="col-md-12">
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
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Adı</label>
                                <input type="text" class="form-control title" name="title" value="{{{$item->title}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Türkiyede Yayınlanan Kanal</label><br/>
                                <small>(Eğer dizi türkiyede yayınlanmıyorsa yabancı kanalı seçip formun devamında
                                    bulunan <em>yabancı kanal</em>'ı gözardı edebilirsiniz)
                                </small>
                                <select name="channel_id" class="select2">
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel['id'] }}"
                                        @if(isset($item->channel_id) && $item->channel_id == $channel['id'])
                                                selected @endif >{{ $channel['title'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="control-label">Başlangıç Yılı</label>
                                        <input type="number" min="1900" class="form-control" name="start_year"
                                               value="{{{$item->start_year}}}"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label">Bitiş Yılı</label> @if($item->end_year == 0) <small>(Devam eden dizi için 0 yazın)</small> @endif
                                        <input type="number"  min="0"  class="form-control" name="end_year"
                                               value="{{{$item->end_year}}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group hobaaa">
                                <label class="control-label">Türkiyede Yayınlanma Saati ve Günü</label>
                                @if($item->airing)
                                    <?php  $dates = unserialize($item->airing); $days = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'] ?>
                                    @foreach($dates as $date)
                                        <?php $use = explode(',', $date); ?>
                                        <div class="row dateDiv">
                                            <div class="col-sm-4">
                                                <label class="control-label">Gün</label>
                                                <select name="day[]" class="select2">
                                                    @foreach($days as $day)
                                                        <option value="{{$day}}" @if($day == trim($use[0]))
                                                                selected @endif >{{$day}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label">Saat</label>
                                                <input type="time" class="form-control" name="time[]"
                                                       value="{{trim($use[1])}}"/>
                                            </div>
                                            <button type="button"
                                                    class="btn btn-xs btn-danger btn-flat silGun pull-right"
                                                    style="margin-top: 33px!important;">iptal
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                                <script>
                                    $(document).ready(function () {
                                        var i = 0;
                                        $('.yeniGun').click(function () {
                                            var html = '<div class="col-sm-6"><label class="control-label">Gün</label><select name="day[]" class="select2' + i + '" style="width:333px"><option value="Pazartesi" selected>Pazartesi</option> <option value="Salı" >Salı</option> <option value="Çarşamba" >Çarşamba</option> <option value="Perşembe" >Perşembe</option> <option value="Cuma" >Cuma</option> <option value="Cumartesi" >Cumartesi</option> <option value="Pazar" >Pazar</option> </select> </div> <div class="col-sm-4"> <label class="control-label">Saat</label><input type="time" class="form-control" name="time[]" /> </div>';
                                            $('.hobaaa').append('<div class="row">' + html + '<button type="button" class="btn btn-xs btn-danger btn-flat silGun pull-right" style="margin-top: 33px!important;">iptal</button></div>');
                                            $(".select2" + i).select2();
                                            ++i;
                                        });

                                        $(document).on('click', '.silGun', function () {
                                            $(this).closest('.row').remove();
                                        });
                                    });
                                </script>
                            </div>
                            <button type="button" class="btn btn-xs btn-info btn-flat yeniGun">Yeni Gün / Saat ekle
                            </button>
                        </div>
                    </div>
                    <br/>
                    <br/>

                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Yabancı Kanalın Adı / Günü, Saati</label>
                                <small>Örnek Giriş: CNBC-E / Çarşamba 21:00</small>
                                <input type="text" class="form-control" name="extra" value="{{{$item->extra}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Kısa Bilgisi </label>
                                <textarea class="form-control" name="info">{{{$item->info}}}</textarea>
                            </div>
                        </div>
                    </div>
                    @if($item->cover != "" && is_string($item->cover))
                        <div class="row">
                            <div class="col-md-offset-3">
                                <div class="gallery-cont">
                                    <div class="item">
                                        <div class="photo">
                                            <div class="img">
                                                <img src="{{asset($item->cover)}}"/>

                                                <div class="over">
                                                    <div class="func"><a class="image-zoom"
                                                                         href="{{asset($item->cover)}}"><i
                                                                    class="fa fa-search"></i></a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row no-margin">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Kapak Görseli</label>
                                <input type="file" name="cover" class="form-control"  @if(is_string($item->cover)) value="{{{$item->cover}}}" @endif />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Maske Aktifliği</label>

                                <div class="clear">
                                    <div class="switch">
                                        <input type="checkbox" name="is_masked" @if($item->is_masked ==1) checked @endif >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($item->img != "" && is_string($item->img))
                        <div class="row">
                            <div class="col-md-offset-3">
                                <div class="gallery-cont">
                                    <div class="item">
                                        <div class="photo">
                                            <div class="img">
                                                <img src="{{asset($item->img)}}"/>

                                                <div class="over">
                                                    <div class="func"><a class="image-zoom"
                                                                         href="{{asset($item->img)}}"><i
                                                                    class="fa fa-search"></i></a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Küçük Görseli</label> (Önerilen Boyut 244x207)
                                <input type="file" name="img" class="form-control" @if(is_string($item->img))  value="{{{$item->img}}}"  @endif/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Oyuncular</label>
                                <input class="tags" type="hidden" name="cast" value="{{$item->cast}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Yazarlar</label>
                                <input class="tags" type="hidden" name="writer" value="{{$item->writer}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Yönetmen</label>
                                <input class="tags" type="hidden" name="director" value="{{$item->director}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Yapımcı</label>
                                <input class="tags" type="hidden" name="producer" value="{{$item->producer}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">Müzik</label>
                                <input class="tags" type="hidden" name="music" value="{{$item->music}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Twitter Hesabı</label> <small>twitter hesabını başında @ olmadan giriniz</small>
                                <input type="text" class="form-control twitter" name="twitter"
                                       value="{{{$item->twitter}}}"/>
                            </div>
                        </div>
                    </div>

                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Popüler Listesin de mi?</label>
                                <input type="checkbox" class="form-control" name="is_popular" value="1" @if($item->is_popular == 1) checked @endif/>
                            </div>
                        </div>
                    </div>

                    <div class="row no-margin">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="control-label">{{$as}} Permalink</label>
                                <input type="text" class="form-control permalink" name="permalink"
                                       value="{{{$item->permalink}}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row no-margin">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <a href="javascript:window.history.back();" class="btn btn-primary btn-flat"><i
                                            class="fa fa-backward"></i> Geri Dön</a>
                                <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-plus"></i>
                                    @if(isset($item->id) && $item->id != null)
                                        kaydet
                                    @else
                                        ekle
                                    @endif
                                </button>
                            </div>
                </form>
            </div>
        </div>

@endsection