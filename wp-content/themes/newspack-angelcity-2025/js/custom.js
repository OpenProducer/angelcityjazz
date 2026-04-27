/**
 * custom.js – Angel City Jazz 2025
 * Version: 1.2.1
 * Date: 2025-07-22
 * Description: Adds lazy load support for Spotify embeds + image layout logic for Tribe Events.
 */

// === Lazy-load Spotify embeds ===
function initLazySpotifyEmbeds() {
  const lazySpotifyIframes = document.querySelectorAll('iframe.lazy-spotify');

  lazySpotifyIframes.forEach((iframe) => {
    const dataSrc = iframe.getAttribute('data-src');
    const src = iframe.getAttribute('src');

    if (dataSrc && !src) {
      iframe.setAttribute('src', dataSrc);
      console.log('🎵 Lazy-loaded Spotify iframe:', dataSrc);
    }
  });
}

// Run once DOM is fully parsed
document.addEventListener('DOMContentLoaded', initLazySpotifyEmbeds);

// Also run after full page load in case of late rendering
window.addEventListener('load', () => {
  console.log('✅ custom.js loaded and window fully loaded.');
  setTimeout(initLazySpotifyEmbeds, 100);

  // === Tribe Events layout logic ===
  const gridSelector = '.tribe-common-g-row--gutters';
  let attempts = 0;
  const maxAttempts = 20;

  const interval = setInterval(() => {
    const grid = document.querySelector(gridSelector);
    attempts++;

    if (grid) {
      console.log(`✅ Grid container "${gridSelector}" found after ${attempts} attempt(s).`);
      clearInterval(interval);

      if (typeof jQuery.fn.imagesLoaded !== 'function') {
        console.error('🧱 imagesLoaded plugin is not loaded.');
        return;
      }

      let layoutReadyApplied = false;

      jQuery(grid).imagesLoaded()
        .progress((instance, image) => {
          const $item = jQuery(image.img).closest('.tribe-common-g-col');
          if ($item.length) {
            $item.addClass('item-visible');
          }

          if (!layoutReadyApplied) {
            grid.classList.add('layout-ready');
            layoutReadyApplied = true;
            console.log('✨ Layout ready after first image loaded.');
          }
        })
        .always(() => {
          console.log('✅ All images loaded. (Final check)');
        });

      setTimeout(() => {
        if (!layoutReadyApplied) {
          grid.classList.add('layout-ready');
          console.warn('⚠️ Fallback: layout-ready forced after timeout.');
        }
      }, 3000);

    } else if (attempts >= maxAttempts) {
      console.warn(`🧱 Grid container "${gridSelector}" not found after ${attempts} attempts. Giving up.`);
      clearInterval(interval);
    }
  }, 250);
});
