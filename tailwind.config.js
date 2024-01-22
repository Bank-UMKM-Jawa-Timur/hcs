const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                "plus-jakarta-sans": '"Plus Jakarta Sans", sans-serif',
            },
            colors: {
                "theme-primary": "#0770CD",
                "theme-secondary": "#112042",
                "theme-body": "#F9FAFB",
                "theme-text": "#555555",
                pdf: "#EC0F02",
                excel: "#107C41",
            },
        },
    },

    plugins: [require("tailwindcss/nesting")],
};
