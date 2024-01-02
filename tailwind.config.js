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
                "theme-primary": "#DC3545",
                "theme-secondary": "#112042",
                "theme-body": "#F9FAFB",
                "theme-text": "#555555",
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
