import Vue from 'vue';
import VueRouter from 'vue-router';
import ExampleComponent from './components/ExampleComponent';
import ImageCreate from './views/ImageCreate';

Vue.use(VueRouter); // use VueRouter

export default new VueRouter({
    routes: [
        {
            path: '/',
            component: ExampleComponent
        },
        {
            path: '/imgs/create',
            component: ImageCreate
        }
    ],

    mode: 'history' // turn off backwards compatibility (for modern web browser)
});