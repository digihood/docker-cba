import { defineConfig } from 'vite'

export default defineConfig({
    plugins: [],
    base: '/wp-content/themes/digihood-vite/assets-minified/',
    build: {
        manifest: true,
        minify: true,
        write: true,
        rollupOptions: {
            input: {
                'app': __dirname+'/assets/scripts/js/app.js',
                'main': __dirname+'/assets/styles/style.css',
                'slideout-menu': __dirname+'/assets/styles/specific-css/slideout-menu.css', 
                'admin': __dirname+'/assets/scripts/specific-scripts/admin.js',
                'admin-css': __dirname + '/assets/styles/specific-css/admin.css',
                'ajax-pagination': __dirname+'/assets/scripts/specific-scripts/ajax-pagination.js',
                'cookiebanner': __dirname+'/assets/scripts/specific-scripts/cookiebanner.js',
                'cookiebanner-css': __dirname + '/assets/styles/specific-css/cookiebanner.css',
            },
            output: {
                dir: __dirname+'/assets-minified/',
                entryFileNames: `scripts/[name].js`,
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.match(/\.(woff2?|eot|ttf|otf)$/)) {
                        return `fonts/[name][extname]`;
                    }
                    if (assetInfo.name.endsWith('.css')) {
                        return `styles/[name][extname]`;
                    }
                    return `assets/[name][extname]`;
                }
            }
        }
    },
    server: {
        cors: true,
        https: false,
        open: false,
        strictPort: true,
        port: 3000,
        hmr: true,
    }
})
