import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import { glob } from "glob";
import { resolve } from "node:path";

const projectRoot = process.cwd();

const moduleAssets = [
    ...glob.sync("Modules/*/resources/assets/js/app.js"),
    ...glob.sync("Modules/*/resources/assets/sass/app.scss"),
];

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                ...moduleAssets, // <-- this includes Inventory's app.js automatically
            ],
            refresh: true,
            fonts: [],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
    resolve: {
        alias: {
            "@inventory": resolve(projectRoot, "Modules/Inventory/resources/assets/js"),
            "@dashboard": resolve(projectRoot, "Modules/Dashboard/resources/assets/js"),
        },
    },
});
