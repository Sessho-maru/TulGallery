import Vue from 'vue';
import router from './router';
import App from './components/App'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import VueCkeditor from 'vue-ckeditor5'

const options = {
  editors: {
    classic: ClassicEditor
  },
  name: 'ckeditor'
}

Vue.use(VueCkeditor.plugin, options);
require('./bootstrap');

const app = new Vue({
    el: '#app',

    components: {
        App
    },

    router
});