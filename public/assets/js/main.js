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
    $("#searchit, .m_search").click(function() {
        if($menu_open == 0){
            $(".search_box").slideDown( 200 );
            $(".pic").addClass( "pic_close", 200 );
            $menu_open = 1;
        }
        else{
            $(".search_box").slideUp( 200 );
            $(".pic").removeClass( "pic_close", 200 );
            $menu_open = 0;
        }  
    });
    $(".search_close").click(function() {
        $(".search_box").slideUp( 200 );
        $(".pic").removeClass( "pic_close", 200 );
        $menu_open = 0;
    });

    $(".m_menu_button").click(function() {
        $(document.body).css("overflow-y","hidden");
        $(".mobile_menu").animate({opacity:"1", height:"100%"}, 500);
        $(".mobile_menu .top").delay(200).animate({opacity:"1"}, 500);
        $(".mobile_menu .bottom").delay(400).animate({opacity:"1"}, 500);
        $(".mobile_menu .social").delay(600).animate({opacity:"1"}, 500);
    });
    $(".m_menu_close").click(function() {
        $(document.body).css("overflow-y","auto");
        $(".mobile_menu").delay(600).animate({height:"0", opacity:"0"}, 500);
        $(".mobile_menu .top").delay(400).animate({opacity:"0"}, 500);
        $(".mobile_menu .bottom").delay(200).animate({opacity:"0"}, 500);
        $(".mobile_menu .social").animate({opacity:"0"}, 500);
    });

    if ($('.txt').length > 0) {
        $('.txt').find('img').each(function () {            
            if ($(this).attr('alt') != "" && $(this).attr('alt') != " " && $(this).attr('alt') != "image") {
                var caption = $('<i/>', {
                    style: 'border-bottom: 1px dotted #ea158c; width:100%; font-weight: 500; font-size:15px; display: block;font-style: normal;'
                }).text($(this).attr('alt'));
                $(this).after(caption);
            }
        });

        if ($('.txt').find('.referans').length > 0) {
            $('.txt').find('.referans').attr('style', 'font-size: 14px');
        }
    }

});