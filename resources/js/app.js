import Vue from 'vue';
import VueRouter from 'vue-router';
import VueMeta from 'vue-meta';
import App from './App.vue';
import routes from './routes';
import store from './store';

import './queries';
import './../sass/app.scss';
import './queries';
import './helper';

import 'sweetalert2/dist/sweetalert2.min.css';
import 'vue-multiselect/dist/vue-multiselect.min.css';

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import VueSweetalert2 from 'vue-sweetalert2';
import Multiselect from 'vue-multiselect';
import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueRouter);
Vue.use(VueMeta);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueSweetalert2);

Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyBBx3muQLAgYjvyMZaiZSGu5EpE0Ywp_E0',
      libraries: 'places', // This is required if you use the Autocomplete plugin
      // OR: libraries: 'places,drawing'
      // OR: libraries: 'places,drawing,visualization'
      // (as you require)
   
      //// If you want to set the version, you can do so:
      // v: '3.26',
    },
   
    //// If you intend to programmatically custom event listener code
    //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
    //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
    //// you might need to turn this on.
    // autobindAllEvents: false,
   
    //// If you want to manually install components, e.g.
    //// import {GmapMarker} from 'vue2-google-maps/src/components/marker'
    //// Vue.component('GmapMarker', GmapMarker)
    //// then disable the following:
    // installComponents: true,
  })

Vue.component('multiselect', Multiselect);

const router = new VueRouter({
    routes,
    mode: 'history',
});

router.beforeEach((to, from, next) => {
    if (to.matched.some((m) => m.meta.userAuth === false)) {
        let userApiToken = store.state.userApiToken;

        if (userApiToken) {
            next('/users');
        }

        next();
        return;
    }

    if (to.matched.some((m) => m.meta.userAuth === true)) {
        const accesstoken = store.state.userApiToken;

        if (accesstoken) {
            next();
        } 

        if (accesstoken == '') {
            next('/');
        }

        return;
    }

    next();
    return; 
});

Vue.prototype.$appEvents = new Vue();

new Vue({
    router,
    store,
    render: (h) => h(App),
}).$mount('#app');
