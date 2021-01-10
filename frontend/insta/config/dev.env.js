'use strict'
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  baseUrl: `"http://${process.env.API_HOST}:${process.env.API_PORT}/api"`
})
