import Alpine from "alpinejs";
const isMultiple = false;
document.addEventListener("alpine:init", () => {
    Alpine.data("initAccordions", () => ({
        active: [],
        setMaxHeight(content, height = 0) {
            content.style.maxHeight = height + "px";
        },

        getContent(id) {
            return this.$refs["container-" + id];
        },

        isOpen(id) {
            return this.active.indexOf(id) !== -1;
        },

        close(id) {
            this.active = this.active.filter((accordion) => accordion !== id);
            const content = this.getContent(id);
            this.setMaxHeight(content);
        },

        closeAll() {
            this.active.forEach((el) => {
                this.close(el);
            });
        },

        open(id) {
            const content = this.getContent(id);
            if (this.isOpen(id)) {
                this.close(id);
            } else {
                if (!isMultiple) {
                    this.closeAll();
                }
                this.active.push(id);
                const contentHeight = content.scrollHeight;
                this.setMaxHeight(content, contentHeight);
            }
        },
    }));
});
