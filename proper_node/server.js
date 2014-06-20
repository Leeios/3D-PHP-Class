//require
var cfg = require('config').Default,
	express = require('express'),
	app = express().use(express.static(__dirname + '/public')),
	server = require('http').Server(app),
	io = require('socket.io')(server);

var routes = require('./routes');

//config
app.set('view engine', 'jade');

//socket
var idUser = 0;
io.on('connection', function() {
	console.log("User " + idUser++ + " connected");
});

//path
app.get('/', routes.main);

//listen
server.listen(cfg.Port, function() {
	console.log('Listening on Port ' + cfg.Port);
});
