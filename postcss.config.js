module.exports = {
    module: {
        rules: [
            {
                test: /\.css$/,
                use: ["style-loader", "css-loader", "postcss-loader"]
            }
        ]
    },

    plugins: {
        // include whatever plugins you want
        // but make sure you install these via yarn or npm!

        // add browserslist config to package.json (see below)
        autoprefixer: {}
    }
}