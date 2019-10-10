import Vue from 'vue';
import VueRouter from 'vue-router';

import ImageIndex from './views/ImageIndex'
import ImageCreate from './views/ImageCreate';
import ImageShow from './views/ImageShow';
import ImageEdit from './views/ImageEdit';
import ImageIndexWithUser from './views/ImageIndexWithUser';
import ImageIndexWithTags from './views/ImageIndexWithTags';
import Logout from './Actions/Logout';

Vue.use(VueRouter); // use VueRouter

export default new VueRouter({
    routes: [
        {
            path: '/imgs',
            component: ImageIndex
        },
        {
            path: '/imgs/index/:id',
            component: ImageIndexWithUser
        },
        {
            path: '/imgs/tags',
            name: 'Tags',
            component: ImageIndexWithTags
        },
        {
            path: '/imgs/create',
            component: ImageCreate
        },
        {
            path: '/imgs/:id',
            name: 'ImageShow',
            component: ImageShow
        },
        {
            path: '/imgs/:id/edit',
            component: ImageEdit
        },
        {
            path: '/logout',
            component: Logout
        }
    ],

    mode: 'history' // turn off backwards compatibility (for modern web browser)
});