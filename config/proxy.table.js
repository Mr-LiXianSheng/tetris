'use strict'

const proxyTable = {
  '/api': {
    target: 'http://localhost:5921/Other_Project/tetris/BE_API/',
    pathRewrite: {
      '^/api': '/api'
    }
  }
}


module.exports = proxyTable