<?php
//about theme info
add_action( 'admin_menu', 'music_recording_studio_gettingstarted' );
function music_recording_studio_gettingstarted() {
	add_theme_page( esc_html__('About Music Recording Studio', 'music-recording-studio'), esc_html__('About Music Recording Studio', 'music-recording-studio'), 'edit_theme_options', 'music_recording_studio_guide', 'music_recording_studio_mostrar_guide');
}

// Add a Custom CSS file to WP Admin Area
function music_recording_studio_admin_theme_style() {
	wp_enqueue_style('music-recording-studio-custom-admin-style', esc_url(get_template_directory_uri()) . '/inc/getstart/getstart.css');
	wp_enqueue_script('music-recording-studio-tabs', esc_url(get_template_directory_uri()) . '/inc/getstart/js/tab.js');
}
add_action('admin_enqueue_scripts', 'music_recording_studio_admin_theme_style');

//guidline for about theme
function music_recording_studio_mostrar_guide() { 
	//custom function about theme customizer
	$music_recording_studio_return = add_query_arg( array()) ;
	$music_recording_studio_theme = wp_get_theme( 'music-recording-studio' );
?>

<div class="wrapper-info">
    <div class="col-left">
    	<h2><?php esc_html_e( 'Welcome to Music Recording Studio', 'music-recording-studio' ); ?> <span class="version"><?php esc_html_e( 'Version', 'music-recording-studio' ); ?>: <?php echo esc_html($music_recording_studio_theme['Version']);?></span></h2>
    	<p><?php esc_html_e('All our WordPress themes are modern, minimalist, 100% responsive, seo-friendly,feature-rich, and multipurpose that best suit designers, bloggers and other professionals who are working in the creative fields.','music-recording-studio'); ?></p>
    </div>

    <div class="col-right">
    	<div class="logo">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/final-logo.png" alt="" />
		</div>
		<div class="update-now">
			<h4><?php esc_html_e('Buy Recording Studio at 20% Discount','music-recording-studio'); ?></h4>
			<h4><?php esc_html_e('Use Coupon','music-recording-studio'); ?> ( <span><?php esc_html_e('vwpro20','music-recording-studio'); ?></span> ) </h4>
			<div class="info-link">
				<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_BUY_NOW ); ?>" target="_blank"> <?php esc_html_e( 'Upgrade to Pro', 'music-recording-studio' ); ?></a>
			</div>
		</div>
   	</div>

    <div class="tab-sec">
    	<div class="tab">
			<button class="tablinks" onclick="music_recording_studio_open_tab(event, 'lite_theme')"><?php esc_html_e( 'Setup With Customizer', 'music-recording-studio' ); ?></button>
			<button class="tablinks" onclick="music_recording_studio_open_tab(event, 'block_pattern')"><?php esc_html_e( 'Setup With Block Pattern', 'music-recording-studio' ); ?></button>
			<button class="tablinks" onclick="music_recording_studio_open_tab(event, 'gutenberg_editor')"><?php esc_html_e( 'Setup With Gutunberg Block', 'music-recording-studio' ); ?></button>
			<button class="tablinks" onclick="music_recording_studio_open_tab(event, 'product_addons_editor')"><?php esc_html_e( 'Woocommerce Product Addons', 'music-recording-studio' ); ?></button>
			<button class="tablinks" onclick="music_recording_studio_open_tab(event, 'theme_pro')"><?php esc_html_e( 'Get Premium', 'music-recording-studio' ); ?></button>
		  	<button class="tablinks" onclick="music_recording_studio_open_tab(event, 'free_pro')"><?php esc_html_e( 'Support', 'music-recording-studio' ); ?></button>
		</div>

		<?php
			$music_recording_studio_plugin_custom_css = '';
			if(class_exists('Ibtana_Visual_Editor_Menu_Class')){
				$music_recording_studio_plugin_custom_css ='display: block';
			}
		?>
		<div id="lite_theme" class="tabcontent open">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
				$plugin_ins = Music_Recording_Studio_Plugin_Activation_Settings::get_instance();
				$music_recording_studio_actions = $plugin_ins->recommended_actions;
				?>
				<div class="music-recording-studio-recommended-plugins">
				    <div class="music-recording-studio-action-list">
				        <?php if ($music_recording_studio_actions): foreach ($music_recording_studio_actions as $key => $music_recording_studio_actionValue): ?>
				                <div class="music-recording-studio-action" id="<?php echo esc_attr($music_recording_studio_actionValue['id']);?>">
			                        <div class="action-inner">
			                            <h3 class="action-title"><?php echo esc_html($music_recording_studio_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($music_recording_studio_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($music_recording_studio_actionValue['link']); ?>
			                            <a class="ibtana-skip-btn" get-start-tab-id="lite-theme-tab" href="javascript:void(0);"><?php esc_html_e('Skip','music-recording-studio'); ?></a>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php } ?>
			<div class="lite-theme-tab" style="<?php echo esc_attr($music_recording_studio_plugin_custom_css); ?>">
				<h3><?php esc_html_e( 'Lite Theme Information', 'music-recording-studio' ); ?></h3>
				<hr class="h3hr">
				<p><?php esc_html_e('With Music Recording Studio you can showcase your artistic skills and exhibit your work in a compelling manner to your fans. Without a question, this is the most functional, competent, mobile-friendly, and visually attractive WordPress theme. This WP Theme works well with websites that promote albums, artists, audio companies, DJ artists, label music,  popular music band, music store, festival, and many more websites that deal with the music business. This multipurpose theme is designed on a solid basis, enabling one to build a fully functional and feature-rich website. The theme is completely customizable, SEO-friendly, responsive, interactive, and has a quicker website load time. The theme is well-designed and adaptable, and it performs admirably. It features a CTA button and tidy code ensuring that you dont face any bug-related issues. Social networking icons have been incorporated to ensure that your fans do not miss out on your new music launch or crucial updates. Create an attractive aura for your audience with plenty of customization settings. There is also a testimonial feature in this WordPress theme. The high-quality banner animation image is engaging and will lure your fans the moment they visit your homepage.','music-recording-studio'); ?></p>
			  	<div class="col-left-inner">
			  		<h4><?php esc_html_e( 'Theme Documentation', 'music-recording-studio' ); ?></h4>
					<p><?php esc_html_e( 'If you need any assistance regarding setting up and configuring the Theme, our documentation is there.', 'music-recording-studio' ); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'music-recording-studio' ); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Theme Customizer', 'music-recording-studio'); ?></h4>
					<p> <?php esc_html_e('To begin customizing your website, start by clicking "Customize".', 'music-recording-studio'); ?></p>
					<div class="info-link">
						<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'music-recording-studio'); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Having Trouble, Need Support?', 'music-recording-studio'); ?></h4>
					<p> <?php esc_html_e('Our dedicated team is well prepared to help you out in case of queries and doubts regarding our theme.', 'music-recording-studio'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support Forum', 'music-recording-studio'); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Reviews & Testimonials', 'music-recording-studio'); ?></h4>
					<p> <?php esc_html_e('All the features and aspects of this WordPress Theme are phenomenal. I\'d recommend this theme to all.', 'music-recording-studio'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_REVIEW ); ?>" target="_blank"><?php esc_html_e('Reviews', 'music-recording-studio'); ?></a>
					</div>

					<div class="link-customizer">
						<h3><?php esc_html_e( 'Link to customizer', 'music-recording-studio' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','music-recording-studio'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','music-recording-studio'); ?></a>
								</div>
							</div>

							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-slides"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_slidersettings') ); ?>" target="_blank"><?php esc_html_e('Slider Settings','music-recording-studio'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-category"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_service_section') ); ?>" target="_blank"><?php esc_html_e('Service Section','music-recording-studio'); ?></a>
								</div>
							</div>
						
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','music-recording-studio'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','music-recording-studio'); ?></a>
								</div>
							</div>
							
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','music-recording-studio'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','music-recording-studio'); ?></a>
								</div>
							</div>
						</div>
					</div>
			  	</div>
				<div class="col-right-inner">
					<h3 class="page-template"><?php esc_html_e('How to set up Home Page Template','music-recording-studio'); ?></h3>
				  	<hr class="h3hr">
					<p><?php esc_html_e('Follow these instructions to setup Home page.','music-recording-studio'); ?></p>
                  	<p><span class="strong"><?php esc_html_e('1. Create a new page :','music-recording-studio'); ?></span><?php esc_html_e(' Go to ','music-recording-studio'); ?>
					  	<b><?php esc_html_e(' Dashboard >> Pages >> Add New Page','music-recording-studio'); ?></b></p>
                  	<p><?php esc_html_e('Name it as "Home" then select the template "Custom Home Page".','music-recording-studio'); ?></p>
                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/home-page-template.png" alt="" />
                  	<p><span class="strong"><?php esc_html_e('2. Set the front page:','music-recording-studio'); ?></span><?php esc_html_e(' Go to ','music-recording-studio'); ?>
					  	<b><?php esc_html_e(' Settings >> Reading ','music-recording-studio'); ?></b></p>
				  	<p><?php esc_html_e('Select the option of Static Page, now select the page you created to be the homepage, while another page to be your default page.','music-recording-studio'); ?></p>
                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/set-front-page.png" alt="" />
                  	<p><?php esc_html_e(' Once you are done with setup, then follow the','music-recording-studio'); ?> <a class="doc-links" href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_FREE_THEME_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation','music-recording-studio'); ?></a></p>
			  	</div>
			</div>
		</div>

		<div id="block_pattern" class="tabcontent">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
			$plugin_ins = Music_Recording_Studio_Plugin_Activation_Settings::get_instance();
			$music_recording_studio_actions = $plugin_ins->recommended_actions;
			?>
				<div class="music-recording-studio-recommended-plugins">
				    <div class="music-recording-studio-action-list">
				        <?php if ($music_recording_studio_actions): foreach ($music_recording_studio_actions as $key => $music_recording_studio_actionValue): ?>
				                <div class="music-recording-studio-action" id="<?php echo esc_attr($music_recording_studio_actionValue['id']);?>">
			                        <div class="action-inner">
			                            <h3 class="action-title"><?php echo esc_html($music_recording_studio_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($music_recording_studio_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($music_recording_studio_actionValue['link']); ?>
			                            <a class="ibtana-skip-btn" href="javascript:void(0);" get-start-tab-id="gutenberg-editor-tab"><?php esc_html_e('Skip','music-recording-studio'); ?></a>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php } ?>
			<div class="gutenberg-editor-tab" style="<?php echo esc_attr($music_recording_studio_plugin_custom_css); ?>">
				<div class="block-pattern-img">
				  	<h3><?php esc_html_e( 'Block Patterns', 'music-recording-studio' ); ?></h3>
					<hr class="h3hr">
					<p><?php esc_html_e('Follow the below instructions to setup Home page with Block Patterns.','music-recording-studio'); ?></p>
	              	<p><b><?php esc_html_e('Click on Below Add new page button >> Click on "+" Icon >> Click Pattern Tab >> Click on homepage sections >> Publish.','music-recording-studio'); ?></span></b></p>
	              	<div class="music-recording-studio-pattern-page">
				    	<a href="javascript:void(0)" class="vw-pattern-page-btn button-primary button"><?php esc_html_e('Add New Page','music-recording-studio'); ?></a>
				    </div>
	              	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/block-pattern.png" alt="" />
	            </div>

              	<div class="block-pattern-link-customizer">
					<h3><?php esc_html_e( 'Link to customizer', 'music-recording-studio' ); ?></h3>
					<hr class="h3hr">
					<div class="first-row">
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','music-recording-studio'); ?></a>
							</div>
							<div class="row-box2">
								<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','music-recording-studio'); ?></a>
							</div>
						</div>
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','music-recording-studio'); ?></a>
							</div>
							
							<div class="row-box2">
								<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','music-recording-studio'); ?></a>
							</div>
						</div>
						
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','music-recording-studio'); ?></a>
							</div>
							 <div class="row-box2">
								<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','music-recording-studio'); ?></a>
							</div> 
						</div>
					</div>
				</div>			
					
	        </div>
		</div>
		
		<div id="gutenberg_editor" class="tabcontent">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
			$plugin_ins = Music_Recording_Studio_Plugin_Activation_Settings::get_instance();
			$music_recording_studio_actions = $plugin_ins->recommended_actions;
			?>
				<div class="music-recording-studio-recommended-plugins">
				    <div class="music-recording-studio-action-list">
				        <?php if ($music_recording_studio_actions): foreach ($music_recording_studio_actions as $key => $music_recording_studio_actionValue): ?>
				                <div class="music-recording-studio-action" id="<?php echo esc_attr($music_recording_studio_actionValue['id']);?>">
			                        <div class="action-inner plugin-activation-redirect">
			                            <h3 class="action-title"><?php echo esc_html($music_recording_studio_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($music_recording_studio_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($music_recording_studio_actionValue['link']); ?>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php }else{ ?>
				<h3><?php esc_html_e( 'Gutunberg Blocks', 'music-recording-studio' ); ?></h3>
				<hr class="h3hr">
				<div class="music-recording-studio-pattern-page">
			    	<a href="<?php echo esc_url( admin_url( 'admin.php?page=ibtana-visual-editor-templates' ) ); ?>" class="vw-pattern-page-btn ibtana-dashboard-page-btn button-primary button"><?php esc_html_e('Ibtana Settings','music-recording-studio'); ?></a>
			    </div>

			    <div class="link-customizer-with-guternberg-ibtana">
	              	<div class="link-customizer-with-block-pattern">
						<h3><?php esc_html_e( 'Link to customizer', 'music-recording-studio' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','music-recording-studio'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','music-recording-studio'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','music-recording-studio'); ?></a>
								</div>
								
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','music-recording-studio'); ?></a>
								</div>
							</div>
							
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=music_recording_studio_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','music-recording-studio'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','music-recording-studio'); ?></a>
								</div> 
							</div>
						</div>
					</div>	
				</div>
			<?php } ?>
		</div>

				<div id="product_addons_editor" class="tabcontent">
			<?php if(!class_exists('IEPA_Loader')){
				$plugin_ins = Music_Recording_Studio_Plugin_Activation_Woo_Products::get_instance();
				$music_recording_studio_actions = $plugin_ins->recommended_actions;
				?>
				<div class="music-recording-studio-recommended-plugins">
					    <div class="music-recording-studio-action-list">
					        <?php if ($music_recording_studio_actions): foreach ($music_recording_studio_actions as $key => $music_recording_studio_actionValue): ?>
					                <div class="music-recording-studio-action" id="<?php echo esc_attr($music_recording_studio_actionValue['id']);?>">
				                        <div class="action-inner plugin-activation-redirect">
				                            <h3 class="action-title"><?php echo esc_html($music_recording_studio_actionValue['title']); ?></h3>
				                            <div class="action-desc"><?php echo esc_html($music_recording_studio_actionValue['desc']); ?></div>
				                            <?php echo wp_kses_post($music_recording_studio_actionValue['link']); ?>
				                        </div>
					                </div>
					            <?php endforeach;
					        endif; ?>
					    </div>
				</div>
			<?php }else{ ?>
				<h3><?php esc_html_e( 'Woocommerce Products Blocks', 'music-recording-studio' ); ?></h3>
				<hr class="h3hr">
				<div class="music-recording-studio-pattern-page">
					<p><?php esc_html_e('Follow the below instructions to setup Products Templates.','music-recording-studio'); ?></p>
					<p><b><?php esc_html_e('1. First you need to activate these plugins','music-recording-studio'); ?></b></p>
						<p><?php esc_html_e('1. Ibtana - WordPress Website Builder ','music-recording-studio'); ?></p>
						<p><?php esc_html_e('2. Ibtana - Ecommerce Product Addons.','music-recording-studio'); ?></p>
						<p><?php esc_html_e('3. Woocommerce','music-recording-studio'); ?></p>

					<p><b><?php esc_html_e('2. Go To Dashboard >> Ibtana Settings >> Woocommerce Templates','music-recording-studio'); ?></span></b></p>
	              	<div class="music-recording-studio-pattern-page">
			    		<a href="<?php echo esc_url( admin_url( 'admin.php?page=ibtana-visual-editor-woocommerce-templates&ive_wizard_view=parent' ) ); ?>" class="vw-pattern-page-btn ibtana-dashboard-page-btn button-primary button"><?php esc_html_e('Woocommerce Templates','music-recording-studio'); ?></a>
			    	</div>
	              	<p><?php esc_html_e('You can create a template as you like.','music-recording-studio'); ?></span></p>
			    </div>
			<?php } ?>
		</div>

		<div id="theme_pro" class="tabcontent">
		  	<h3><?php esc_html_e( 'Premium Theme Information', 'music-recording-studio' ); ?></h3>
			<hr class="h3hr">
		    <div class="col-left-pro">
		    	<p><?php esc_html_e('WordPress themes are considered the most essential component to building an impactful website and to make it more artistic, several tools are also added to it. These simple terms when organized perfectly create a more sophisticated website that would be more attractive to its users. Online users tend to visit those websites that look perfectly arranged and whose tools are also extremely easy to be operated. Recording Studio WordPress theme has got many exciting features such as sliders with an unlimited number of slides, customized text boxes, customized header and footer and many other simple yet useful elements. This theme would perfectly suit the recording studios, audio sectors, and other related fields to make it more effective for its users.','music-recording-studio'); ?></p>
		    	<div class="pro-links">
			    	<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'music-recording-studio'); ?></a>
					<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Pro', 'music-recording-studio'); ?></a>
					<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_PRO_DOC ); ?>" target="_blank"><?php esc_html_e('Pro Documentation', 'music-recording-studio'); ?></a>
				</div>
		    </div>
		    <div class="col-right-pro">
		    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/responsive.png" alt="" />
		    </div>
		    <div class="featurebox">
			    <h3><?php esc_html_e( 'Theme Features', 'music-recording-studio' ); ?></h3>
				<hr class="h3hr">
				<div class="table-image">
					<table class="tablebox">
						<thead>
							<tr>
								<th></th>
								<th><?php esc_html_e('Free Themes', 'music-recording-studio'); ?></th>
								<th><?php esc_html_e('Premium Themes', 'music-recording-studio'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Theme Customization', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Responsive Design', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Logo Upload', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Social Media Links', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Slider Settings', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Number of Slides', 'music-recording-studio'); ?></td>
								<td class="table-img"><?php esc_html_e('4', 'music-recording-studio'); ?></td>
								<td class="table-img"><?php esc_html_e('Unlimited', 'music-recording-studio'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Template Pages', 'music-recording-studio'); ?></td>
								<td class="table-img"><?php esc_html_e('3', 'music-recording-studio'); ?></td>
								<td class="table-img"><?php esc_html_e('6', 'music-recording-studio'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Home Page Template', 'music-recording-studio'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'music-recording-studio'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'music-recording-studio'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Theme sections', 'music-recording-studio'); ?></td>
								<td class="table-img"><?php esc_html_e('2', 'music-recording-studio'); ?></td>
								<td class="table-img"><?php esc_html_e('12', 'music-recording-studio'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Contact us Page Template', 'music-recording-studio'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('1', 'music-recording-studio'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Blog Templates & Layout', 'music-recording-studio'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('3(Full width/Left/Right Sidebar)', 'music-recording-studio'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Page Templates & Layout', 'music-recording-studio'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('2(Left/Right Sidebar)', 'music-recording-studio'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Color Pallete For Particular Sections', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Global Color Option', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Reordering', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Demo Importer', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Allow To Set Site Title, Tagline, Logo', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Enable Disable Options On All Sections, Logo', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Full Documentation', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Latest WordPress Compatibility', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Woo-Commerce Compatibility', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Support 3rd Party Plugins', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Secure and Optimized Code', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Exclusive Functionalities', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Enable / Disable', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Section Google Font Choices', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Gallery', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Simple & Mega Menu Option', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Support to add custom CSS / JS ', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Shortcodes', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Background, Colors, Header, Logo & Menu', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Premium Membership', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Budget Friendly Value', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Priority Error Fixing', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Feature Addition', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('All Access Theme Pass', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Seamless Customer Support', 'music-recording-studio'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td></td>
								<td class="table-img"></td>
								<td class="update-link"><a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Upgrade to Pro', 'music-recording-studio'); ?></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div id="free_pro" class="tabcontent">
		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-star-filled"></span><?php esc_html_e('Pro Version', 'music-recording-studio'); ?></h4>
				<p> <?php esc_html_e('To gain access to extra theme options and more interesting features, upgrade to pro version.', 'music-recording-studio'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Get Pro', 'music-recording-studio'); ?></a>
				</div>
		  	</div>
		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-cart"></span><?php esc_html_e('Pre-purchase Queries', 'music-recording-studio'); ?></h4>
				<p> <?php esc_html_e('If you have any pre-sale query, we are prepared to resolve it.', 'music-recording-studio'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_CONTACT ); ?>" target="_blank"><?php esc_html_e('Question', 'music-recording-studio'); ?></a>
				</div>
		  	</div>
		  	<div class="col-3">		  		
		  		<h4><span class="dashicons dashicons-admin-customizer"></span><?php esc_html_e('Child Theme', 'music-recording-studio'); ?></h4>
				<p> <?php esc_html_e('For theme file customizations, make modifications in the child theme and not in the main theme file.', 'music-recording-studio'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_CHILD_THEME ); ?>" target="_blank"><?php esc_html_e('About Child Theme', 'music-recording-studio'); ?></a>
				</div>
		  	</div>

		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e('Frequently Asked Questions', 'music-recording-studio'); ?></h4>
				<p> <?php esc_html_e('We have gathered top most, frequently asked questions and answered them for your easy understanding. We will list down more as we get new challenging queries. Check back often.', 'music-recording-studio'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_FAQ ); ?>" target="_blank"><?php esc_html_e('View FAQ','music-recording-studio'); ?></a>
				</div>
		  	</div>

		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-sos"></span><?php esc_html_e('Support Queries', 'music-recording-studio'); ?></h4>
				<p> <?php esc_html_e('If you have any queries after purchase, you can contact us. We are eveready to help you out.', 'music-recording-studio'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( MUSIC_RECORDING_STUDIO_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Contact Us', 'music-recording-studio'); ?></a>
				</div>
		  	</div>
		</div>

	</div>
</div>

<?php } ?>