exports.main = function(req, res) {
	res.render('index.jade');
}

exports.err_404 = function(req, res) {
	res.send(404, 'NOT FOUND');
}
