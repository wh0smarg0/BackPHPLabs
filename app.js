const express = require('express');
const mongoose = require('mongoose');
const path = require('path');
const methodOverride = require('method-override');
const { ApolloServer } = require('@apollo/server');
const { expressMiddleware } = require('@apollo/server/express4');
const { json } = require('body-parser');
const cors = require('cors');
const Article = require('./models/Article');

const app = express();

// Підключення до бази даних (Варіант 9)
mongoose.connect('mongodb://127.0.0.1:27017/lab5_db')
    .then(() => console.log('✅ Connected to MongoDB'))
    .catch(err => console.log('❌ DB Error:', err));

app.set('view engine', 'hbs');
app.use(express.urlencoded({ extended: true }));
app.use(methodOverride('_method'));
app.use(express.static(path.join(__dirname, 'public')));
app.use(cors());

// --- Визначення схеми GraphQL (SDL) ---
const typeDefs = `#graphql
  type Article {
    id: ID!
    authorName: String!
    authorAddress: String
    login: String!
    topic: String
    title: String!
    content: String
    illustration: String
  }

  type Query {
    getAllArticles: [Article] # Отримати всі статті
    getArticle(id: ID!): Article # Пошук за ID
  }

  type Mutation {
    # Створення статті
    createArticle(authorName: String!, login: String!, password: String!, title: String!, content: String, topic: String): Article
    
    # Редагування статті (замінює форму редагування)
    updateArticle(id: ID!, title: String, content: String, topic: String): Article
    
    # Видалення статті (замінює кнопку видалення)
    deleteArticle(id: ID!): Boolean
  }
`;

// --- Резолвери (Логіка CRUD) ---
const resolvers = {
    Query: {
        getAllArticles: async () => await Article.find(),
        getArticle: async (_, { id }) => await Article.findById(id)
    },
    Mutation: {
        createArticle: async (_, args) => {
            return await Article.create(args);
        },
        updateArticle: async (_, { id, ...updates }) => {
            return await Article.findByIdAndUpdate(id, updates, { new: true });
        },
        deleteArticle: async (_, { id }) => {
            const result = await Article.findByIdAndDelete(id);
            return !!result;
        }
    }
};

const server = new ApolloServer({ typeDefs, resolvers });

async function startServer() {
    await server.start();

    // Ендпоінт для запитів GraphQL
    app.use('/graphql', cors(), json(), expressMiddleware(server));

    // Головна сторінка
    app.get('/', async (req, res) => {
        const articles = await Article.find();
        res.render('index', { articles, title: 'Архів статей: GraphQL Edition' });
    });

    app.listen(3000, () => {
        console.log('🚀 Сервер: http://localhost:3000');
        console.log('📊 Тестування GraphQL: http://localhost:3000/graphql');
    });
}

startServer();
