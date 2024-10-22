const express = require('express')
const app = express()
const mysql = require('mysql')
// const bodyparser = require('body-parser')

app.use(express.static('public'))
app.use(express.static('public/login_forms'))
app.use(express.urlencoded({ extended: false }))

const login = require('../routes/login.js')
const user = require('../routes/user.js')
const admin = require('../routes/admin.js')

app.use(login)
// app.use(user)
// app.use(admin)

app.listen(3000)