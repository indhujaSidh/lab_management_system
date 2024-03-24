<!-- jQuery  -->
const $ = require('jquery');
global.$ = global.jQuery = global.jquery = $;

//fortawesome
require('@fortawesome/fontawesome-free/css/all.min.css')
require('@fortawesome/fontawesome-free/js/all')

//web styles
require('../styles/web.css')
require('../styles/bootstrap.min.css')

// CommonJS
const Swal = require('sweetalert2')
global.Swal = Swal;


