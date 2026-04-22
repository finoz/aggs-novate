import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { readFileSync, readdirSync, writeFileSync, existsSync } from 'fs';
import { resolve } from 'path';

function svgSpritePlugin() {
    const iconsDir = resolve(__dirname, 'resources/icons');
    const outputFile = resolve(__dirname, 'resources/views/partials/icons-sprite.blade.php');

    function generateSprite() {
        if (!existsSync(iconsDir)) return;

        const files = readdirSync(iconsDir).filter(f => f.endsWith('.svg'));

        const symbols = files.map(file => {
            const name = file.replace('.svg', '');
            const raw = readFileSync(resolve(iconsDir, file), 'utf-8');
            const inner = raw
                .replace(/<svg[^>]*>/i, '')
                .replace(/<\/svg>/i, '')
                .replace(/fill="(#0+|black|#000000)"/gi, 'fill="currentColor"')
                .trim();
            return `  <symbol id="icon-${name}" viewBox="0 0 48 48">\n    ${inner}\n  </symbol>`;
        }).join('\n');

        const sprite = `<svg hidden aria-hidden="true" style="display:none" xmlns="http://www.w3.org/2000/svg">\n${symbols}\n</svg>\n`;
        writeFileSync(outputFile, sprite);
    }

    return {
        name: 'svg-sprite',
        buildStart() {
            generateSprite();
        },
        configureServer(server) {
            server.watcher.add(iconsDir);
            server.watcher.on('add', (path) => { if (path.startsWith(iconsDir)) generateSprite(); });
            server.watcher.on('change', (path) => { if (path.startsWith(iconsDir)) generateSprite(); });
            server.watcher.on('unlink', (path) => { if (path.startsWith(iconsDir)) generateSprite(); });
        },
    };
}

export default defineConfig({
    plugins: [
        svgSpritePlugin(),
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/ts/app.ts',
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Usa il compilatore SCSS moderno
                api: 'modern-compiler',
            },
        },
    },
});
