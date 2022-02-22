## Setting up
Start by cloning the project:

` git clone git@github.com:AlexOlival/voicemod-challenge.git`

Then, `cd` into the project directory and run the following command:

`docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html -w /var/www/html laravelsail/php81-composer:latest composer install --ignore-platform-reqs`

This will install PHP as well as the necessary dependencies in order to run the project.
Once that is done, copy the .env.example file to a .env file to define our environment variables:

`cp .env.example .env`

This will allow Sail, Laravel's Docker CLI, to create a MySQL database named after the DB_DATABASE environment variable.
We can now prop up our containers:

`./vendor/bin/sail up`

After the command is done pulling and building the required images, run the following command (you may need a new tab):

`./vendor/bin/sail artisan key:generate`

And then:

`./vendor/bin/sail artisan migrate --seed`

Which will set an application key and migrate and seed the database, respectively.

The application should now be running and exposed on `localhost`.

That's it!

## Running tests

`./vendor/bin/sail test`


## Shutting down

Simply use the Docker CLI or the UI as normal; or you can use Sail's command to gracefully shutdown:

`./vendor/bin/sail down`

## Potential issues
Port collisions; ensure that no services are running and being exposed on port 3306 or 8080.

For any additional info you can also check the [Laravel Sail documentation](https://laravel.com/docs/9.x/sail).
