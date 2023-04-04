//プラグイン名
const pluginName = 'vk-pattern-directory-creator';

// CSS や JS の共通パス
const assetsPath = './assets';

// モジュールをロード
const gulp = require( 'gulp' );
const sass = require( 'gulp-sass' )( require( 'sass' ) );
const uglify = require( 'gulp-uglify' );

// CSS をビルド
gulp.task( 'build:css', ( done ) => {
	gulp.src( `${ assetsPath }/src/scss/*.scss` )
		.pipe(
			sass( { outputStyle: 'compressed' } ).on( 'error', sass.logError )
		)
		.pipe( gulp.dest( `${ assetsPath }/build/css/` ) );
	done();
} );

// JS をビルド
gulp.task( 'build:js', ( done ) => {
	gulp.src( `${ assetsPath }/src/js/*.js` )
		.pipe( uglify() )
		.pipe( gulp.dest( `${ assetsPath }/build/js/` ) );
	done();
} );

// 一気にビルド
gulp.task( 'build', gulp.series( 'build:css', 'build:js' ) );

// 監視
gulp.task( 'watch', () => {
	gulp.watch(
		[ `${ assetsPath }/src/scss/*.scss`, `${ blocksPath }/src/*.scss` ],
		gulp.task( 'build:css' )
	);
	gulp.watch( [ `${ assetsPath }/src/js/*.js` ], gulp.task( 'build:js' ) );
} );
