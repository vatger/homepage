import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import * as path from "path";

export default defineConfig({
  server: {
    host: "127.0.0.1",
    port: 3000,
  },

  css: {
    preprocessorOptions: {
      scss: {
        silenceDeprecations: [
          "import",
          "global-builtin",
          "color-functions",
          "if-function",
        ],
      },
    },
  },

  plugins: [
    tailwindcss(),
    laravel([
      "resources/css/app-public.css",
      "resources/css/app-admin.css",
      "resources/scss/mail.scss",
      "resources/ts/app-public.ts",
      "resources/ts/app.ts",
      "resources/ts/special/events.ts",
      "resources/ts/special/aerodrome.ts",
      "resources/ts/special/member.ts",
      "resources/ts/special/landing-typewriter.ts",
      "resources/scss/special/aerodrome-mapbox.scss",
      "resources/scss/special/leaflet.scss",
    ]),
  ],

  build: {
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes("node_modules")) {
            return id
              .toString()
              .split("node_modules/")[1]
              .split("/")[0]
              .toString();
          }
        },
        assetFileNames: function (file) {
          return file.name.includes("mail")
            ? `assets/[name].[ext]`
            : `assets/[name]-[hash].[ext]`;
        },
      },
    },
  },

  resolve: {
    alias: {
      "@": path.resolve(import.meta.dirname, "resources"),
      "~vendor": path.resolve(import.meta.dirname, "vendor"),
    },
  },
});
