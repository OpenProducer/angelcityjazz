jQuery(document).ready(function ($) {

    $('.sp-logo-carousel-pro-area.lcp-filter-opacity').each(function (index) {

    var filter_id = $(this).attr('id');

    $.fn.hideReveal = function (options) {
        options = $.extend({
            filter: $('#' + filter_id).data('filter'),
            hiddenStyle: { opacity: 0.1 },
            visibleStyle: { opacity: 1 },
        }, options);
        this.each(function () {
            var $items = $(this).children();
            var $visible = $items.filter(options.filter);
            var $hidden = $items.not(options.filter);
            // reveal visible
            $visible.animate(options.visibleStyle);
            // hide hidden
            $hidden.animate(options.hiddenStyle);
        });
    };

    $(function () {

        var $container = $('#' + filter_id + ' .sp-isotope-logo-items');
        var $filter = $('#' + filter_id + ' .sp-logo-filter');

        try {
            $container.imagesLoaded(function () {
                $container.show();
                $container.isotope({
                    layoutMode: 'masonry',
                });
            });
        } catch (err) {
        }

        // filter functions
        var filterFns = {
            // show if number is greater than 50
            numberGreaterThan50: function () {
                var number = $(this).find('.number').text();
                return parseInt(number, 10) > 50;
            },
            // show if name ends with -ium
            ium: function () {
                var name = $(this).find('.name').text();
                return name.match(/ium$/);
            }
        };

        // bind filter button click
        $('#' + filter_id + ' .sp-logo-filter').on('click', 'button', function () {
            var filterValue = $(this).attr('data-filter');
            // use filterFn if matches value
            filterValue = filterFns[filterValue] || filterValue;
            $container.hideReveal({ filter: filterValue });
        });
        var filterValue = $(this).attr('data-filter');
        // use filterFn if matches value
        filterValue = filterFns[filterValue] || filterValue;
        $container.hideReveal({ filter: filterValue });

        // change is-checked class on buttons
        $('#' + filter_id + ' .sp-logo-filter').each(function (i, buttonGroup) {
            var $buttonGroup = $(buttonGroup);
            var firstFilterItem = $('#' + filter_id + ' .sp-logo-filter li:first button');
            firstFilterItem.addClass('active');
            $buttonGroup.on('click', 'button', function () {
                $buttonGroup.find('.active').removeClass('active');
                $(this).addClass('active');
            });
        });

    });

    });
});
