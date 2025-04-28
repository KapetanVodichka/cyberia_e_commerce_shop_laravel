# Интернет-магазин (REST API)

## Техническое задание
Полное ТЗ: https://docs.google.com/document/d/1BNlk1fmyUcb_EmCuXeg9ZvFBKQXxEbjJm6pNxckIVjM/edit?tab=t.0

## Описание
Это REST API для простого интернет-магазина, реализованного на Laravel 11 с использованием PostgreSQL. API поддерживает функционал для управления категориями, товарами, заказами и пользователями (покупатели и администраторы).

## Требования
- PHP 8.1+
- Laravel 11+
- PostgreSQL 16+

## Инструкции по запуску

1. **Клонирование репозитория**:
   ```bash
   git clone https://github.com/KapetanVodichka/cyberia_e_commerce_shop_laravel.git
   cd cyberia_e_commerce_shop_laravel
   ```
   
2. **Настройка завсимостей**:
В .env можно выбрать БД sqlite для быстрого тестирования или PostgreSQL если необходимо проверить работу с этой БД.
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

3. **Применение миграций и создание тестовых данных**:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Запуск приложения**:
   ```bash
   php artisan serve
   ```

## Тестирование API
API можно протестировать с помощью коллекции Postman в котором собраны примеры запросов [cyberia_e_commerce_shop_laravel.postman_collection.json](https://github.com/user-attachments/files/19938229/cyberia_e_commerce_shop_laravel.postman_collection.json)

- Открыть Postman
- Импорт коллекцию
- Тестирование с использоанием запросов коллекции
