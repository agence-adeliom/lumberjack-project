import "@scripts/navigations/menu";
import "@scripts/navigations/keyboard";

import Alpine from "alpinejs";

window.Alpine = Alpine;
document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});
