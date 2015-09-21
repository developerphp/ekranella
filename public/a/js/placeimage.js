(function ( $ ) {

    $.fn.placeImg = function() {

        var count = 1;
        return this.each(function() {
            var item = $(this);
            $.ajax({url:item.attr('src'),type:'HEAD',error:function(){
                item.attr('src', 'http://placehold.it/'+item.width()+'x'+item.height()+'/');
            }});
        });

    };

}( jQuery ));