/*
 Version 1.1

 author: tongnguyen
 Start Date: 2/08/2010
 Finish Date: 2/08/210

 Usage:
		sLoading( message,image )

 History:

		1.00 - Released (29 December 2008)

 License:

*/
(function($) {

    $.loadings = {
        verticalOffset: -75,                // vertical offset of the dialog from center screen, in pixels
        horizontalOffset: 0,                // horizontal offset of the dialog from center screen, in pixels
        background: '#e3e2df',
        colorBorder: 'gray',
        width: 200,
        
        loading: function(message,image) {
            if( message == null ) message = 'Hệ thống đang xử lý vui lòng chờ...';
            if(image==null) image = boardUrl+"/styles/images/loading.gif";
            $.loadings.show( message,image);
        },


        // Private methods

        show: function(msg,image) {
            $.loadings.hide();
            $.loadings.overlay('show');
            $("BODY").append(
                "<div  id='imgLOAD' style='text-align:center;display:block;position:fixed;padding:10px;z-index:1002;' align='center'"+
                "</div><div id='WholePage'></div>");

            $("#imgLOAD").append("<b style='color:#000'>"+msg+"</b><br /><br /><div><img src='"+image+"' alt='' /></div>");
            // IE6 Fix
            var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed';

            $("#popup_over").css({
                position: pos
            });
            $("#imgLOAD").css({
                position: pos,
                background: $.loadings.background,
                border: '1px solid '+ $.loadings.colorBorder,
                width: $.loadings.width + 'px'
            });


            $.loadings.reposition();
        },

        hide: function() {
            $("#imgLOAD").remove();
            $.loadings.overlay('hide');
        },

        overlay: function(status) {
            switch( status ) {
                case 'show':
                    $.loadings.overlay('hide');
                    $("BODY").append('<div id="popup_over" class="ui-widget-overlay"></div>');
                    $("#popup_over").css({
                        position: 'absolute',
                        zIndex: 1001,
                        top: '0px',
                        left: '0px',
                        width: '100%',
                        height: $(document).height()
                    });
                    break;
                case 'hide':
                    $("#popup_over").remove();
                    break;
            }
        },

        reposition: function() {
            var top = (($(window).height() / 2) - ($("#imgLOAD").outerHeight() / 2)) + $.loadings.verticalOffset;
            var left = (($(window).width() / 2) - ($("#imgLOAD").outerWidth() / 2)) + $.loadings.horizontalOffset;
            if( top < 0 ) top = 0;
            if( left < 0 ) left = 0;
            $("#imgLOAD").css({
                top: top + 'px',
                left: left + 'px'
            });
            if($.browser.msie && parseInt($.browser.version) <= 6 )
                $("#popup_over").height(document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight);
        }
    }

    // Shortuct functions
    sLoading = function(message,image) {
        $.loadings.loading(message, image);
    }
})(jQuery);