import Vue from 'vue';
import VueRouter from 'vue-router';

import ImageIndex from './views/ImageIndex'
import ImageCreate from './views/ImageCreate';
import ImageShow from './views/ImageShow';
import ImageEdit from './views/ImageEdit';

Vue.use(VueRouter); // use VueRouter

export default new VueRouter({
    routes: [
        {
            path: '/imgs',
            component: ImageIndex
        },
        {
            path: '/imgs/create',
            component: ImageCreate
        },
        {
            path: '/imgs/:id',
            component: ImageShow
        },
        {
            path: '/imgs/:id/edit',
            component: ImageEdit
        }
    ],

    mode: 'history' // turn off backwards compatibility (for modern web browser)
});