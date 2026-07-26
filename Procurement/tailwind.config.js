import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    DEFAULT: '#16265B',
                    50: '#eef0fa',
                    100: '#dfe3f5',
                    600: '#1f3372',
                    700: '#182a63',
                    800: '#16265B',
                    900: '#0f1a42',
                },
                brand: {
                    green: '#1FCB88',
                    greenDark: '#12A86F',
                    blue: '#2F4CDD',
                    orange: '#F5941F',
                    red: '#EF4B4B',
                },
            },
            boxShadow: {
                card: '0 2px 10px 0 rgba(16, 24, 64, 0.06)',
            },
        },
    },

    plugins: [forms],
};