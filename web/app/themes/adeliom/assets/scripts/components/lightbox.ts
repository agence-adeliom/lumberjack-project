import "glightbox/dist/css/glightbox.css";
import GLightbox from "glightbox";

// Trick to set back focus on current element on close
export const setBackFocus = (selector: string, gallery: any) => {
    const lightboxOpeners = document.querySelectorAll(selector);
    let lastOpener: Element | null = null;
    lightboxOpeners.forEach((opener) => {
        opener.addEventListener("click", () => {
            lastOpener = opener;
        });
    });
    gallery.on("close", () => {
        if (lastOpener) {
            (lastOpener as HTMLElement).focus();
        }
        lastOpener = null;
    });
};

/*----------------------
----------------------
DEFAULT LIGHTBOX
----------------------
----------------------*/
const defaultLightboxSelector = ".js-glightbox";
const defaultLightboxGallery = document.querySelector(defaultLightboxSelector);

if (defaultLightboxGallery) {
    const defaultLightbox = GLightbox({
        selector: defaultLightboxSelector,
    });

    setBackFocus(defaultLightboxSelector, defaultLightbox);
}
