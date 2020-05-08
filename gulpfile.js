/**
 * Gulpfile.
 *
 * Gulp with WordPress.
 *
 * Implements:
 *      1. Live reloads browser with BrowserSync.
 *      2. CSS: Sass to CSS conversion, error catching, Autoprefixing, Sourcemaps,
 *         CSS minification, and Merge Media Queries.
 *      3. JS: Concatenates & uglifies Vendor and Custom JS files.
 *      4. Images: Minifies PNG, JPEG, GIF and SVG images.
 *      5. Watches files for changes in CSS or JS.
 *      6. Watches files for changes in PHP.
 *      7. Corrects the line endings.
 *      8. InjectCSS instead of browser page reload.
 *      9. Generates .pot file for i18n and l10n.
 *
 * @tutorial https://github.com/ahmadawais/WPGulp
 * @author Ahmad Awais <https://twitter.com/MrAhmadAwais/>
 */

/**
 * Load WPGulp Configuration.
 *
 * TODO: Customize your project in the wpgulp.js file.
 */
const config = require( './wpgulp.config.js' );

/**
 * Load Plugins.
 *
 * Load gulp plugins and passing them semantic names.
 */
const gulp = require( 'gulp' ); // Gulp of-course.
const parameterized = require('gulp-parameterized');

// CSS related plugins.
const sass = require( 'gulp-sass' ); // Gulp plugin for Sass compilation.

// JS related plugins.
const uglify = require('gulp-uglify-es').default; // Minifies JS files.

// HTML related plugins
const htmlbeautify = require('gulp-html-beautify'); // Beautify HTML/PHP files

// Image related plugins.
const imagemin = require( 'gulp-imagemin' ); // Minify PNG, JPEG, GIF and SVG images with imagemin.

// Utility related plugins.
const concat = require( 'gulp-concat' ); // Concatenates files.
const lineec = require( 'gulp-line-ending-corrector' ); // Consistent Line Endings for non UNIX systems. Gulp Plugin for Line Ending Corrector (A utility that makes sure your files have consistent line endings).
const notify = require( 'gulp-notify' ); // Sends message notification to you.

const fs = require('fs'); // File System

/*
	VolunteerMatch Admin Styles
*/
gulp.task('admin-css', parameterized( async function (_) {
	var noFlags = undefined === _.params.length || _.params.all;

	if ( _.params.prod ) {
		buildAdminStyles(true);
	}

	if ( _.params.dev ) {
		buildAdminStyles(false);
	}

	if( noFlags ){
		buildAdminStyles(true);
		buildAdminStyles(false);
	}
}));

/*
	VolunteerMatch FrontEnd Styles
*/
gulp.task('frontend-css', parameterized( async function (_) {
	var noFlags = undefined === _.params.length || _.params.all;

	if ( _.params.prod ) {
		buildFrontEndStyles(SVGComponentTransferFunctionElement);
	}

	if ( _.params.dev ) {
		buildFrontEndStyles(false);
	}

	if( noFlags ){
		buildFrontEndStyles(false);
		buildFrontEndStyles(true);
	}

}));

/*
	VolunteerMatch Admin JavaScript
*/
gulp.task('admin-js', parameterized( async function (_) {
	var noFlags = undefined === _.params.length || _.params.all;

	if ( _.params.prod ) {
		buildAdminJS(true);
	}

	if ( _.params.dev ) {
		buildAdminJS(false);
	}

	if( noFlags ){
		buildAdminJS(true);
		buildAdminJS(false);
	}
}));

/*
	VolunteerMatch FrontEnd JavaScript
*/
gulp.task('frontend-js', parameterized( async function (_) {
	var noFlags = undefined === _.params.length || _.params.all;

	if ( _.params.prod ) {
		buildFrontEndJS(true);
	}

	if ( _.params.dev ) {
		buildFrontEndJS(false);
	}

	if( noFlags ){
		buildFrontEndJS(true);
		buildFrontEndJS(false);
	}
}));

/*
	BootStrap JS Script
*/
gulp.task('bootstrap-js', parameterized( async function (_) {

	if ( _.params.prod ) {
		buildBootStrapJS(true);
	}

	if ( _.params.dev ) {
		buildBootStrapJS(false);
	}

	if( buildAll(_.params) ){
		buildBootStrapJS(true);
		buildBootStrapJS(false);
	}
}));


gulp.task('beautify', parameterized(async function(_) {
	var options = {indentSize: 2};
	var noFlags = ! Object.getOwnPropertyNames(_.params).length || undefined === _.params.file;
	var src = ['*.php', '*.html'];

	if( ! noFlags ){
		src = _.params.file;
	}
	
	gulp.src(src, {base: './'})
	  .pipe(htmlbeautify(options))
	  .pipe(gulp.dest('./'));
	
}));

/*
	VolunteerMatch Build All CSS/JS and Beautify
*/
gulp.task('build', parameterized(async function(_){
	var noFlags = ! Object.getOwnPropertyNames(_.params).length || undefined !== _.params.all;
	var versionNum = undefined !== _.params.ver ? _.params.ver : false;

	if ( _.params.prod ) {
		// Build Admin Styles
		buildAdminStyles(true);

		// Build Version Styles
		buildFrontEndStyles(true);

		// Build Admin JS
		buildAdminJS(true);

		// Build Frontend JS
		buildFrontEndJS(true);

		// Build Bootstrap JS
		buildBootStrapJS(true);
	}

	if ( _.params.dev ) {
		// Build Admin Styles
		buildAdminStyles(false);

		// Build Front End Styles
		buildFrontEndStyles(false);

		// Build Admin JS
		buildAdminJS(false);

		// Build Frontend JS
		buildFrontEndJS(false);

		// Build Bootstrap JS
		buildBootStrapJS(false);

	}

	if( noFlags ){
		// Build Admin Styles
		buildAdminStyles(true);
		buildAdminStyles(false);
		
		// Build Front End Styles
		buildFrontEndStyles(true);
		buildFrontEndStyles(false);

		// Build Admin JS
		buildAdminJS(true);
		buildAdminJS(false);

		// Build Frontend JS
		buildFrontEndJS(true);
		buildFrontEndJS(false);

		
		// Build Bootstrap JS
		buildBootStrapJS(true);
		buildBootStrapJS(false);
	}

}));


// Gulp Task Functions
async function buildAdminStyles( min = false){
	var buildOutputStyle = min ? 'compressed' : 'expanded';
	var minified = min ? '.min' : '';

	if( ! config.adminCSS.length )
		return;

	return gulp.src(config.adminCSS, { allowEmpty: true } )
		.pipe(
			sass({
				outputStyle: buildOutputStyle,
			})
		)
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe(concat('admin' + minified + '.css')) // compiled file
		.pipe( notify({ title: '✅  VolunteerMatch Admin Styles', message: '<%= file.relative %> was created successfully.', onLast: true }) )
		.pipe(gulp.dest('./css/'));
}

async function buildFrontEndStyles( min = false ){
	var buildOutputStyle = min ? 'compressed' : 'expanded';
	var minified = min ? '.min' : '';

	if( ! config.frontendCSS.length )
		return;

		return gulp.src(config.frontendCSS, { allowEmpty: true } )
			.pipe(
				sass({
					outputStyle: buildOutputStyle,
				})
			)
			.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
			.pipe(concat('frontend' + minified + '.css' )) // compiled file
			.pipe( notify({ title: '✅  VolunteerMatch Front End Styles', message: '<%= file.relative %> was created successfully.', onLast: true }) )
			.pipe(gulp.dest('./css/'));
}

async function buildAdminJS( min = false){
	var minified = min ? '.min' : '';
	
	if( ! config.adminJS.length )
		return;

	let js = gulp.src(config.adminJS, { allowEmpty: true } )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe(concat('admin' + minified + '.js')) // compiled file
		.pipe( notify({ title: '✅  VolunteerMatch Admin JavaScript', message: '<%= file.relative %> was created successfully.', onLast: true }) )


	if( min ){
		js = js.pipe(uglify());
	}

	return js.pipe(gulp.dest('./js/'));
}

async function buildFrontEndJS( min = false){
	var minified = min ? '.min' : '';

	if( ! config.frontendJS.length )
		return;

	let js = gulp.src(config.frontendJS, { allowEmpty: true } )
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe(concat('frontend' + minified + '.js')) // compiled file
		.pipe( notify({ title: '✅  VolunteerMatch Front End JavaScript', message: '<%= file.relative %> was created successfully.', onLast: true }) );

	if( min ){
		js = js.pipe(uglify());
	}

	return js.pipe(gulp.dest('./js/'));
}

async function buildBootStrapJS( min = false ){
	var minified = min ? '.min' : '';
	var t = minified ? ' Minified' : '';
	t = '✅  Bootstrap JavaScript' + t;

	if( ! config.bootStrapJS.length )
		return;

	let js = gulp.src(config.bootStrapJS)
		.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
		.pipe(concat('bootstrap' + minified + '.js')) // compiled file
		.pipe( notify({ title: t, message: '<%= file.relative %> was created successfully.', onLast: true }) )


	if( min ){
		js = js.pipe(uglify());
	}

	return js.pipe(gulp.dest('./js/'));
}

//
// DEV (Development Output)
//
gulp.task('dev', parameterized.series('frontend-css --dev', 'admin-css --dev', 'admin-js --dev', 'frontend-js --dev') );

// PROD (Minified Output)
gulp.task('prod', parameterized.series('frontend-css --prod', 'admin-css --prod', 'admin-js --prod', 'frontend-js --prod') );
