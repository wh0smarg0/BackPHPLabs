const mongoose = require('mongoose');

const articleSchema = new mongoose.Schema({
    authorName: { type: String, required: true },
    authorAddress: String,
    login: { type: String, required: true },
    password: { type: String, required: true },
    topic: String,
    title: { type: String, required: true },
    content: String,
    illustration: String
}, { timestamps: true });

module.exports = mongoose.model('Article', articleSchema);
