const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#318FB5', // blue-medium
                    dark: '#005086',   // blue-dark
                    light: '#B0CAC7',  // blue-light
                },
                'light-gray': '#f8f9fa',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
