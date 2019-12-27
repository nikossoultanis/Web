const express = require('express')
const login = express.Router()
const mysql = require('mysql')
const bodyparser = require('body-parser')

login.get('/login', (req, res) => {
    res.sendFile('sign_in.html', {root: './public/login_forms' })
})

login.get('/sign_up', (req, res) => {
    res.sendFile('sign_up.html', {root: './public/login_forms' })
})

module.exports = login

