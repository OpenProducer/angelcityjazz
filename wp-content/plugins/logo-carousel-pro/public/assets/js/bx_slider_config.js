jQuery(document).ready(function ($) {

    $('.sp-logo-carousel-pro-area.mode_ticker').each(function (index) {
        var _this = $(this);
        var custom_id = _this.attr('id');
        if (custom_id != '') {
           jQuery('#' + custom_id).bxSlider({
                minSlides: _this.data('minslide'),
                maxSlides: _this.data('maxslide'),
                slideWidth: _this.data('slidewidth'),
                slideMargin: _this.data('slidemargin'),
                autoDirection: _this.data('direction'),
                ticker: true,
                speed: _this.data('speed'),
                mode: _this.data('mode'),
                tickerHover: _this.data('tickerhover'),
                responsive: false,
            });
        }
    });
});
