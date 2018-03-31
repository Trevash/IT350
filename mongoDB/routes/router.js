var express = require('express');
var router = express.Router();
var User = require('../models/user');
var Product = require('../models/product');

// GET route for reading data
router.get('/', function (req, res, next) {
  return res.sendFile(path.join(__dirname + '/templateLogReg/index.html'));
});

router.post('/chgpwd', function (req, res, next) {
    User.findById(req.session.userId).exec(function (error, user) {
        if (error) {
            return next(error);
        }
        else if (user === null) {
            var err = new Error('Not authorized! Go back!');
            err.status = 400;
            return next(err);
        }
        else if (req.body.currPassword !== user.password) {
            var err = new Error('Passwords do not match!');
            err.status = 400;
            return next(err);
        }
        else {
          user.password = req.body.newPassword;
          user.passwordConf = req.body.newPassword;
          user.save(function (err, user) {
              if (err) {
                  var err = new Error('Password change failed. Check your password');
                  err.status = 400;
                  return next(err);
              }
              else return res.redirect('/profile');
          });
        }
    })
});

router.post('/items', function (req, res, next) {
    User.findById(req.session.userId).exec(function (error, user) {
        if (error) {
            return next(error);
        }
        else if (user === null) {
            var err = new Error('Not authorized! Go back!');
            err.status = 400;
            return next(err);
        }
        else {
          var productData = {
            name: req.body.product,
            price: req.body.price
          };
            Product.create(productData, function (error, product) {
                if (error) {
                  return next(error);
                }
                else {
                  return res.redirect('/items');
                }
            });
        }
    })
});

//POST route for updating data
router.post('/', function (req, res, next) {
  // confirm that user typed same password twice
  if (req.body.password !== req.body.passwordConf) {
    var err = new Error('Passwords do not match.');
    err.status = 400;
    res.send("passwords dont match");
    return next(err);
  }

  if (req.body.email &&
    req.body.username &&
    req.body.password &&
    req.body.passwordConf) {

    var userData = {
      email: req.body.email,
      username: req.body.username,
      password: req.body.password,
      passwordConf: req.body.passwordConf,
    };

    User.create(userData, function (error, user) {
      if (error) {
        return next(error);
      } else {
        req.session.userId = user._id;
        return res.redirect('/profile');
      }
    });

  }
  else if (req.body.logemail && req.body.logpassword) {
    User.authenticate(req.body.logemail, req.body.logpassword, function (error, user) {
      if (error || !user) {
        var err = new Error('Wrong email or password.');
        err.status = 401;
        return next(err);
      } else {
        req.session.userId = user._id;
        return res.redirect('/profile');
      }
    });
  } else {
    var err = new Error('All fields required.');
    err.status = 400;
    return next(err);
  }
});

// GET route after registering
router.get('/profile', function (req, res, next) {
  User.findById(req.session.userId)
    .exec(function (error, user) {
      if (error) {
        return next(error);
      } else {
        if (user === null) {
          var err = new Error('Not authorized! Go back!');
          err.status = 400;
          return next(err);
        }
        else {
          return res.render('profile.html', {
            user: user
          })
        }
      }
    });
});

// GET for logout logout
router.get('/logout', function (req, res, next) {
  if (req.session) {
    // delete session object
    req.session.destroy(function (err) {
      if (err) {
        return next(err);
      } else {
        return res.redirect('/');
      }
    });
  }
});

router.get('/changePwd', function (req, res, next) {
    User.findById(req.session.userId).exec(function (error, user) {
        if (error) {
            return next(error);
        }
        else if (user === null) {
            var err = new Error('Not authorized! Go back!');
            err.status = 400;
            return next(err);
        }
        else {
            return res.render('changePwd.html', {
                user: user
            });
        }
    })
});

router.get('/items', function (req, res, next) {
    User.findById(req.session.userId).exec(function (error, user) {
        if (error) {
          return next(error);
        }
        else if (user === null) {
          var err = new Error('Not authorized! Go back!');
          err.status = 400;
          return next(err);
        }
        else {
          Product.find({}, function (err, products) {
            if (err) {
              console.log(err);
              return next("Unable to load items page");
            }
            var productArray = [];
            products.forEach(function (product) {
              console.log(product._doc.name);
                productArray.push(product);
            });
              return res.render('items.html', {
                products: products
              });
          });
        }
    })
});

module.exports = router;
