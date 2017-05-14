var args            = require('yargs').argv;
var autoprefixer    = require('gulp-autoprefixer');
var babel           = require('gulp-babel');
var bump            = require('gulp-bump');
var checktextdomain = require('gulp-checktextdomain');
var concat          = require('gulp-concat');
var cssnano         = require('gulp-cssnano');
var gulp            = require('gulp');
var gulpif          = require('gulp-if');
var jshint          = require('gulp-jshint');
var mergeStream     = require('merge-stream');
var plumber         = require('gulp-plumber');
var potomo          = require('gulp-potomo');
var pseudo          = require('gulp-pseudo-i18n');
var rename          = require('gulp-rename');
var runSequence     = require('run-sequence');
var sass            = require('gulp-sass');
var sort            = require('gulp-sort');
var uglify          = require('gulp-uglify');
var wpPot           = require('gulp-wp-pot');
var yaml            = require('yamljs');

var config = yaml.load('+/config.yml');

/* JSHint Task
 -------------------------------------------------- */
gulp.task('jshint', function() {
  return gulp.src(config.watch.js)
  .pipe(plumber({
    errorHandler: function(error) {
    console.log(error.message);
    this.emit('end');
  }}))
  .pipe(jshint())
  .pipe(jshint.reporter('jshint-stylish'))
});

/* JS Task
 -------------------------------------------------- */
gulp.task('js', function() {
  var streams = mergeStream();
  for(var key in config.scripts) {
    streams.add(gulp.src(config.scripts[key]).pipe(concat(key)));
  }
  return streams
  .pipe(plumber({
    errorHandler: function(error) {
    console.log(error.message);
    this.emit('end');
  }}))
  // .pipe(babel({
  //   presets: ["env"]
  // }))
  .pipe(gulpif(args.production, uglify({
    preserveComments: 'license',
  })))
  .pipe(gulp.dest(config.dest.js))
});

/* CSS Task
 -------------------------------------------------- */
gulp.task('css', function() {
  var streams = mergeStream();
  for(var key in config.styles) {
    streams.add(gulp.src(config.styles[key]).pipe(concat(key)));
  }
  return streams
  .pipe(plumber({
    errorHandler: function(error) {
    console.log(error.message);
    this.emit('end');
  }}))
  .pipe(gulpif(args.production, cssnano()))
  .pipe(gulp.dest(config.dest.css))
});

/* SCSS Task
 -------------------------------------------------- */
gulp.task('scss', function() {
  return gulp.src(config.watch.scss)
  .pipe(plumber({
    errorHandler: function(error) {
    console.log(error.message);
    this.emit('end');
  }}))
  .pipe(sass({
    outputStyle: 'expanded',
  }))
  .pipe(autoprefixer('last 2 versions'))
  .pipe(gulpif(args.production, cssnano({
    minifyFontValues: false,
    discardComments: { removeAll: true }
  })))
  .pipe(gulp.dest(config.dest.css))
});

/* Language Tasks
 -------------------------------------------------- */
gulp.task('languages', function() {
  return runSequence('po', 'mo')
});

gulp.task('po', function() {
  return gulp.src(config.watch.php)
  .pipe(checktextdomain({
    text_domain: config.language.domain,
    keywords: [
      '__:1,2d',
      '_e:1,2d',
      '_x:1,2c,3d',
      'esc_html__:1,2d',
      'esc_html_e:1,2d',
      'esc_html_x:1,2c,3d',
      'esc_attr__:1,2d',
      'esc_attr_e:1,2d',
      'esc_attr_x:1,2c,3d',
      '_ex:1,2c,3d',
      '_n:1,2,4d',
      '_nx:1,2,4c,5d',
      '_n_noop:1,2,3d',
      '_nx_noop:1,2,3c,4d',
    ],
  }))
  .pipe(sort())
  .pipe(wpPot({
    domain: config.language.domain,
    lastTranslator: config.language.translator,
    team: config.language.team,
  }))
  .pipe(pseudo({
    // language: 'en_US',
    charMap: {},
  }))
  .pipe(rename(config.language.domain + '-en_US.po'))
  .pipe(gulp.dest(config.dest.lang));
});

gulp.task('mo', function() {
  return gulp.src(config.dest.lang + '*.po')
  .pipe(potomo())
  .pipe(gulp.dest(config.dest.lang));
});

/* Version Task
 -------------------------------------------------- */
gulp.task('bump', function() {
  ['patch', 'minor', 'major'].some(function(arg) {
    if(!args[arg])return;
    for(var key in config.bump) {
      gulp.src(config.bump[key]).pipe(bump({
        type: arg,
        key: key,
      })).pipe(gulp.dest('.'));
    }
    return true;
  });
});

/* Watch Task
 -------------------------------------------------- */
gulp.task('watch', function() {
  gulp.watch(config.watch.css, ['css']);
  gulp.watch(config.watch.js, ['jshint', 'js']);
  gulp.watch(config.watch.scss, ['scss']);
});

/* Default Task
 -------------------------------------------------- */
gulp.task('default', function() {
  gulp.start('css', 'scss', 'jshint', 'js')
});

/* Build Task
 -------------------------------------------------- */
gulp.task('build', function() {
  gulp.start('css', 'scss', 'jshint', 'js', 'languages')
});
