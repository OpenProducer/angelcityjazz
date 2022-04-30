(function() {
	tinymce.PluginManager.add('sp_lcpro_mce_button', function( editor, url ) {
		editor.addButton('sp_lcpro_mce_button', {
			text: false,
            icon: false,
			image: url + '/icon-32.png',
            tooltip: 'Logo Carousel Pro',
            onclick: function () {
                editor.windowManager.open({
                    title: 'Insert Shortcode',
					width: 400,
					height: 100,
					body: [
						{
							type: 'listbox',
							name: 'listboxName',
                            label: 'Select Shortcode',
							'values': editor.settings.spLCPROShortcodeList
						}
					],
					onsubmit: function( e ) {
						editor.insertContent( '[logo_carousel_pro id="' + e.data.listboxName + '"]');
					}
				});
			}
		});
	});
})();