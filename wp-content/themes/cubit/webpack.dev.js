const { merge } = require('webpack-merge')
const common = require('./webpack.common')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

module.exports = merge(common, {
  mode: 'development',
  devtool: 'eval-cheap-module-source-map',
  plugins: [
    new CleanWebpackPlugin({
      cleanStaleWebpackAssets: false,
    }),
    new BrowserSyncPlugin({
      proxy: 'wpecommercebase.local',
      files: ['**/*.php'],
      reloadDelay: 0,
      notify: false,
      open: false,
    }),
  ],
})
