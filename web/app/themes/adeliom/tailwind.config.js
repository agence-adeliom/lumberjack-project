/** @type {import('tailwindcss').Config} */
const plugin = require('tailwindcss/plugin');
module.exports = {
    darkMode: 'class',
    content: ["./views/**/*.html.twig", "./assets/**/*.{js,ts,jsx,tsx}"],
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: "1rem",
                sm: "2.5rem",
                md: "2.25rem",
                lg: "2.75rem",
                xl: "2.5rem",
                "2xl": "3rem",
            },
        },
        extend: {
            colors: {},
        },
    },
    corePlugins: {
        float: false,
        clear: false,
    },
    plugins: [
        require("tailwind-css-extensions")({
            base: ["assets/styles/tailwind/base/**/*.{css,pcss}"], // Glob paths to your bases
            utilities: ["assets/styles/tailwind/utilities/**/*.{css,pcss}"], // Glob paths to your utilities
            components: ["assets/styles/tailwind/components/**/*.{css,pcss}"], // Glob paths to your components
        }),
        // Ajout de variants custom
        plugin(function ({ addVariant }) {
            addVariant('is-active', ['&.is-active', '.is-active &']);
        }),
    ],
};
