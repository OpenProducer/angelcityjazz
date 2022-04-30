jQuery(document).ready(function ($) {

    $('.sp-logo-carousel-pro-area.splcp-ttip').each(function (index) {
        var section_id = $(this).attr('id');
        var splcp_tooltip_conf =$(this).closest('.sp-logo-carousel-pro-section').find('.sp-lcp-tooltip-conf');
        jQuery('.sp-logo-carousel-pro-section #' + section_id + ' .sp-lcp-tooltip').tooltipster({
            animation: splcp_tooltip_conf.data('animation'),
            delay: 200,
            theme: section_id + 'style',
            side: splcp_tooltip_conf.data('side'),
            trigger: "hover",
            maxWidth: splcp_tooltip_conf.data('maxwidth'),
        });
    });
});
