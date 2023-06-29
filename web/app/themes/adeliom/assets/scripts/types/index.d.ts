import { Alpine as AlpineType } from "alpinejs";

declare module "*.jpg";
declare module "*.svg";
declare module "*.png";

declare module "glightbox";

declare global {
    interface Window {
        Alpine: AlpineType;
    }
}
