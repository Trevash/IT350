/**
 *
 *
 *  @license
 *    Copyright 2018 Brigham Young University
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 *
 **/

var mongoose = require('mongoose');
var User = require('./user');

var ProductSchema = new mongoose.Schema({
    name: {
        type: String,
        unique: true,
        required: true,
        trim: true
    },
    price: {
        type: Number,
        unique: false,
        required: true,
        trim: true
    }
});

//authenticate input against database
ProductSchema.statics.purchase = function (email, password, callback) {
    User.findOne({ email: email })
        .exec(function (err, user) {
            if (err) {
                return callback(err)
            }
            else if (!user) {
                var err = new Error('User not found.');
                err.status = 401;
                return callback(err);
            }
            if (user.password === password) {
                return callback(null, user);
            }
            else return callback();
        });
};


var Product = mongoose.model('Product', ProductSchema);
module.exports = Product;

