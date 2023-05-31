import "glightbox/dist/css/glightbox.css";
import GLightbox from "glightbox";
import { setBackFocus } from "@components/utils/lightbox";

/*----------------------
----------------------
SINGLE VIDEO LIGHTBOX (no gallery)
Used for :
- Media block
- Text + media block
----------------------
----------------------*/
const lightboxVideoSelector = ".js-glightbox-video-single";
const lightboxVideoGallery = document.querySelector(lightboxVideoSelector);

if (lightboxVideoGallery) {
    //Custom HTML to disable next and prev button focus
    const customLightboxHTML = `<div id="glightbox-body" class="glightbox-container">
    <div class="gloader visible"></div>
    <div class="goverlay"></div>
    <div class="gcontainer">
    <div id="glightbox-slider" class="gslider"></div>
    <div class="gnext gbtn hidden" aria-label="Next"></div>
    <div class="gprev gbtn hidden" aria-label="Previous"></div>
    <button class="gclose gbtn" tabindex="0" aria-label="Close">{closeSVG}</button>
</div>
</div>`;

    const lightboxVideo = GLightbox({
        loop: false,
        lightboxHTML: customLightboxHTML,
        selector: lightboxVideoSelector,
        touchNavigation: false,
    });

    setBackFocus(lightboxVideoSelector, lightboxVideo);
}
