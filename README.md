# INF653 - PHP OOP REST API Midterm Project

Author: David A. Sowles  
Live Application: https://inf653-midtermproject-davidsowles.onrender.com/api

## Project Description
This project is a RESTful API built with PHP using Object-Oriented Programming (OOP) principles. It interacts with a PostgreSQL database to manage a collection of quotes, authors, and categories. The API supports full CRUD (Create, Read, Update, Delete) operations and provides responses in JSON format.

## Project Features
*   Language: PHP
*   Database: PostgreSQL
*   Hosting: Render

## 🛠 API Endpoints

### Authors
*   `GET /api/authors/` - Retrieve all authors.
*   `GET /api/authors/?id=5` - Retrieve a single author by ID.
*   `POST /api/authors/` - Create a new author.
*   `PUT /api/authors/` - Update an existing author.
*   `DELETE /api/authors/` - Delete an author.

### Categories
*   `GET /api/categories/` - Retrieve a list of all categories.
*   `GET /api/categories/?id=4` - Retrieve a single category by ID.
*   `POST /api/categories/` - Create a new category.
*   `PUT /api/categories/` - Update a category.
*   `DELETE /api/categories/` - Delete a category.

### Quotes
*   `GET /api/quotes/` - Retrieve all quotes.
*   `GET /api/quotes/?id=10` - Retrieve a single quote by ID.
*   `GET /api/quotes/?author_id=5` - Filter and retrieve all quotes by a specific author.
*   `GET /api/quotes/?category_id=4` - Filter and retrieve all quotes by a specific category.
*   `GET /api/quotes/?author_id=5&category_id=4`** - Retrieve quotes matching both author and category filters.
*   `POST /api/quotes/` - Create a new quote.
*   `PUT /api/quotes/` - Update a quote.
*   `DELETE /api/quotes/` - Delete a quote.