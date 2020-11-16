import Vue from 'vue';
window.Vue = Vue;

import axios from 'axios';
window.axios = axios;

let token = document.head.querySelector('meta[name="csrf-token"]');

axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': token.content
};
