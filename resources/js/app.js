import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
// import { aliases, fa } from 'vuetify/iconsets/fa'
// import '@fortawesome/fontawesome-free/css/all.css'
import { aliases, mdi } from 'vuetify/iconsets/mdi-svg'
import {
    VDataTable,
    VDataTableServer,
    VDataTableVirtual,
} from "vuetify/labs/VDataTable";

const mainTheme = {
    dark: false,
    colors: {
        background: '#FFFFFF',
        'paper-dark': '#1c2536',
        'paper-dark-text': '#979ea9',
        'muted': '#6C737F',
        surface: '#FFFFFF',
        primary: '#6265ef',
        'primary-darken-1': '#3700B3',
        secondary: '#03DAC6',
        'secondary-darken-1': '#018786',
        error: '#B00020',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FB8C00',
    },
}

const vuetify = createVuetify({
    components: {
        ...components,
        VDataTable,
        VDataTableServer,
        VDataTableVirtual,
    },
    directives,
    theme: {
        defaultTheme: 'mainTheme',
        themes: {
            mainTheme,
        },
    },
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
})

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
