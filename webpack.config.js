const Encore = require('@symfony/webpack-encore');

Encore
    // Le répertoire de sortie pour les fichiers compilés
    .setOutputPath('public/build/')
    // Le répertoire public pour accéder aux fichiers compilés
    .setPublicPath('/build')

    // Entrée principale de l'application (généralement app.js)
    .addEntry('app', './assets/app.js')

    // Si vous utilisez Sass ou LESS
    // .enableSassLoader()

    // Générer un fichier source map pour le débogage
    .enableSourceMaps(!Encore.isProduction())

    // Autoriser la gestion des fichiers images

    // Activer la versionning des fichiers pour le cache-busting
    .enableVersioning(Encore.isProduction())

    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
