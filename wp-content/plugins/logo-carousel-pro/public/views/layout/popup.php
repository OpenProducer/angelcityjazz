<?php
$outline .= '<div class="remodal sp-lcpro-modal-logo-' . $post_id . ' sp-lcpro-modal-logo" data-remodal-id="sp-lcpro-logo-id-' . get_the_ID() . '">
<a data-remodal-action="close" class="remodal-close"></a>';
$outline .= '<div class="sp-lcpro-modal-logo-content">';
if ( has_post_thumbnail() ) {
	if ( ! empty( $logo_link ) && 'true' == $link ) {
		$outline .= '<a target="' . $target . '" href="' . esc_url( $logo_link ) . '" ' . $image_title_attr . ' ';
		if ( 'true' == $logo_ref ) {
			$outline .= 'rel="nofollow"';
		}
		$outline .= '>';
	}
	$outline .= '<img src="' . $lcp_img_url . '" alt="' . $logo_image_alt_title . '"';
	$outline .= '>';
	if ( ! empty( $logo_link ) && 'true' == $link ) {
		$outline .= '</a>';
	}
}
$outline .= '<h3 class="text-center lcpro-logo-title">' . get_the_title() . '</h3>';
$outline .= '<div class="lcpro-description">' . get_the_content() . '</div>';
$outline .= '</div>'; // sp-lcpro-modal-logo-content.
$outline .= '</div>';
