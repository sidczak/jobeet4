import Vue from 'vue';
import App from './App';

new Vue({
    el: '#app',
    render: h => h(App)
});

// require jQuery normally
var $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('bootstrap');

$(document).ready(function() {
  $(".dropdown-toggle").dropdown();
});

import 'bootstrap/dist/css/bootstrap.min.css';