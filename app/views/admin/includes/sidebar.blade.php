
<div class="cl-sidebar">
    <div class="cl-toggle"><i class="fa fa-bars"></i></div>
    <div class="cl-navblock">
       <div class="menu-space">
         <div class="content">
           <div class="side-user">
            <br/>
             <div class="info">
               <a href="#">{{\Auth::user()->name}}</a>
               <span>{{\admin\RoleController::getRoleAlias()}}</span>
             </div>
           </div>
           <ul class="cl-vnavigation">
             <li><a href="{{action('DashController@getIndex')}}"><i class="fa fa-home"></i><span>Genel</span></a></li>
             <li><a href="#"><i class="fa fa-desktop"></i><span>Diziler / Programlar</span></a>
               <ul class="sub-menu">
                 <li><a href="{{action('SerialController@getForeignSeries')}}">Yabancı Diziler</a></li>
                 <li><a href="{{action('SerialController@getLocalSeries')}}">Yerli Diziler</a></li>
                 <li><a href="{{action('SerialController@getPrograms')}}">Programlar</a></li>
                 <li><a href="{{action('SerialController@getChannels')}}">Kanallar</a></li>
               </ul>
             </li>
             <li><a href="#"><i class="fa fa-list-alt"></i><span>Haberler</span></a>
               <ul class="sub-menu">
                 <li><a href="{{action('NewsController@getIndex')}}">Haberler</a></li>
                 <li><a href="{{action('NewsController@getSpecialNews')}}">Özel</a></li>
                 <li><a href="{{action('NewsController@getPortrait')}}">Foto Haber</a></li>
               </ul>
             </li>

             <li><a href="{{action('InterviewsController@getIndex')}}"><i class="fa fa-microphone"></i><span>Röportajlar</span></a></li>

             <li><a href="{{action('ArticleController@getIndex')}}"><i class="fa fa-pencil-square-o"></i><span>Köşe Yazıları</span></a></li>

             <li><a href="{{action('FeaturedController@getIndex')}}"><i class="fa fa-bookmark-o"></i><span>Gündemler</span></a></li>

             <li><a href="{{action('SliderController@getIndex')}}"><i class="fa fa-th-large"></i><span>Manşetler</span></a></li>
             <li><a href="{{action('RatingController@getIndex')}}"><i class="fa fa-signal"></i><span>Rating</span></a></li>

             @if(admin\RoleController::isAdmin())
               <li><a href="{{action('AdController@getIndex')}}"><i class="fa fa-ticket"></i><span>Reklam</span></a></li>
               <li><a href="{{action('PatchController@getIndex')}}"><i class="fa fa-thumbs-o-up"></i><span>Eski Site</span></a></li>
               <li><a href="{{action('VisitorController@getIndex')}}"><i class="fa fa-comments"></i><span>Kayıt Listesi</span></a></li>
               <li><a href="#"><i class="fa fa-cogs"></i><span>Ayarlar</span></a>
                 <ul class="sub-menu">
                   <li><a href="{{action('SettingsController@getGeneral')}}"><i class="fa fa-globe"></i><span>Genel</span></a></li>
                   <li><a href="{{action('SettingsController@getUsers')}}"><i class="fa fa-users"></i><span>Kullanıcılar</span></a></li>
                 </ul>
               </li>
               @endif

               <!--<li><a href="#"><i class="fa fa-folder"></i><span>Galeri</span></a>
                 <ul class="sub-menu">
                   <li><a href="#">General</a></li>
                   <li><a href="#"><span class="label label-primary pull-right">New</span>Data Tables</a></li>
                 </ul>
               </li>
               <li><a href="#"><i class="fa fa-map-marker fa-file"></i><span>Foto Haberler</span></a>
                 <ul class="sub-menu">
                   <li><a href="maps.html">Google Maps</a></li>
                   <li><a href="vector-maps.html"><span class="label label-primary pull-right">New</span>Vector Maps</a></li>
                 </ul>
               </li>-->
           </ul>
         </div>
       </div>
    </div>
</div>

<script>
$(document).ready(function(){

if($("a[href='{{Request::url()}}']").length > 0)
$("a[href='{{Request::url()}}']").parents('li').addClass('active');

});


</script>