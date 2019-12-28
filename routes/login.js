const express = require('express')
const login = express.Router()
const mysql = require('mysql')
// const bodyparser = require('body-parser')

login.get('/', (req, res) => {
    res.sendFile('index.html', { root: 'public/login_forms' }) 
})

login.get('/sign_in', (req, res) => {
    res.sendFile('sign_in.html', { root: './public/login_forms' })
})

login.get('/sign_up', (req, res) => {
    res.sendFile('sign_up.html', { root: './public/login_forms' })
})

login.post('/sign_up', (req, res) => {
    username = req.body.username
    email = req.body.email
    console.log(username, email)

    const connection = mysql.createConnection({
        host: 'localhost',
        user: 'root',
        password: '1234',
        database: 'web'
    })
    
    connection.query("SELECT * FROM users WHERE email = ? ", (err, rows, fields) => {
        res.send("ekanes sign up")
    })
})

module.exports = login