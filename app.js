const express = require('express');
const hbs = require('hbs');
const path = require('path');
const request = require('request');

const app = express();

// Налаштування шляхів для Express
const viewsPath = path.join(__dirname, '/views');
const partialsPath = path.join(__dirname, '/views/partials');
const publicDirectoryPath = path.join(__dirname, '/public');

// Налаштування шаблонізатора hbs та статичних файлів
app.set('view engine', 'hbs');
app.set('views', viewsPath);
hbs.registerPartials(partialsPath);
app.use(express.static(publicDirectoryPath));

// API ключ з OpenWeatherMap
const apiKey = '0343c5889546441edcf96def52da8c6c';

/**
 * Головна сторінка
 */
app.get('/', (req, res) => {
    res.render('index', {
        title: 'WEATHER APP',
        name: 'Сахно Маргарита',
        group: 'ІО-35'
    });
});

/**
 * Маршрут для отримання погоди
 * Підтримує:
 * 1. /weather/Kyiv (через параметри шляху)
 * 2. /weather?lat=50.45&lon=30.52 (через query-параметри для геолокації)
 */
app.get(['/weather', '/weather/:city'], (req, res) => {
    const city = req.params.city;
    const { lat, lon } = req.query;

    let url = '';

    // 1. Визначення типу запиту
    if (lat && lon) {
        url = `http://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=ua`;
    } else if (city) {
        url = `http://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=ua`;
    } else {
        // Значення за замовчуванням
        url = `http://api.openweathermap.org/data/2.5/weather?q=Obukhiv&appid=${apiKey}&units=metric&lang=ua`;
    }

    request({ url, json: true }, (error, response) => {
        // Виводимо відповідь у термінал для дебагу
        if (response && response.body) {
            console.log('Статус API:', response.body.cod);
            console.log('Повідомлення API:', response.body.message);
        }

        // 2. Обробка критичних помилок (немає зв'язку)
        if (error) {
            return res.status(500).render('error', {
                title: 'Помилка мережі',
                message: 'Не вдалося з’єднатися з сервером погоди.'
            });
        }

        // 3. Розділення помилок за кодами OpenWeatherMap
        if (response.body.cod !== 200) {
            let errorTitle = 'Помилка';
            let errorMessage = 'Щось пішло не так.';

            switch (response.body.cod) {
                case 401:
                    errorTitle = 'Помилка авторизації';
                    errorMessage = 'Ваш API-ключ недійсний або ще не активований (зачекайте до 2-х годин).';
                    break;
                case 404:
                    errorTitle = 'Місто не знайдено';
                    errorMessage = city
                        ? `Місто "${city}" не знайдено в базі даних.`
                        : 'Не вдалося знайти населений пункт за вашими координатами.';
                    break;
                case 429:
                    errorTitle = 'Ліміт вичерпано';
                    errorMessage = 'Ви зробили забагато запитів за короткий час.';
                    break;
                default:
                    errorMessage = response.body.message || 'Помилка отримання даних.';
            }

            return res.status(response.body.cod).render('error', {
                title: errorTitle,
                message: errorMessage
            });
        }

        // 4. Успішний рендер
        res.render('weather', {
            city: response.body.name,
            temp: Math.round(response.body.main.temp),
            description: response.body.weather[0].description,
            humidity: response.body.main.humidity,
            pressure: response.body.main.pressure,
            icon: response.body.weather[0].icon
        });
    });
});

/**
 * Універсальний обробник 404 помилки (Middleware)
 */
app.use((req, res) => {
    res.status(404).render('error', {
        title: '404',
        message: 'Сторінку не знайдено.'
    });
});

// Запуск сервера на порту 3000
app.listen(3000, () => {
    console.log('Сервер працює на порту 3000. Відкрийте http://localhost:3000');
});
