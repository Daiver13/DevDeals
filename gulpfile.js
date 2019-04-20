/*******************************************************************************
1. DEPENDENCIES
*******************************************************************************/
 
var gulp = require('gulp');                             // gulp core
    sass = require('gulp-sass'),                        // sass compiler
    uglify = require('gulp-uglify'),                    // uglifies the js
    jshint = require('gulp-jshint'),                    // check if js is ok
    rename = require("gulp-rename");                    // rename files
    concat = require('gulp-concat'),                    // concatinate js
    notify = require('gulp-notify'),                    // send notifications to osx
    plumber = require('gulp-plumber'),                  // disable interuption

    stylish = require('jshint-stylish'),                // make errors look good in shell
    sourcemaps = require('gulp-sourcemaps'),

    minifycss = require('gulp-minify-css'),             // minify the css files
    autoprefixer = require('gulp-autoprefixer'),        // sets missing browserprefixes
    bourbon = require('node-bourbon'),
    gutil = require('gulp-util'),
    filesize = require('gulp-filesize'),
    browserSync = require('browser-sync'),
    reload = browserSync.reload;

/*******************************************************************************
2. FILE DESTINATIONS (RELATIVE TO ASSSETS FOLDER)
*******************************************************************************/

var target = {
    sass_gen      : 'assets/sass/general.scss',
    sass_src      : 'assets/sass/*.scss',
    css_dest      : 'assets/css/',
    css_dest_name : 'main.css',
    js_src        : 'assets/js/*.js',
    js_dest       : 'assets/js/min',
    js_dest_name  : 'scripts.js',
    files_dest    : '**/*.php',
    project_dir   : 'localhost'
};

/*******************************************************************************
3. SOME SETTINGS
*******************************************************************************/

var plumberError = function (err) {
    gutil.beep();
    console.error(err);
};

gulp.task('browserReload', function() {
    return browserSync.reload();
});

gulp.task('browser-sync', function() {
    return browserSync.init({
        proxy: 'http://localhost:8888/remember/'
    });
});



/*******************************************************************************
4. SASS TASK
*******************************************************************************/

gulp.task('sass', function() {
  return gulp.src(target.sass_gen)
    .pipe(plumber(function (error) {
        plumberError(error);
        this.emit('end');
    }))
    .pipe(sass({ includePaths: require('node-bourbon').includePaths, sourceComments: 'normal', errLogToConsole: true }))
    .pipe(autoprefixer(
            'last 2 version',
            '> 1%',
            'ie 8',
            'ie 9',
            'ios 6',
            'android 4'
        ))
    .pipe(rename(target.css_dest_name))
    .pipe(gulp.dest(target.css_dest))
    .pipe(browserSync.reload({stream:true}))
    .pipe(notify({message: 'SCSS processed!'}));
});

/*******************************************************************************
 4. SASS MINIFY TASK
 *******************************************************************************/

gulp.task('minsass', function() {
    return gulp.src(target.sass_gen)
        .pipe(plumber(function (error) {
            plumberError(error);
            this.emit('end');
        }))
        .pipe(sass({ includePaths: require('node-bourbon').includePaths, sourceComments: 'normal', errLogToConsole: true }))
        .pipe(autoprefixer(
            'last 2 version',
            '> 1%',
            'ie 8',
            'ie 9',
            'ios 6',
            'android 4'
        ))
        .pipe(minifycss())
        .pipe(rename(target.css_dest))
        .pipe(gulp.dest(target.css_dest))
        .pipe(filesize())
        .pipe(notify({message: 'SCSS processed!'}));
});

/*******************************************************************************
5. JS MINIFY TASKS
*******************************************************************************/
 
// Конкатенация и минификация файлов
gulp.task('minjs', function(){
    gulp.src([target.js_src])
        .pipe(plumber())
        .pipe(jshint())                                 
        .pipe(jshint.reporter(stylish))
        .pipe(filesize())
        .pipe(uglify({
            mangle: false,
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(target.js_dest))
        .pipe(filesize())
        .pipe(notify({
          message: "Generated file: <%= file.relative %> @ <%",
          templateOptions: {
            date: new Date()
          }
        }))
});

/*******************************************************************************
6. JS TASKS
*******************************************************************************/

gulp.task('js', function(){
    return gulp.src([target.js_src])
        .pipe(plumber())
        .pipe(jshint())                                 
        .pipe(jshint.reporter(stylish))
        .pipe(uglify({
            mangle: false,
        }))
        .pipe(rename(target.js_dest_name))
        .pipe(gulp.dest(target.js_dest))
        .pipe(notify({
          message: "Generated file: <%= file.relative %>",
          templateOptions: {
            date: new Date()
          }
        }))
        .pipe(browserSync.reload({stream:true, once: true}));
});
 
/*******************************************************************************
8. GULP WATCH
*******************************************************************************/
 
gulp.task('watch', function(){
    gulp.watch(target.sass_src, function(){
        gulp.run('sass');
    });
    gulp.watch(target.js_src, function(){
        gulp.run('js');
    });
    gulp.watch(target.files_dest).on('change', reload);
});

/*******************************************************************************
9. GULP TASKS
*******************************************************************************/

gulp.task('default', function(){
    gulp.run(
        'browser-sync',
        'sass',
        'js',
        'watch'
    );
});

gulp.task('prod', function(){
    gulp.run(
        'minsass',
        'minjs'
    );
});
