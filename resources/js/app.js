import Vue from 'vue';
import router from './router';
import ExampleComponent from './components/ExampleComponent';
require('./bootstrap');

const app = new Vue({
    el: '#app',
    components:{
        ExampleComponent
    },
    router
});
