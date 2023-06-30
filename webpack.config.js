const path = require('path');

module.exports = {
  entry: './public/js/fecharconteudo.js',
  output: {
    filename: 'mind_ar.js',
    path: path.resolve(__dirname, 'public/js'),
  },
  resolve: {
    fallback: {
        "fs": false
    },
  }
};