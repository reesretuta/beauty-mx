var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    // sass = require('gulp-ruby-sass');
    sass = require('gulp-sass'),
    gutil = require('gulp-util');

// gulp.task('scripts', function(){
//     gulp.src('js/*.js')
//     .pipe(uglify())
//     .pipe(gulp.dest('build/js'));
// });

function errorLog(err){
    console.error.bind(error);
    this.emit('end');
}

gulp.task('styles', function(){
    gulp.src('./httpdocs/media/JAFRA_files/*.scss')
    .pipe(sass())
    .on('error',console.error.bind(console))
    .pipe(gulp.dest('./httpdocs/media/JAFRA_files/'));
});

gulp.task('watch',function(){
    // gulp.watch('js/*.js',['scripts']);
    gulp.watch('./httpdocs/media/JAFRA_files/*.scss',['styles']);
});

gulp.task('default',['styles','watch']);