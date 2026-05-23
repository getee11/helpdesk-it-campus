import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Aeonik Pro', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#9fe870',
                    active: '#cdffad',
                    pale: '#e2f6d5',
                },
                ink: '#0e0f0c',
                body: '#454745',
                mute: '#868685',
                canvas: {
                    DEFAULT: '#ffffff',
                    soft: '#e8ebe6',
                    dark: '#000000',
                },
                positive: '#2ead4b',
                warning: '#ffd11a',
                negative: '#d03238',
                surface: {
                    elevated: '#16181a',
                }
            },
            borderRadius: {
                'xl': '24px',
                '2xl': '32px',
            }
        },
    },

    plugins: [forms],
};
