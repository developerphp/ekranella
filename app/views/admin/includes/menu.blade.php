<div class="container-fluid">

<!-- Left Menu Side -->
<div class="navbar-header">
  <!-- Toggle Button visible for Extra Small Devices -->
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <!-- Brand Logo -->
  <a class="navbar-brand" href="#"><span>Back Office</span></a>
</div>

<!-- Right Menu Side -->
<div class="navbar-collapse collapse">
  <ul class="nav navbar-nav">
    <!-- Left Menu Items -->
  </ul>
  <ul class="nav navbar-nav navbar-right user-nav">
    <!-- Right Profile Menu Items -->

  </ul>
  <ul class="nav navbar-nav navbar-right not-nav">

    <!-- Right Menu Notification Items -->
    <li class="button dropdown">
      <a href="javascript:;" class="dropdown-toggle logs-button" data-toggle="dropdown"><i class=" fa fa-globe"></i></a>
      <ul class="dropdown-menu messages">
        <li>
          <div class="nano nscroller">
            <div class="content">
              <ul id="loglist">
                <div class="logs-loading"><img src="{{asset('admin/images/ajax-loader.gif')}}"></div>
              </ul>
            </div>
          </div>
          <ul class="foot"><li><a href="{{action('DashController@getLogs')}}">Tüm hareketler</a></li></ul>
        </li>
      </ul>
    </li>
    <li class="button" style="width: 100px;margin-top: 7px;">
      <a href="/" target="_blank" style="width: 100px;">Siteyi Gör</a>
    </li>
  </ul>
</div>

</div>

<script>
  $(document).ready(function(){
    $.get('{{action('DashController@getLogsLatest')}}', null, function(response){
      $('.logs-loading').hide();
      response.forEach(function(log){
        var html = '<li>' +
                '<a href="#"><img src="{{asset('')}}' + log.pp +'" alt="avatar"/><span class="date pull-right">'+ log.created_at +'</span>' + log.message +
                '</a>' +
                '</li>';
        var object = $(html);
        $loglist = $('#loglist');
        object.appendTo($loglist);
      });
    }, 'json');
  });
</script>
