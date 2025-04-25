## Technical description

- Docker
- Docker Compose
- PHP 8.2
- Laravel Framework
- Redis
- Sqlite
- Nginx
- PhpFPM

The application is a simple Laravel application that simulates the process of asynchronous processing events.

Nginx is used as an API gateway to handle incoming requests and forward them to the appropriate service and rate limiting.

Redis is used to emulate the message broker.

## Setting up

Make sure docker and docker compose already installed on your machine. If not, please
visit [official documentation](https://docs.docker.com/engine/install/) to
install it.

- Enter cp_challenge folder and run `docker-compose.yml -p cp_challenge up -d`

## Documentation

- Follow [this link](http://localhost:8080/api/documentation) to see swagger documentation

## How to run

Make a POST request to `http://localhost:8080/api/start-charging` with the following data:

### Test Data Example

- Success Result:
```json
{
    "station_id": "123e4567-e89b-12d3-a456-426614174000",
    "driver_token": "validDriverToken1234",
    "callback_url": "http://nginx/callback"
}
```
- Not Allowed Result:
```json
{
  "station_id": "123e4567-e89b-12d3-a456-426614174000",
  "driver_token": "invalidDriverToken1234",
  "callback_url": "http://nginx/callback"
}
```
- Unknown Driver Token Emulation. Timeout parameter here is for emulation purposes only. You can check `api/app/Services/CircuitBreakerServer.php`
```json
{
  "station_id": "123e4567-e89b-12d3-a456-426614174000",
  "driver_token": "unknownDriverToken1234",
  "callback_url": "http://nginx/callback",
  "timeout": "30"
}
```

## Debugging

To check the results of the requests, you can use the following commands:
`docker logs -f auth_service`

## Testing

To run the tests, you can use the following command:
`docker exec -it api_service bash -c "php artisan test"`

## Tradeoffs 

There are some tradeoffs that were made in the implementation of this application because of the time constraints. There are some TODO comments across the application to explain some tradeoffs. Some of them are:

- .env file was added to the git repository to simplify installation process
- Laravel debug mode was enabled to simplify development process
- Endless job was added to the queue to simulate the process of asynchronous processing events. In production, it should be replaced with a real job that processes events e.g. supervisor.
- Redis was used as a message broker. In production, it should be replaced with a real message broker e.g. RabbitMQ or Kafka.
- Circuit breaker was implemented in a very simple way. In production, it should be replaced with a real circuit breaker e.g. Hystrix or Resilience4j.
- ACL was implemented in a very simple way. In production, it should be replaced with a real ACL solution e.g. Laravel ACL or Spatie Laravel Permission.
- Test coverage is low. Several tests were added to show the abbility to test the application. In production, it should be replaced with a real test suite that covers all the functionality of the application.
- Rate limiting was implemented in a very simple way. In production, it should be replaced with a real rate limiting solution.
- sqlite was used as a database. In production, it should be replaced with a real database e.g. MySQL or PostgreSQL.
- Dummy endpoint `http://nginx/callback` was added to simulate `callback_url` functionality.