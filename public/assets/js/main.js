$(document).ready(function(){
	$('.indicators ul li').click(function(){
		$maindiv=$(this).parents('ul');
		$($maindiv).find('li').removeClass('active');
		$(this).addClass('active');		
	});

	$('.home_sections .page_select .button').click(function(){
		$(this).parents('.home_sections').addClass('hide');
		$($(this).data('href')).removeClass('hide');
	});


	var searchLoad = true;
    var search = false;
    $('#liveInput').keydown(function () {
        if (searchLoad)
            // loadEpisode('', $('.endlessEpisode').data('type'), true);
        searchLoad = false;

        var val = $(this).val();
        if (val == "" || val == null || val == " ") {
            search = false;
            $('#searchList').find('.home_boxes').fadeIn();
        } else {
            search = true;
            $('#searchList').find('.home_boxes').each(function () {
                var text = $(this).find('.desc').text();

                if (text.toLowerCase().indexOf(val) != -1 || text.toUpperCase().indexOf(val) != -1 || text.indexOf(val) != -1) {
                    $(this).fadeIn(0, function () {
                        var templi = $(this);
                        //setTimeout( function(){templi.css('height',217).find('.serial_marked').css('height',207).css('margin-top', -222);}, 200);
                    });
                } else {
                    $(this).fadeOut(0);
                    // $('#searchList').find('li').find('.serial_marked').css('height', 207).css('margin-top', -222);
                }
            });
        }
    });

    $('#liveInput').change(function () {
        var val = $(this).val();
        if (val == "" || val == null || val == " ") {
            search = false;
            $('#searchList').find('.home_boxes').fadeIn();
        }
    });

    if ($('#liveInput').length > 0) {
        $('#liveInput').val('');
    }

    var $menu_open = 0;
    $("#searchit").click(function() {
        if($menu_open == 0){
            $(".search_box").slideDown( 200 );
            $menu_open = 1;
        }
        else{
            $(".search_box").slideUp( 200 );
            $menu_open = 0;
        }
        
    });

});