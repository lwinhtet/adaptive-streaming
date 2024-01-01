# Dockerized Web Application Setup

This repository contains the necessary configuration files to deploy a Dockerized web application stack, including Nginx, PHP-FPM, MySQL, and PHPMyAdmin. The setup is designed to facilitate easy development, testing, and deployment of web applications in a containerized environment.

## Prerequisites

Before you begin, ensure that you have Docker and Docker Compose installed on your machine.

- [Docker Installation Guide](https://docs.docker.com/get-docker/)
- [Docker Compose Installation Guide](https://docs.docker.com/compose/install/)

## Getting Started

1. Clone this repository to your local machine:

   ```bash
   git clone https://github.com/lwinhtet/adaptive-streaming
   cd your-repo
   ```

2. Customize Configuration:

   - **Nginx Configuration:** Adjust the settings in `nginx.conf/default.conf` to suit your web application's requirements.

   - **PHP Configuration:** Modify settings in `php.conf/php.ini` to meet your PHP application's needs.

   - **MySQL Configuration:** Update environment variables in the `docker-compose.yml` file under the `mysql` service for MySQL database settings.

3. Build and Run:

   ```bash
   docker-compose up -d --build
   ```

4. Access your web application:

   - Web application: [http://localhost:8000](http://localhost:8000)
   - PHPMyAdmin: [http://localhost:8888](http://localhost:8888)
   - Login with server `mysql`, username `your_username`, and password `your_password`

## Services

### 1. Nginx

Nginx serves as the web server and reverse proxy, forwarding requests to the PHP-FPM service. The configuration is specified in `nginx.conf/default.conf`.

### 2. PHP-FPM

PHP-FPM (FastCGI Process Manager) processes PHP scripts. The configuration is defined in `PHP.Dockerfile` and `php.conf/php.ini`.

### 3. MySQL

MySQL is used as the database server. Customize database credentials in the `docker-compose.yml` file under the `mysql` service.

### 4. PHPMyAdmin

PHPMyAdmin provides a web interface to manage MySQL databases. Access it at [http://localhost:8888](http://localhost:8888) with the provided credentials.

## Additional Information

- **Volumes:**
  - A Docker volume named `mysqldata` is created to persist MySQL data.
- **Network:**
  - All services are part of a custom network named `my_network`, allowing seamless communication between containers using their service names.

## Contributing

Feel free to contribute to improve this Dockerized web application setup. Create issues for any problems or suggestions you encounter.

Happy coding!
