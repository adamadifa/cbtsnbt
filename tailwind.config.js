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
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                indigo: {
                    50: '#f4f6f8',
                    100: '#e5ebf0',
                    200: '#c6d4e1',
                    300: '#9bb6cc',
                    400: '#6991b1',
                    500: '#466c8e',
                    600: '#0a2240', // Logo primary navy
                    750: '#091f3a',
                    700: '#081c35',
                    800: '#06162a',
                    900: '#040f1f',
                    950: '#020914',
                },
                violet: {
                    50: '#faf8f5',
                    100: '#f3eee6',
                    200: '#e6dbcd',
                    300: '#d5c2ac',
                    400: '#c0a587',
                    500: '#dfceba', // Logo accent warm beige/tan
                    600: '#a38465',
                    700: '#886c52',
                    800: '#6f5641',
                    900: '#5c4635',
                    950: '#34261c',
                },
            },
            borderRadius: {
                'xl': '12px',
                '2xl': '16px',
                '3xl': '24px',
            },
        },
    },

    plugins: [forms],
};
