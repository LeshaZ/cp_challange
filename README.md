## Technical description

- Docker
- Docker Compose
- PHP 8.2
- Laravel Framework
- Redis (pub/sub)
- MySql
- Nginx
- PhpFPM

## Setting up

Make sure docker and docker compose already installed on your machine. If not, please
visit [official documentation](https://docs.docker.com/engine/install/) to
install it.

- Enter cp_challenge folder and run `docker-compose.yml -p cp_challange up -d`
- Use some tool to send POST request to `http://localhost:8080/api/start-charging` with appropriate data.

## Tradeoffs (should not be in production solution)

- .env file was added to the git repository to simplify installation process
- Laravel debug mode was enabled to simplify development process
