// npm install express express-http-proxy

var proxy = require('express-http-proxy');

var app = require('express')();

if (!String.prototype.endsWith) {
  String.prototype.endsWith = function(searchString, position) {
      var subjectString = this.toString();
      if (position === undefined || position > subjectString.length) {
        position = subjectString.length;
      }
      position -= searchString.length;
      var lastIndex = subjectString.indexOf(searchString, position);
      return lastIndex !== -1 && lastIndex === position;
  };
}

app.use(proxy('vip.local', {
  forwardPath: function(req, res) {
    return require('url').parse(req.url).path;
  },
  intercept: function(rsp, data, req, res, callback) {
       // rsp - original response from the target
       console.log(req.path)
       if (res.get('Content-Type') && res.get('Content-Type').indexOf('text/html') > -1) {
           data = data.toString('utf8').replace(/http:\/\/vip.local\//gi, '/');
       }
       callback(null, data);
  },
  decorateRequest: function(req) {
       req.headers['Accept-Encoding'] = null;
       return req;
  }
}));

app.listen(8080);
