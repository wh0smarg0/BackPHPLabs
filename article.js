const mongoose = require('mongoose');

const articleSchema = new mongoose.Schema({
    authorName: String,
    authorAddress: String,
    login: String,
    topic: String,
    title: { type: String, required: true },
    content: String,
    illustration: String,
    createdAt: { type: Date, default: Date.now }
});

module.exports = mongoose.model('article', articleSchema);
