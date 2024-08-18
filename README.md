# Stock Tracker

A [Symfony](https://symfony.com) based stock tracker with [Vue.js](https://vuejs.org/) frontend, [FrankenPHP](https://frankenphp.dev) as php server and [Caddy](https://caddyserver.com/) as webserver!

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+).
2. Make sure that no other service is running on ports 80, 443, 8080, 3306 and 8025.
3. Clone this repository.
4. Run `make start` to build and start the containers.
5. Download and add the [Postman collection](docs/Stock-Tracker.postman_collection.json) to your Postman app.
   1. This collection has all the endpoints that the app uses.
      1. Login Check - To login the user and get the JWT token.
      2. Register - To register a new user.
      3. Stock - To get the quote for a stock.
      4. History - To get the history of all the users quote calls.

## Usage
1. First of all to use the app you need to create an account.
   1. Register a new user using the Postman call (Register). Change username and password fields if needed in the body.
2. Login to the app using the Postman call (Login Check). Change username and password fields if needed.
   1. JWT token will be stored in collection variables to be used in further calls.
   2. If you get a 401 error, on other calls is because JWT token is expired. Just login again.
3. Get the quote for a stock using the Postman call (Stock). Change q field to the stock you want to get the quote.
4. Get the history of all the users quote calls using the Postman call (History).
5. You can check the received emails when you call the Stock endpoint. The email will be sent to the user email with the quote information.
   1. You can check the email in the MailHog web interface. Go to [http://localhost:8025](http://localhost:8025) to access the MailHog web interface.
6. You can use the frontend app also with these features. Go to [http://localhost:8080](http://localhost:8080) to access the app.

## Usefull Links
* [http://localhost:8080](http://localhost:8080) - Frontend app.
* [http://localhost/api](http://localhost/api) - API endpoint.
* [http://localhost:8025](http://localhost:8025) - Mailer web interface.
* [docs/Stock-Tracker.postman_collection.json](docs/Stock-Tracker.postman_collection.json) - Postman collection.
* [docs/API.md](docs/API.md) - API documentation.

## Useful Commands

* `make start` - Build and start images and containers.
* `make build` - Build all the docker images.
* `make up` - Start the containers.
* `make down` - Stop the containers.
* `make test` - Run the tests.

**Enjoy!**

