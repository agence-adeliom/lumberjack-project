/** @type {import('tailwindcss').Config} */
module.exports = {
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
            base: ["assets/tailwind/base/**/*.{css,pcss}"], // Glob paths to your bases
            utilities: ["assets/tailwind/utilities/**/*.{css,pcss}"], // Glob paths to your utilities
            components: ["assets/tailwind/components/**/*.{css,pcss}"], // Glob paths to your components
        }),
    ],
};
