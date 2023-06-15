import Swiper, { Navigation } from "swiper";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

const swiper = new Swiper(".swiper", {
    // configure Swiper to use modules
    modules: [Navigation],
    slidesPerView: "auto",
    spaceBetween: "24",
    loop: false,
    freeMode: {
        enabled: true,
        sticky: false,
    },
    navigation: {
        nextEl: ".js-swiper-next",
        prevEl: ".js-swiper-prev",
    },
});

const stickyMenu: HTMLElement = document.querySelector(".js-sticky-menu")!;
const links: NodeListOf<HTMLElement> = stickyMenu?.querySelectorAll(
    ".js-sticky-menu-item "
);
const activeClass = "is-active";
const sectionOffset = 80;

let isClicked = false;
addEventListener("scroll", () => {
    links.forEach((el) => {
        const a: HTMLAnchorElement = el.querySelector("a")!;
        const anchor: string = a.getAttribute("href")!;
        const block: HTMLElement = document.querySelector(anchor)!;
        const blockTop: number = block.offsetTop;
        const sectionOffsetTop =
            block.getBoundingClientRect().top + window.pageYOffset;
        const sectionHeight = block.getBoundingClientRect().height;
        const filterOffsetTop =
            stickyMenu.getBoundingClientRect().top + window.pageYOffset;

        el.onclick = function (click: MouseEvent) {
            isClicked = true;
            links.forEach((allLinks) => {
                allLinks.classList.remove(activeClass);
            });
            click.preventDefault();
            window.scrollTo({
                top: blockTop,
                left: 0,
                behavior: "smooth",
            });
            el.classList.add(activeClass);

            // ce timeout sert à ne pas jouer l'animation "is-active" lors du scrollTo
            setTimeout(() => {
                isClicked = false;
            }, 500);
        };

        if (
            filterOffsetTop > sectionOffsetTop - sectionOffset &&
            !isClicked &&
            filterOffsetTop < sectionOffsetTop + sectionHeight - sectionOffset
        ) {
            const activeFilter = stickyMenu.querySelector("." + activeClass);
            if (activeFilter) {
                activeFilter.classList.remove(activeClass);
            }
            if (!el.classList.contains(activeClass)) {
                el.classList.add(activeClass);
            }

            const sliderIndex: any = el.dataset.sliderIndex!;

            swiper.slideTo(sliderIndex, 0);
        }
    });
});
