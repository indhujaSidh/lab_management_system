<!-- jQuery  -->
const $ = require('jquery');
global.$ = global.jQuery = global.jquery = $;

<!-- Vendor CSS Files -->
require('../includes/nice-admin/vendor/bootstrap/css/bootstrap.min.css');
require('../includes/nice-admin/vendor/bootstrap-icons/bootstrap-icons.css');
require('../includes/nice-admin/vendor/boxicons/css/boxicons.min.css');
require('../includes/nice-admin/vendor/quill/quill.snow.css');
require('../includes/nice-admin/vendor/quill/quill.bubble.css');
require('../includes/nice-admin/vendor/remixicon/remixicon.css');

<!-- Template Main CSS File -->
require('../includes/nice-admin/css/style.css');
require('../styles/app.css');

<!-- Vendor JS Files -->
require('../includes/nice-admin/vendor/bootstrap/js/bootstrap.bundle.min.js');
require('../includes/nice-admin/vendor/quill/quill.min.js');
require('../includes/nice-admin/vendor/tinymce/tinymce.min.js');
require('../includes/nice-admin/vendor/php-email-form/validate.js');

<!-- Template Main JS File -->
require('../includes/nice-admin/js/main.js');


//fortawesome
require('@fortawesome/fontawesome-free/css/all.min.css')
require('@fortawesome/fontawesome-free/js/all')

// CommonJS
const Swal = require('sweetalert2')
global.Swal = Swal;


