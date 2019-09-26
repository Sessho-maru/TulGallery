import Vue from 'vue';
import VueRouter from 'vue-router';
import ExampleComponent from './components/ExampleComponent';

Vue.use(VueRouter); // use VueRouter

export default new VueRouter({
    routes: [
        {
            path: '/test',
            component: ExampleComponent
        }
    ],

    mode: 'history' // turn off backwards compatibility (for modern web browser)
});