const Encore = require('@symfony/webpack-encore');
const TerserPlugin = require("terser-webpack-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const glob = require('glob');
const path = require('path');
const theme = path.basename(__dirname);
console.log(theme, __dirname)
// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore.addAliases({
    "@tailwind": path.resolve(__dirname, 'assets/tailwind'),
    "@components": path.resolve(__dirname, 'assets/components/')
});

const globToEntry = (base, pattern) => {
    return glob.sync(path.join(base, pattern)).reduce((entry, file) => {
        const parsedPath = path.parse(path.relative(base, file));
        entry[parsedPath.dir] = path.resolve(file);
        return entry;
    }, {});
};

Encore
    // directory where compiled assets will be stored
    .setOutputPath('build/')
    // public path used by the web server to access the output path
    .setPublicPath(`/app/themes/${theme}/build`)
    .setManifestKeyPrefix('')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    .enableBuildCache({
        // object of "buildDependencies"
        // https://webpack.js.org/configuration/other-options/#cachebuilddependencies
        // __filename means that changes to webpack.config.js should invalidate the cache
        config: [__filename],
    })

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    .addEntries(globToEntry("assets", 'components/**/index.ts'))

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[hash:8].[ext]'
    })

    .copyFiles({
        from: './assets/fonts',
        to: 'fonts/[path][name].[hash:8].[ext]'
    })

    .enablePostCssLoader()
    .enableTypeScriptLoader()
    .enableForkedTypeScriptTypesChecking()
    .enableEslintPlugin()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    .enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    .configureWatchOptions(watchOptions => {
        watchOptions.poll = 250;
    })
;

const config = Encore.getWebpackConfig();
if (!Encore.isProduction()) {
    config.devtool = 'eval-source-map';
    config.module.unsafeCache = true;
}

module.exports = config;
