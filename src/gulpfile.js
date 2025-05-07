const {
    src,
    dest,
    series,
    watch
} = require('gulp');

const sass = require('gulp-sass')(require('sass'));

/* Configuração do CSS */

var scssGeral     = "assets/scss/basestyle/style.scss";
//var scssBootstrap = "assets/scss/basestyle/bootstrap.scss";
var scssLogin     = "assets/scss/basestyle/login.scss";

// CSS de destino
var cssDest = "assets/css/app";

// Opções de compilação (expanded e compressed)
var optionsDev = {
    outputStyle: "compressed"
}

/*function sassBootstrap() {
    return src(scssBootstrap)
    .pipe(sass(optionsDev).on('error', sass.logError))
    .pipe(dest(cssDest));
}*/

function sassInternal() {
    return src(scssGeral)
    .pipe(sass(optionsDev).on('error', sass.logError))
    .pipe(dest(cssDest));
}

function sassLogin() {
    return src(scssLogin)
    .pipe(sass(optionsDev).on('error', sass.logError))
    .pipe(dest(cssDest));
}

function watchFiles() {
    //watch("assets/scss/**/*.scss", sassBootstrap);
    watch("assets/scss/**/*.scss", sassInternal);
    watch("assets/scss/**/*.scss", sassLogin);
}

exports.default = series(
    watchFiles
);