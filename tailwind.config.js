import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/usernotnull/tall-toasts/config/**/*.php',
        './vendor/usernotnull/tall-toasts/resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            backgroundColor: {
                'primary': '#1f2937',
                'primary-hover': '#111827',
                'warning': '#fdba74',
                'warning-hover': '#fb923c',
                'danger': '#fca5a5',
                'danger-hover': '#f87171',
                'system': '#f59e0b',
                'system-hover': '#d97706',
            },
            textColor: {
                'primary': 'white',
                'warning': '#9a3412 ',
                'danger': '#991b1b',
                'system': '#f59e0b',
                'system-hover': '#d97706',
            },
            borderColor: {
                'primary': 'blue',
            },
        },
    },

    plugins: [forms, typography],
};
