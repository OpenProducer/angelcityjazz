jQuery(document).ready(function ($) {
    // ----------------------------------------------
    //  Isotope Filter
    // ----------------------------------------------
    $('.sp-logo-carousel-pro-area.lcp-filter-normal').each(function (index) {
        var _this = $(this);
        var filter_id = $(this).attr('id');
      
        var winDow = $(window);
        var $container = $('#' + filter_id + ' .sp-isotope-logo-items');
        var $filter = $('#' + filter_id + ' .sp-logo-filter');
        try {
            $container.imagesLoaded(function () {
                $container.show();
                $container.isotope({
                    filter: $('#' + filter_id).data('filter'),
                    layoutMode: $('#' + filter_id).data('layout_mode'),
                    animationOptions: {
                        duration: 750,
                        easing: 'linear'
                    }
                });
            });
        } catch (err) {
        }

        winDow.bind('resize', function () {
            var selector = $filter.find('button.active').attr('data-filter');

            try {
                $container.isotope({
                    filter: selector,
                    animationOptions: {
                        duration: 750,
                        easing: 'linear',
                        queue: false,
                    }
                });
            } catch (err) {
            }
            return false;
        });

        $filter.find('button').click(function () {
            var selector = $(this).attr('data-filter');
            try {
                $container.isotope({
                    filter: selector,
                    animationOptions: {
                        duration: 750,
                        easing: 'linear',
                        queue: false
                    }
                });
            } catch (err) {

            }
            return false;
        });

        var filterItemA = $('#' + filter_id + ' .sp-logo-filter button');
        var firstFilterItem = $('#' + filter_id + ' .sp-logo-filter li:first button');
        firstFilterItem.addClass('active');
        filterItemA.on('click', function () {
            var $this = $(this);
            if (!$this.hasClass('active')) {
                filterItemA.removeClass('active');
                $this.addClass('active');
            }
        });

    });
});
