# Set API_KEY in .env :
API_KEY="YOUR_API_KEY"

# Set your database config in .env :
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# Start the server with :
php artisan serve 

# Then you can generate basic table with :
php artisan db:seed

# To start tests :
php artisan test

# You can call these routes with the right API_KEY :
- Return all Orders -> /api/orders [GET]
- Save an Order in database -> /api/order [POST]
- Update Order in database -> /api/order/{id} [PUT]
- Get an Order -> /api/get-order/{id} [GET]
- Delete an Order in database -> /api/order/{id} [DELETE]
- Get all Products  -> /api/products [GET]
- Sort Orders for schedule page -> /schedule [GET]

# About the project, missing :
- Add tests for all models
- Add tests for all routes
- Add CSRF with cors to POST|PUT|DELETE routes

# Warning :
'allowed_origins' => ['*'] in config/cors.php must have an array of whitelist url and not *

# Backend in prod :
https://backend.areauniverse.fr/
