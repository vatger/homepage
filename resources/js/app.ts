import '../css/styles/globals.css';


import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'


createInertiaApp({
    // @ts-ignore
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
        showSpinner: true,
    },
}).then()
