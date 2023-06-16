import Alpine from "alpinejs";
import Swiper, { Navigation, SwiperOptions } from "swiper";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

document.addEventListener("alpine:init", () => {
    Alpine.data("initStickyMenu", () => {
        return {
            swiper: {},
            slideToCurrent(el: HTMLElement) {
                const sliderIndex = Number(el.dataset.sliderIndex!);
                this.swiper.slideTo(sliderIndex, 300);
            },
            init() {
                const swiperParams: SwiperOptions = {
                    // configure Swiper to use modules
                    modules: [Navigation],
                    slidesPerView: "auto",
                    spaceBetween: "24",
                    loop: false,
                    mousewheel: true,
                    navigation: {
                        nextEl: this.$refs.buttonNext,
                        prevEl: this.$refs.buttonPrev,
                        disabledClass: "opacity-0",
                    },
                };
                this.swiper = new Swiper(
                    this.$refs.swiperContainer,
                    swiperParams
                );

                this.$nextTick(() => {
                    this.swiper.init();
                });

                const stickyMenu: HTMLElement =
                    document.querySelector(".js-sticky-menu")!;
                const links: NodeListOf<HTMLElement> =
                    stickyMenu?.querySelectorAll(".js-sticky-menu-item ");
                const activeClass = "is-active";
                const sectionOffset = 80;

                let isClicked = false;
                addEventListener("scroll", () => {
                    links.forEach((el: HTMLElement) => {
                        const a: HTMLAnchorElement = el.querySelector("a")!;
                        const anchor: string = a.getAttribute("href")!;
                        const block: HTMLElement =
                            document.querySelector(anchor)!;
                        const blockTop: number = block.offsetTop;
                        const sectionOffsetTop: number =
                            block.getBoundingClientRect().top +
                            window.pageYOffset;
                        const sectionHeight: number =
                            block.getBoundingClientRect().height;
                        const filterOffsetTop: number =
                            stickyMenu.getBoundingClientRect().top +
                            window.pageYOffset;

                        el.onclick = (click: MouseEvent) => {
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

                            this.slideToCurrent(el);

                            el.classList.add(activeClass);
                            // ce timeout sert à ne pas jouer l'animation "is-active" lors du scrollTo
                            setTimeout(() => {
                                isClicked = false;
                            }, 800);
                        };

                        // Partie qui gère la classe active lors du survol des blocks
                        if (
                            filterOffsetTop >
                                sectionOffsetTop - sectionOffset &&
                            !isClicked &&
                            filterOffsetTop <
                                sectionOffsetTop + sectionHeight - sectionOffset
                        ) {
                            const activeFilter = stickyMenu.querySelector(
                                "." + activeClass
                            );
                            if (activeFilter) {
                                activeFilter.classList.remove(activeClass);
                            }
                            if (!el.classList.contains(activeClass)) {
                                el.classList.add(activeClass);
                            }
                            // Scroll vers l'item actif
                            this.slideToCurrent(el);
                        }
                    });
                });
            },
        };
    });
});
