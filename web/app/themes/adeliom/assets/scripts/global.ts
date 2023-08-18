import '@scripts/navigations/menu';
import '@scripts/navigations/keyboard';
import LazySizes from 'lazysizes';
import 'lazysizes/plugins/blur-up/ls.blur-up';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

LazySizes.cfg.lazyClass = 'lazyload';
LazySizes.cfg.blurupMode = 'always';

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});
