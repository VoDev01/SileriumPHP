import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(
{
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/prepare-page-for-pdf-formatting.js',
                'resources/js/hide-datepicker.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '$': 'jQuery',
            'toastr' : 'toastr',
            'StarRating' : 'star-rating.js/dist/star-rating'
        }
    }
});
