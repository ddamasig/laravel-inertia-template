import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';

// Pinia
import {createPinia} from 'pinia'

// Vuetify
import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import {aliases, mdi} from 'vuetify/iconsets/mdi-svg'
import {VDataTable, VDataTableServer, VDataTableVirtual,} from "vuetify/labs/VDataTable";

const mainTheme = {
    dark: false,
    colors: {
        background: '#FFFFFF',
        'paper-dark': '#233044',
        'paper-dark-text': '#979ea9',
        'muted': '#6C737F',
        surface: '#FFFFFF',
        primary: '#376fd0',
        'primary-faded': '#e9efff',
        'primary-lighten-1': '#eeeeee',
        'primary-darken-1': '#3700B3',
        secondary: '#03DAC6',
        'secondary-darken-1': '#018786',
        'error': '#ad3636',
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

const pinia = createPinia()


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)});

        // Sentry
        // Sentry.init({
        //     app,
        //     dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
        //     tracesSampleRate: 1.0,
        //     logErrors: true,
        // });

        app.use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(pinia)
            .mount(el);

        return app
    },
    progress: {
        color: mainTheme.colors.primary,
    },
});
