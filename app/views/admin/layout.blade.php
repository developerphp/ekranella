<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{asset('admin/images/favicon.png')}}">

    <title>Ekranella Back Office</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>


    <!-- Bootstrap core CSS -->
    <link href="{{asset('admin/js/bootstrap/dist/css/bootstrap.css')}}" rel="stylesheet" />
	<link rel="stylesheet" href="{{asset('admin/fonts/font-awesome-4/css/font-awesome.min.css')}}">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')}}"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/cropper.css')}}" />

    <link rel="stylesheet" type="text/css" href="{{asset('admin/js/jquery.gritter/css/jquery.gritter.css')}}" />
    <script type="text/javascript" src="{{asset('admin/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/jquery.maskedinput/jquery.maskedinput.js')}}" ></script>
    <link rel="stylesheet" type="text/css" href="{{asset('admin/js/jquery.nanoscroller/nanoscroller.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/js/jquery.easypiechart/jquery.easy-pie-chart.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/js/bootstrap.switch/bootstrap-switch.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('admin/js/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('admin/js/jquery.select2/select2.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('admin/js/bootstrap.slider/css/slider.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('admin/js/intro.js/introjs.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('admin/js/jquery.datatables/bootstrap-adapter/css/datatables.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('admin/js/dropzone/css/dropzone.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/js/jquery.magnific-popup/dist/magnific-popup.css')}}" />

  <!-- Custom styles for this template -->
  <link href="{{asset('admin/css/style.css')}}" rel="stylesheet" />

  <script type="text/javascript" src="{{asset('admin/js/jquery.gritter/js/jquery.gritter.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.nanoscroller/jquery.nanoscroller.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/behaviour/general.js')}}"></script>
  <script src="{{asset('admin/js/jquery.ui/jquery-ui.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.sparkline/jquery.sparkline.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.easypiechart/jquery.easy-pie-chart.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.nestable/jquery.nestable.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/bootstrap.switch/bootstrap-switch.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
  <script src="{{asset('admin/js/jquery.select2/select2.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('admin/js/skycons/skycons.js')}}" type="text/javascript"></script>
  <script src="{{asset('admin/js/bootstrap.slider/js/bootstrap-slider.js')}}" type="text/javascript"></script>
  <script src="{{asset('admin/js/intro.js/intro.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.datatables/jquery.datatables.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.datatables/bootstrap-adapter/js/datatables.js')}}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
  <script type="text/javascript" src="{{asset('admin/js/tinymce/tinymce.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/cropper.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/tinymce/jquery.tinymce.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/dropzone/dropzone.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>


      <!-- Bootstrap core JavaScript
      ================================================== -->
      <!-- Placed at the end of the document so the pages load faster -->
      <script>
        $(document).ready(function(){
          //initialize the javascript
          App.init();
          App.dataTables();
          App.textEditor();
          //App.dashBoard();

            //introJs().setOption('showBullets', false).start();

        });
      </script>

  <script src="{{asset('admin/js/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.flot/jquery.flot.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.flot/jquery.flot.pie.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.flot/jquery.flot.resize.js')}}"></script>
  <script type="text/javascript" src="{{asset('admin/js/jquery.flot/jquery.flot.labels.js')}}"></script>
</head>
  <body>
    <div id="head-nav" class="navbar navbar-default navbar-fixed-top">
     @include('admin.includes.menu')


    </div>

    <div id="cl-wrapper">
    
      @include('admin.includes.sidebar')

      <div class="container-fluid" id="pcont">
          @yield('subheader')
        <div class="cl-mcont">
          @yield('content')
        </div>
      </div> 
      
    </div>


@if(null != Session::get('alert'))
   <script>
    $(document).ready(function(){
        $.gritter.add({
              title: 'Mesaj',
              text: "{{Session::get('alert')}}",
              class_name: 'basic'
        });
    });

    </script>
@endif


  </body>
</html>


