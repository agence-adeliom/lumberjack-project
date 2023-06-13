import Alpine from "alpinejs";

document.addEventListener("alpine:init", () => {
    Alpine.data("initAccordion", () => {
        const activeClass = "is-active";
        return {
            setMaxHeight(content: HTMLElement, height = 0) {
                content.style.maxHeight = `${height}px`;
            },

            getContent(index: number) {
                const container = `container${index}`;
                return this.$refs[container];
            },

            closeEl(item: HTMLElement) {
                const index = item.dataset.index;
                item.classList.remove(activeClass);
                const content = this.getContent(index);

                this.setMaxHeight(content);
            },

            openEl(item: HTMLElement) {
                const index = item.dataset.index;
                const content = this.getContent(index);
                if (item.className.includes(activeClass)) {
                    item.classList.remove(activeClass);
                    this.setMaxHeight(content);
                } else {
                    const contentHeight = content.scrollHeight;
                    item.classList.add(activeClass);

                    this.setMaxHeight(content, contentHeight);
                }
            },
        };
    });
});
