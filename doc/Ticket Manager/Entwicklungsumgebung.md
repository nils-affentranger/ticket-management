## Docker Compose
Ich habe die Entwicklungsumgebung mit Docker eingerichtet, weil es mich interessiert habe, wie man ein Laravel Projekt, inklusive DB, in einer Docker Compose Umgebung  einrichtet. Ich hatte schon ein wenig Erfahrung mit Docker und Docker Compose, weil ich zu Hause einen Server habe. Jedoch hatte ich bis dahin erst schon bestehende Docker Compose Umgebungen ausgeführt.

> [!INFO]
> Nur weil ich das Projekt mit Docker Compose eingerichtet habe, heisst das nicht, dass man es nicht ohne Docker ausführen kann. Selbstverständlich kann man das Projekt auf XAMPP ausführen. Das einzige, das man anpassen muss, ist verbindung zur DB in der `.env` Datei von Laravel. Zudem muss man auch noch die PDO Erweiterung in PHP aktivieren, wenn diese noch nicht aktiv ist

```
Docker Compose hat den Vorteil, dass man das Projekt auf jedem Gerät einrichten ausführen kann.
```bash
// Beim ersten ausführen
docker-compose up --build -d

// Umgebung starten
docker-compose up -d
```

`docker-compose.yml`
```yml
services:
	app:
		build:
			context: .
			dockerfile: Dockerfile
		image: php:8.2-fpm
		container_name: ticket_manager_app
		working_dir: /var/www
		volumes:
			- .:/var/www
		networks:
			- ticket-manager
		depends_on:
			- db

	webserver:
		image: nginx:alpine
		container_name: ticket_manager_laravel_webserver
		ports:
			- "8000:80"
		volumes:
			- .:/var/www
			- ./nginx/default.conf:/etc/nginx/conf.d/default.conf
		networks:
			- ticket-manager
		depends_on:
			- app

	db:
		image: mariadb:10.5
		container_name: ticker_manager_db
		environment:
			MYSQL_DATABASE: ticket-manager
			MYSQL_USER: ticket-manager
			MYSQL_PASSWORD: admin123
			MYSQL_ROOT_PASSWORD: admin123
		ports:
			- "3306:3306"
		volumes:
			- dbdata:/var/lib/mysql
		networks:
			- ticket-manager

networks:
	ticket-manager:
		driver: bridge

volumes:
	dbdata:
		driver: local
```

`Dockerfile`
```dockerfile
FROM php:8.2-fpm

# Install system dependencies

RUN apt-get update && apt-get install -y \
	libpng-dev \
	libjpeg-dev \
	libfreetype6-dev \
	zip \
	unzip \
	git \
	curl \
	libonig-dev \
	libxml2-dev \
	libzip-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install gd

# Install PDO and PDO_MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www
```