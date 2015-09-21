function saveFacebookUser() {
    FB.api('/me', function (response) {
        $.post("/ajax/facebook/", {
            name: response.first_name,
            surname: response.last_name,
            email: response.email,
            gender: response.gender,
            fbID: response.id
        });
    });
}

function facebookLogin() {
    FB.login(function (response) {
        if (response.status === 'connected') {
            $('#facebookLoginBtn').hide();
            saveFacebookUser();


            //$('.fb-comments').fadeIn();
        } else if (response.status === 'not_authorized') {
            //$('.fb-comments').hide();
        } else {
            //$('.fb-comments').hide();

        }
    }, {scope: 'public_profile,email'});
}


window.fbAsyncInit = function () {
    FB.init({
        appId: '1559952917583872',
        cookie: true,
        xfbml: true,
        version: 'v2.1'
    });

    FB.getLoginStatus(function (response) {
        if (response.status === 'connected') {
            $('#facebookLoginBtn').hide();
            //$('.fb-comments').fadeIn();
            saveFacebookUser();
        } else {
            //$('.fb-comments').hide();
        }
    });
};

$(document).on('click', '#facebookLoginBtn', function () {
    facebookLogin();
});

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


$(document).ready(function () {
    //$('img').placeImg();
    var stickyNavTop = $('header nav').offset().top;

    var stickyNav = function () {
        var scrollTop = $(window).scrollTop();

        if (scrollTop > stickyNavTop) {
            $('header nav').addClass('sticky');
        } else {
            $('header nav').removeClass('sticky');
        }
    };

    stickyNav();

    $(window).scroll(function () {
        stickyNav();
    });

    $(document).on('click', '.with-slider .tabs li a', function (e) {
        var href = $(this).attr('href');
        var cur = $(this).parent().parent().parent().parent().parent().parent().find('.tab.active');
        cur.removeClass('active');
        $(href).addClass('active');
        $(href + ' .programs-slide').flexslider({animation: "fade", controlNav: false, slideshow: false});
        e.preventDefault();
    });


    /*$(document).on('click', 'header nav ul li.headline > a, .two-column .left ul.dropdown-menu > li', function (e) {
     $(this).parent().toggleClass('active')
     e.preventDefault();
     });*/

    /*$(document).on('mouseleave', 'header nav ul li.headline > ul, .two-column .left ul.dropdown-menu.active > li', function (e) {
     $(this).parent().toggleClass('active')
     e.preventDefault();
     });*/
    $(document).on('click', '.search .hidethis', function (e) {
        $('.search input').focus();
        $(this).hide()
    });


    $(document).on('focus', '.search input', function (e) {
        $('.search .hidethis').hide()
    });
    $(document).on('blur', '.search input', function (e) {
        if ($(this).val() == '') $('.search .hidethis').show();
    });


    $(document).on('click', '.no-slider .tabs li a', function (e) {
        var href = $(this).attr('href');
        var cur = $(this).parent().parent().parent().parent().parent().parent().find('.tab.active');
        cur.removeClass('active');
        $(href).addClass('active');
        e.preventDefault()
    });


    $(document).on('click', '.tabser .tabs-head a', function (e) {
        var href = $(this).attr('href');
        $('.tabser .tabs-head li').removeClass('active')
        $('.tab.active').removeClass('active')
        $(href).addClass('active');
        $(this).parent().addClass('active');
        e.preventDefault()
    });


    if ($('.main-slider').length > 0) {
        $('.main-slider .flexslider').flexslider({animation: "slide"});
    }
    if ($('.programs-slide').length > 0) {
        $('.active .programs-slide').flexslider({animation: "fade", controlNav: false, slideshow: false});
    }


});

$(function () {
    "use strict";

    var $container = [];
    var count = $('.js-masonry').length;

    for (var i = 0; i < count; ++i) {
        $container[i] = $('#isotopeList' + i);
    }

    jQuery.each($container, function (i) {
        this.isotope({
            itemSelector: '.item',
            layoutMode: 'fitRows'
        });

        $('#isotopeList' + i).on('click', '.item', function () {
            var status = initClick($(this), $('#isotopeList' + i));
            $('#isotopeList' + i).isotope('layout');

            return status;

        });

        $('#isotopeList' + i).isotope('once', 'layoutComplete', function () {
            $("#isotopeList" + i + " li:first-child").trigger('click');
        });
    });

    setTimeout(function () {
        $('.js-masonry li:first-child').trigger('click');
    }, 300);

    function initClick(item, container) {
        if (!item.hasClass('two-col')) {
            var img = item.find('img');
            if (img.data('active') == "no") {
                img.attr('src', img.data('image') + '_thumbl.jpg');
                img.data('active', 'yes');
            }
            else {
                img.attr('src', img.data('img') + '__square.jpg');
                img.data('active', 'no');
            }

            var eximg = item.parents('.showcase').find('li.two-col').removeClass('two-col').find('img');
            eximg.data('active', 'no').attr('src', eximg.data('image') + '_square.jpg');
            item.addClass('two-col');


            if (item.index() == container.data('limit')) {
                var wrapper = item.closest('.js-masonry');
                var oldElement = wrapper.find('li').last();
                var element = $('<li/>', {
                    class: 'item',
                    id: 'created' + Math.floor((Math.random() * 10) + 1),
                    html: oldElement.html()
                });
                container.prepend(element).isotope('prepended', element).fadeIn();
                container.isotope('layout');
                container.isotope({
                    itemSelector: '.item',
                    layoutMode: 'fitRows'
                });
                container.isotope('remove', oldElement);
            }
            return false;
        } else {
            return true;
        }
    }

    var logoLoader = new PxLoader();

    logoLoader.addImage('/a/img/logo.gif');
    logoLoader.start();

    logoLoader.addProgressListener(function () {
        $('.icn-logo').css('background', 'url("/a/img/logo.gif") no-repeat');
    });


    var newsletterLoader = new PxLoader();

    newsletterLoader.addImage('/a/img/inputBack.png');
    newsletterLoader.addImage('/a/img/registerBtn.png');
    newsletterLoader.start();


    newsletterLoader.addProgressListener(function (e) {
        if (e.completedCount == 2) {
            var visited = $.cookie('visited');
            if (visited == 'yes' && $('#newsletterBox').length > 0)
                $('#newsletterClose').click();
            else {
                $('#newsletterBox').addClass('active');

                $.cookie('visited', 'yes', {
                    expires: 14
                });
            }
        }
    });

    $('#newsletterClose').click(function () {
        $('#newsletterBox').removeClass('active');
        setTimeout(function () {
            $('#newsletterBox').remove();
        }, 1000);
    });
    //copy from stackoverflow
    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $('#sendButton').click(function () {
        if (validateEmail($('#mailInput').text()))
            $.post("/ajax/facebook", {
                name: 'Newsletter',
                surname: ' ',
                email: $('#mailInput').text(),
                gender: ' ',
                fbID: null
            }, function () {
                $('#newsletterClose').click();
            });
        else
            alert('Geçerli bir adres girmelisiniz.');
    });

    //Image Loader
    var loader = new PxLoader();

    //Özetliyorum thumbl
    $('.showcase').find('.item').find('img').each(function (i, img) {
        loader.addImage($(img).data('image') + '_thumbl.jpg');
    });

    //Manşet slideThumb
    $('.flexslider').find('figure').each(function (i, figure) {
        loader.addImage($(figure).find('img').attr('src'));
    });

    loader.start();


    if ($('#textContent').length > 0) {
        $('#textContent').find('img').each(function () {
            $(this).attr('style', '').attr('width', '100%').attr('height', '');
            if ($(this).attr('alt') != "" && $(this).attr('alt') != " " && $(this).attr('alt') != "image") {
                var caption = $('<i/>', {
                    style: 'border-bottom: 1px dotted #ea158c; width:100%; font-weight: 500; font-size:15px; display: block;font-style: normal;'
                }).text($(this).attr('alt'));
                $(this).after(caption);
            }
        });

        if ($('#textContent').find('.referans').length > 0) {
            $('#textContent').find('.referans').attr('style', 'font-size: 14px');
        }

    }

    if ($('#galleryContent').length > 0)
        $('#galleryContent').find('img').each(function () {
            $(this).attr('style', '').attr('width', '100%').attr('height', '');
        });


    $('.tab-content .trailer').find('li').click(function () {
        if (!$(this).hasClass('big')) {
            var big = $('.tab-content .trailer li.big');
            big.find('a').attr('href', $(this).find('a').attr('href'));
            big.find('.text').html($(this).find('a').data('title'));
            big.find('img').attr('src', $(this).find('img').data('img') + '_thumb.jpg');

            return false;
        } else {
            return true;
        }
    });

    $('.bottomTabs').find('li').click(function () {
        if (!$(this).hasClass('big-col')) {
            var big = $(this).closest('.bottomTabs').find('li.big-col');
            big.find('a').attr('href', $(this).find('a').attr('href'));
            big.find('.text').find('h1').text($(this).find('a').data('title'));
            big.find('.rating').text($(this).find('.rating').text());
            big.find('.text').find('p').html($(this).find('a').data('description'));
            big.find('.text').find('small').text($(this).find('a').data('date'));
            big.find('img').attr('src', $(this).find('a').data('img') + '_square.jpg');
            $(this).data('active', true);
            return false;
        } else {
            return true;
        }
    });


    $('.bottomTabs').each(function () {
        $(this).find('li').each(function () {
            if ($(this).index() == 1)
                $(this).click();
        });
    });

    $('.tab-head.with-slider').find('a').click(function () {
        $(this).parents('.tabbed').find('.tab-head').find('a').removeClass('active');
        $(this).addClass('active');
        $('a[href="' + $(this).attr('href') + '"]').addClass('active');
    });

    var page = 1;
    var loading = false;

    function loadEpisode(page, type, all) {
        all = typeof all !== 'undefined' ? all : false;
        if (!all)
            $.ajax({
                url: "/ajax/dizi/" + type + '/' + page,
                type: 'POST',
                success: function (html) {
                    if (html != 'false') {
                        $(".endlessEpisode").append(html);
                        $(window).resize();
                        //$('#searchList').find('li').find('.serial_marked').css('height',207);
                        loading = false;
                    }
                }
            });
        else
            $.ajax({
                url: "/ajax/dizi/all/" + type,
                type: 'POST',
                success: function (html) {
                    if (html != 'false') {
                        $(".endlessEpisode").html('');
                        $(".endlessEpisode").append(html);
                        $(window).resize();
                        //$('#searchList').find('li').find('.serial_marked').css('height',207);
                        loading = false;
                        $('#liveInput').keydown();
                    }
                }
            });
        return false;
    }

    function loadEpisodeList(permalink, enumNum, page) {
        $('.loading').show();
        $.ajax({
            url: "/ajax/diziliste/" + permalink + '/' + enumNum + '/' + page,
            type: 'POST',
            success: function (html) {
                if (html != 'false') {
                    $(".endlessEpisodeList").append(html);
                    $(window).resize();
                    //$('#searchList').find('li').find('.serial_marked').css('height',207);
                    loading = false;
                }
                $('.loading').hide();
            }
        });
        return false;
    }

    function loadNewsList(permalink, page) {
        $('.loading').show();
        $.ajax({
            url: "/ajax/haberliste/" + permalink + '/' + page,
            type: 'POST',
            success: function (html) {
                if (html != 'false') {
                    $(".endlessNewsList").append(html);
                    loading = false;
                }
                $('.loading').hide();
            }
        });
        return false;
    }

    function loadSpecialsList(permalink, page) {
        $('.loading').show();
        $.ajax({
            url: "/ajax/ozelliste/" + permalink + '/' + page,
            type: 'POST',
            success: function (html) {
                if (html != 'false') {
                    $(".endlessSpecialsList").append(html);
                    loading = false;
                }
                $('.loading').hide();
            }
        });
        return false;
    }

    $(window).scroll(function () {
        if ($(window).scrollTop() > $(document).height() - $(window).height() - 600 && !search && searchLoad) {
            if ($('.endlessEpisode').length > 0 && !loading) {
                loading = true;
                loadEpisode(page, $('.endlessEpisode').data('type'));
                page++;
            }
            if ($('.endlessEpisodeList').length > 0 && !loading) {
                loading = true;
                loadEpisodeList($('.endlessEpisodeList').data('permalink'), $('.endlessEpisodeList').data('enum'), page);
                page++;
            }
            if ($('.endlessNewsList').length > 0 && !loading) {
                loading = true;
                loadNewsList($('.endlessNewsList').data('permalink'), page);
                page++;
            }
            if ($('.endlessSpecialsList').length > 0 && !loading) {
                loading = true;
                loadSpecialsList($('.endlessSpecialsList').data('permalink'), page);
                page++;
            }
        }
    });

    var searchLoad = true;
    var search = false;
    $('#liveInput').keydown(function () {
        if (searchLoad)
            loadEpisode('', $('.endlessEpisode').data('type'), true);
        searchLoad = false;

        var val = $(this).val();
        if (val == "" || val == null || val == " ") {
            search = false;
            $('#searchList').find('li').fadeIn();
        } else {
            search = true;
            $('#searchList').find('li').each(function () {
                var text = $(this).find('p').text();

                if (text.toLowerCase().indexOf(val) != -1 || text.toUpperCase().indexOf(val) != -1 || text.indexOf(val) != -1) {
                    $(this).fadeIn(300, function () {
                        var templi = $(this);
                        //setTimeout( function(){templi.css('height',217).find('.serial_marked').css('height',207).css('margin-top', -222);}, 200);
                    });
                } else {
                    $(this).fadeOut();
                    $('#searchList').find('li').find('.serial_marked').css('height', 207).css('margin-top', -222);
                }
            });
        }
    });

    $('#liveInput').change(function () {
        var val = $(this).val();
        if (val == "" || val == null || val == " ") {
            search = false;
            $('#searchList').find('li').fadeIn();
        }
    });

});

$(document).ready(function () {

    $('.galleryNavButton').hide();
    $('#galleryImage').load(function () {
        var imageHeight = $("#galleryImage").css('height');
        var buttonHeight = $(".galleryNavButton").css('height');
        $(".galleryNavButton").css('margin-top', (parseInt(imageHeight) - parseInt(buttonHeight)) / 2) + 'px';
        $('.galleryNavButton').show();
    });

    $(window).load(function () {
        var imageHeight = $("#galleryImage").css('height');
        var buttonHeight = $(".galleryNavButton").css('height');
        $(".galleryNavButton").css('margin-top', (parseInt(imageHeight) - parseInt(buttonHeight)) / 2) + 'px';
        $('.galleryNavButton').show();
    });

    var isAndroid = navigator.userAgent.indexOf('Android') >= 0;
    if ($('.breakpage').length > 0 &&  !isAndroid) {
        var breakpageDiv = $('.breakpage');
        var html = breakpageDiv.html();
        var htmlArr = html.split("<!-- pagebreak -->");

        var pageCount = 0;
        if (htmlArr.length > 1) {
            breakpageDiv.html('');
            var paginateHtml = '<div class="paginationWrap" style="margin-bottom: 20px"><ul class="pagination">';

            $.each(htmlArr, function (key, value) {
                var part = $('<div/>', {html: value}).addClass('type' + breakpageDiv.data('type')).addClass('id' + breakpageDiv.data('item_id')).addClass('page' + pageCount).hide();
                breakpageDiv.append(part);
                ++pageCount;
                paginateHtml += '<li class="paginateBtn"><a href="#" data-page="' + (pageCount - 1) + '">' + pageCount + '</a></li>';
            });

            paginateHtml += '<li class="showAllBtn"><a href="#">Tek Parça</a></li>';


            paginateHtml += '</ul></div>';

            breakpageDiv.after(paginateHtml);

            var lastPages = JSON.parse(localStorage.getItem('lastPages'));
            if (lastPages != undefined) {
                var pageFlag = false;
                $.each(lastPages, function (key, value) {
                    if ($(key + '.page' + value).length > 0) {
                        $(key + '.page' + value).fadeIn();
                        pageFlag = true;
                        $('a[data-page="' + value + '"]').closest('li').addClass('active');
                    }
                });
                if (!pageFlag)
                    $('.type' + breakpageDiv.data('type')).first().show();

            } else {
                $('.type' + breakpageDiv.data('type')).first().show();
                $('a[data-page="0"]').closest('li').addClass('active');
            }
        }


        $('.paginateBtn').on('click', function (e) {
            e.preventDefault(e);
            var savedTemp = JSON.parse(localStorage.getItem('lastPages'));

            var saved = {};
            if (savedTemp != null && savedTemp.isArray) {
                saved = savedTemp;
            }

            var clickedPage = $(this).find('a').data('page');
            var breakpageDiv = $('.breakpage');
            saved['.type' + breakpageDiv.data('type') + '.id' + breakpageDiv.data('item_id')] = clickedPage;

            localStorage.setItem('lastPages', JSON.stringify(saved));
            $('.type' + breakpageDiv.data('type')).hide();

            var scrollTarget = $('.type' + breakpageDiv.data('type') + '.id' + breakpageDiv.data('item_id') + '.page' + clickedPage);
            $('.paginateBtn').removeClass('active');
            $('.showAllBtn').removeClass('active');
            $('a[data-page="' + clickedPage + '"]').closest('li').addClass('active');

            scrollTarget.fadeIn(100);
            $('html, body').animate({
                scrollTop: scrollTarget.offset().top - 190
            }, 200);
            return false;
        });

        $('.showAllBtn').on('click', function (e) {
            e.preventDefault(e);
            var breakpageDiv = $('.breakpage');
            var scrollTarget = $('.type' + breakpageDiv.data('type') + '.id' + breakpageDiv.data('item_id') + '.page0');
            $('.type' + breakpageDiv.data('type') + '.id' + breakpageDiv.data('item_id')).show();
            $('.paginateBtn').removeClass('active');
            $('.showAllBtn').closest('li').addClass('active');
            scrollTarget.fadeIn(100);
            $('html, body').animate({
                scrollTop: scrollTarget.offset().top - 190
            }, 200);
            return false;
        });
    }

    if ($('#liveInput').length > 0)
        $('#liveInput').val('');

    if ($('#textContent').length > 0)
        $('#textContent').find('iframe').css('width', '100%');

    if ($('.fb-comments').length > 0)
        setInterval(function () {
            $('#facebookLoginBtn').css('height', $('.fb-comments').height());
        }, 300);
});


 
 
 
 
 
 
 
 