const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    // .setPublicPath('/ICBT/lab_management_system/public/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')

    //Dashboard
    .addEntry('dashboard', './assets/adminDashboard/dashboard/dashboard')

    //Doctor
    .addEntry('doctor_list', './assets/adminDashboard/user/doctor/view')
    .addEntry('doctor_form', './assets/adminDashboard/user/doctor/doctorForm')

    //BackendUSer
    .addEntry('backend_users_list', './assets/adminDashboard/user/backendUser/backendUser')
    .addEntry('backend_user_form', './assets/adminDashboard/user/backendUser/backendUserForm')

    //test
    .addEntry('test_category', './assets/adminDashboard/test/category/testCategory')
    .addEntry('test', './assets/adminDashboard/test/test/test')
    .addEntry('test_form', './assets/adminDashboard/test/test/testsForm')


    //PreRequest
    .addEntry('pre_request_list', './assets/adminDashboard/preRequest/preRequest')
    .addEntry('pre_request_view', './assets/adminDashboard/preRequest/preRequestForm')

    //patientProfile
    .addEntry('patient_profile', './assets/adminDashboard/patientProfile/patientProfile')
    .addEntry('patient_appointment', './assets/adminDashboard/patientProfile/patientAppointment')
    .addEntry('test_results', './assets/adminDashboard/patientProfile/testResults')


    //Appointment
    .addEntry('appointment_form', './assets/adminDashboard/appointment/appointmentForm')
    .addEntry('appointment_list', './assets/adminDashboard/appointment/appointmentList')

    //Transaction
    .addEntry('payment_details', './assets/adminDashboard/transaction/paymentDetails/paymentDetails')
    .addEntry('payment_success', './assets/adminDashboard/transaction/paymentSuccess/paymentSuccess')
    .addEntry('payment_fail', './assets/adminDashboard/transaction/paymentFail/paymentFail')
    .addEntry('payment_cancel', './assets/adminDashboard/transaction/paymentCancel/paymentCancel')

    //TestView
    .addEntry('test_view', './assets/adminDashboard/testView/testView')
    .addEntry('upload_report','./assets/adminDashboard/testView/uploadReport')

    //patient
    .addEntry('patient_list','./assets/adminDashboard/user/patient/patientList')


    //web
    .addEntry('web_landing_page', './assets/webPage/landingPage/landingPage')
    .addEntry('user_registration', './assets/webPage/userRegistration/userRegistration')





    .copyFiles([
        {from: './node_modules/ckeditor4/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './node_modules/ckeditor4/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/skins', to: 'ckeditor/skins/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/vendor', to: 'ckeditor/vendor/[path][name].[ext]'}
    ])


    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
