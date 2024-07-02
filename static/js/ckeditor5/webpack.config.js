'use strict';

/* eslint-env node */

const path = require( 'path' );
const TerserWebpackPlugin = require( 'terser-webpack-plugin' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );

module.exports = {
devtool: false,
performance: { hints: false },

entry: path.resolve( __dirname, 'src', 'ckeditor.ts' ),

output: {
    // The name under which the editor will be exported.
    library: 'ClassicEditor',

    path: path.resolve( __dirname, 'build' ),
    filename: 'ckeditor.js',
    libraryTarget: 'umd',
    libraryExport: 'default'
},

optimization: {
    minimize: true,
    minimizer: [
        new CssMinimizerPlugin(),
        new TerserWebpackPlugin( {
            terserOptions: {
                output: {
                    // Preserve CKEditor&nbsp;5 license comments.
                    comments: /^!/
                }
            },
            extractComments: false
        } )
    ]
},

plugins: [
    new MiniCssExtractPlugin( {
        filename: 'ckeditor.css'
    } ),
],

resolve: {
    extensions: [ '.ts', '.js', '.json' ]
},

module: {
    rules: [
        {
			test: /\.svg$/,
			use: [ 'raw-loader' ]
		},
        {
            test: /\.ts$/,
            use: 'ts-loader'
        },
        {
            test: /\.css$/i,
            use: [ MiniCssExtractPlugin.loader, 'css-loader' ]
        }
    ]
}
};
