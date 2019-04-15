## Laravel REST API example

### Installation

* install vendor libs: `composer install`
* generate application key: `php artisan key:generate`
* copy from *.env.example* and edit *.env* file manually
* run migrations: `php artisan migrate`
* run server internal: `php artisan serve`

### Docker

You can run application through Docker (Laradock).

If *Laradock* was installed in project root:

* move to *laradock* folder
* run `docker-compose up -d --build nginx mysql workspace`
* edit app & laradock *.env* files
* run commands: `docker-compose exec --user=laradock workspace *command*`

### Tests

You can run tests with the command `./vendor/bin/phpunit` (local) in root folder or `phpunit` (global)
