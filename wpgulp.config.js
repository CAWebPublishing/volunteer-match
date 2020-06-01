/**
 * WPGulp Configuration File
 *
 * 1. Edit the variables as per your project requirements.
 * 2. In paths you can add <<glob or array of globs>>.
 *
 * @package WPGulp
 */


module.exports = {

	frontendCSS: [ // Frontend CSS
		'assets/scss/frontend.scss',
	], 
	frontendJS: [ // Frontend JS 
		'assets/js/volunteermatch/volunteer-match.js',
		'assets/js/volunteermatch/volunteer-match-opportunity.js',
	],  
	adminCSS:[ // WP Backend Admin CSS
		'assets/scss/admin.scss',
	],
	adminJS: [ // WP Backend Admin JS
		'assets/js/volunteermatch/admin.js',
		'assets/js/bootstrap/bootstrap.bundle.js',
	], 
	bootStrapJS: [ // Bootstrap JS
		'assets/js/bootstrap/bootstrap.bundle.js',
	], 
};
