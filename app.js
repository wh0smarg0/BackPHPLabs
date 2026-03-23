const express = require('express');
const mongoose = require('mongoose');
const path = require('path');
const methodOverride = require('method-override');
const Article = require('./models/article');

const app = express();

// Підключення до локальної БД
mongoose.connect('mongodb://127.0.0.1:27017/lab5_db')
    .then(() => console.log('З’єднано з MongoDB'))
    .catch(err => console.log('Помилка з’єднання:', err));

app.set('view engine', 'hbs');
app.use(express.urlencoded({ extended: true }));
app.use(methodOverride('_method'));
app.use(express.static(path.join(__dirname, 'public')));

// --- РОУТИ ---

// 1. READ: Список усіх статей
app.get('/', async (req, res) => {
    try {
        const articles = await Article.find();
        res.render('index', { title: 'Архів статей', articles });
    } catch (err) {
        res.status(500).send('Помилка завантаження бази даних');
    }
});

// 2. CREATE: Форма додавання
app.get('/add', (req, res) => {
    res.render('add', { title: 'Додати нову статтю' });
});

// 2. CREATE: Збереження в базу
app.post('/articles', async (req, res) => {
    try {
        const newArticle = new Article(req.body);
        await newArticle.save();
        res.redirect('/');
    } catch (err) {
        res.status(400).send('Помилка при збереженні даних');
    }
});

// 3. JSON: Виведення даних
app.get('/api/articles', async (req, res) => {
    try {
        const articles = await Article.find();
        res.json(articles);
    } catch (err) {
        res.status(500).json({ error: 'Помилка отримання JSON' });
    }
});

// 4. UPDATE: Форма редагування
app.get('/edit/:id', async (req, res) => {
    try {
        const article = await Article.findById(req.params.id);
        if (!article) return res.status(404).send('Статтю не знайдено');
        res.render('edit', { title: 'Редагування', article });
    } catch (err) {
        res.status(500).send('Некоректний ID');
    }
});

// 4. UPDATE: Оновлення в базі
app.put('/articles/:id', async (req, res) => {
    try {
        await Article.findByIdAndUpdate(req.params.id, req.body);
        res.redirect('/');
    } catch (err) {
        res.status(500).send('Помилка при оновленні');
    }
});

// 5. DELETE: Видалення
app.delete('/articles/:id', async (req, res) => {
    try {
        await Article.findByIdAndDelete(req.params.id);
        res.redirect('/');
    } catch (err) {
        res.status(500).send('Помилка при видаленні');
    }
});

// READ: Перегляд однієї конкретної статті
app.get('/articles/:id', async (req, res) => {
    try {
        const article = await Article.findById(req.params.id);
        if (!article) return res.status(404).send('Статтю не знайдено');

        res.render('details', { title: article.title, article });
    } catch (err) {
        res.status(500).send('Помилка при завантаженні статті');
    }
});

app.listen(3000, () => console.log('Сервер: http://localhost:3000'));
