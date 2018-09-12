let mix = require('laravel-mix');
 // mix.scripts([
// // 	/*-- Scripts de la plantilla --*/
//  	'resources/assets/js/jquery/jquery-2.1.1.js',
//   	'resources/assets/js/bootstrap/bootstrap.min.js',
//   	'resources/assets/js/plugins/metisMenu/jquery.metisMenu.js',
//   	'resources/assets/js/plugins/slimscroll/jquery.slimscroll.min.js',
//   	'resources/assets/js/inspinia.js',
//   	'resources/assets/js/plugins/pace/pace.min.js',

//   	'resources/assets/js/plugins/toastr/toastr.min.js',
//   	'resources/assets/js/plugins/iCheck/icheck.min.js',
// 	'resources/assets/js/plugins/dataTables/datatables.min.js',
//  	'resources/assets/js/plugins/ladda/spin.min.js', 
//  	'resources/assets/js/plugins/ladda/ladda.min.js',
//  	'resources/assets/js/plugins/ladda/ladda.jquery.min.js',
//  	'resources/assets/js/plugins/chosen/chosen.jquery.js',
//  	'resources/assets/js/plugins/fullcalendar/moment.min.js',
//  	'resources/assets/js/plugins/datapicker/bootstrap-datepicker.js',
//  	'resources/assets/js/plugins/daterangepicker/daterangepicker.js',
//  	'resources/assets/js/plugins/select2/select2.full.min.js',
//     'resources/assets/js/plugins/dropzone/dropzone.js',
//  	'resources/assets/js/plugins/bootstrapToogle/bootstrap-toggle.min.js',
// // 	// 'resources/assets/js/plugins/switchery/switchery.js',------------------------ delete
// // 	// 'resources/assets/js/plugins/formatCurrency/jquery.formatCurrency-1.4.0.min.js',------------------------ delete
// // 	// 'resources/assets/js/plugins/jqueryFiler/jquery.filer.min.js',------------------------ delete
// // 	// 'resources/assets/js/plugins/jqueryFiler/custom.js',------------------------ delete
//  	'resources/assets/js/plugins/summernote/summernote.min.js',
//  	'resources/assets/js/plugins/bootstrapTagsInput/bootstrap-tagsinput.js'
//  	], 'public/js/plantilla.js');

 // mix.styles([
// 	/*-- Estilos de la plantilla --*/
//  	'resources/assets/css/bootstrap.min.css',
//  	'resources/assets/css/plugins/toastr/toastr.min.css',
//  	'resources/assets/css/animate.css',
//  	'resources/assets/css/style.css',
//  	'resources/assets/css/font-awesome/css/font-awesome.css',
//  	'resources/assets/css/plugins/dataTables/datatables.min.css',
//  	'resources/assets/css/plugins/iCheck/custom.css',
//  	'resources/assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',
//  	'resources/assets/css/plugins/jasny/jasny-bootstrap.min.css',
//  	'resources/assets/css/plugins/ladda/ladda-themeless.min.css',
//  	'resources/assets/css/plugins/chosen/chosen.css',
//  	'resources/assets/css/plugins/datapicker/datepicker3.css',
//  	'resources/assets/css/plugins/daterangepicker/daterangepicker-bs3.css',
//  	'resources/assets/css/plugins/bootstrapToogle/bootstrap-toggle.min.css',
// // 	// 'resources/assets/css/plugins/switchery/switchery.css',------------------------ delete
//  	'resources/assets/css/plugins/select2/select2.min.css',
// // 	// 'resources/assets/css/plugins/dropzone/dropzone.css',------------------------ delete
// // 	// 'resources/assets/css/hoverEfects/css/hover.css',------------------------ delete
//  	'resources/assets/css/plugins/summernote/summernote-bs3.css',
//  	'resources/assets/css/plugins/summernote/summernote.css',
//  	'resources/assets/css/plugins/bootstrapTagsInput/bootstrap-tagsinput.css',
//  	], 'public/css/plantilla.css');




mix.js('resources/assets/js/app.js' , 'public/js');
mix.scripts(['resources/assets/js/main.js'] , 'public/js/main.js');
mix.styles(['resources/assets/css/main.css'] , 'public/css/main.css');
mix.copyDirectory('resources/assets/js/templates', 'public/js/templates');




 // mix.copyDirectory('resources/assets/js/plugins', 'public/js/plugins');
 // mix.copyDirectory('resources/assets/css/plugins', 'public/css/plugins');
 // mix.copyDirectory('resources/assets/img', 'public/img');
 // mix.copyDirectory('resources/assets/fonts', 'public/fonts');
 // mix.copyDirectory('resources/assets/css/font-awesome', 'public/css/font-awesome');
 // mix.copyDirectory('resources/assets/css/hoverEfects', 'public/css/hoverEfects');
 // mix.copyDirectory('resources/assets/css/patterns', 'public/css/patterns');
 // mix.copyDirectory('resources/assets/json', 'public/json');
 // mix.copyDirectory('resources/assets/css/plugins/iCheck/green.png', 'public/css');
 // mix.copyDirectory('resources/assets/css/plugins/iCheck/green@2x.png', 'public/css');