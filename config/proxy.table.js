'use strict'

const proxyTable = {
  '/api': {
    // PC
    // target: 'http://localhost:5921/Other_Project/tetris/BE_API',
    // MAC
    target: 'http://localhost:5921/tetris/BE_API',
    pathRewrite: {
      '^/api': '/'
    }
  }
}


module.exports = proxyTable