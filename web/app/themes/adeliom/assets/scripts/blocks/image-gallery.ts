import "glightbox/dist/css/glightbox.css";
import GLightbox from "glightbox";
import { setBackFocus } from "@scripts/components/lightbox";

const defaultLightboxSelector = ".js-gallery";
const defaultLightboxGallery = document.querySelector(defaultLightboxSelector);

if (defaultLightboxGallery) {
    const defaultLightbox = GLightbox({
        selector: defaultLightboxSelector,
    });

    setBackFocus(defaultLightboxSelector, defaultLightbox);
}
