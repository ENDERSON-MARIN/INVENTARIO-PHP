# Project Title
INVENTARIO - PUNTO DE VENTA
# Screenshot
![](public/img/login.png)
![](public/img/lockscreen.png)
![](public/img/dashboard.png)

## Getting Started


### Prerequisites

you need to install following software 
1)	COMPOSER https://getcomposer.org/download/
2)  WEB SERVER (PHP, APACHE)
3)	DATABASE MYSQL
4)  OTHER OPTIONS:
    laragon https://laragon.org/download/index.html
OR
    xammp https://www.apachefriends.org/download.html
OR
	wammp https://sourceforge.net/projects/wampserver/files/latest/download



# The easiest way to get started is to clone the repository:
git clone https://github.com/ENDERSON-MARIN/INVENTARIO-PUNTO-VENTA.git

# Change directory
cd project_dir

# Config enviroments file:
1) DB_CONNECTION=mysql
2) DB_HOST=127.0.0.1
3) DB_PORT=3306
4) DB_DATABASE=Your_Database_Here
5) DB_USERNAME=Your_Username_Here
6) DB_PASSWORD=Your_password_Here


# Install COMPOSER dependencies
composer install

# Install NPM dependencies
npm install

# Compile NPM dependencies
npm run dev

# RUN THE MIGRATIONS:
php artisan migrate

# APP Key Generate:
php artisan key:generate

# Then simply start your app
php artisan serve --port 5000

you can check website will be up and running on localhost at 8000 port.
http://localhost:5000


## Author

* [Enderson Marín](https://github.com/ENDERSON-MARIN)


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Web Site:

* https://www.marinenderson.com
