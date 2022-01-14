## Description
Web service that fetches current weather data from https://www.weatherapi.com and store it in the DB
The app is based on `Laravel 8`

## Installation
### Clone the repo
`git clone https://github.com/amaynut/weather_app.git`
`cd weather app`
### install the composer packages
`composer install`
### spin up docker containers
The app is shipped with 2 containers: a mysql container and a php 8 container (ubuntu 21.4)
Run : `bash ./vendor/bin/sail up` on Windows
or : `./vendor/bin/sail up` on Unix (Linux or Mac OS)
### config
The config file is `.env` and it is committed for testing purposes 
The DB credentials are
```sh
DB_DATABASE=weather_app
DB_USERNAME=sail
DB_PASSWORD=password
```
### migrate the DB
The db has two tables `weather_data` (stores the historical weather data) and `locations` (stores the cities)
The follow command should create the 2 table and seed the locations table with Montreal data as an example
`docker-compose exec -it weather_app_laravel.test_1 php artisan migrate --seed`
Locations table
```sql
create table locations
(
	id bigint unsigned auto_increment
		primary key,
	name varchar(255) not null,
	region varchar(255) not null,
	country varchar(255) not null,
	lat double(8,2) not null,
	lon double(8,2) not null,
	tz_id varchar(255) not null,
	created_at timestamp null,
	updated_at timestamp null
)
collate=utf8mb4_unicode_ci;
```
Populate the location table
```sql
INSERT INTO weather_app.locations (id, name, region, country, lat, lon, tz_id, created_at, updated_at) VALUES (1, 'Montreal', 'Quebec', 'Canada', 45.5, -73.58, 'America/Toronto', '2022-01-13 23:34:37', '2022-01-13 23:34:37');
```
weather_data table
```sql
create table weather_data
(
	id bigint unsigned auto_increment
		primary key,
	location_id bigint unsigned not null,
	last_updated_epoch timestamp not null,
	last_updated datetime not null,
	temp_c double(8,2) not null,
	temp_f double(8,2) not null,
	is_day tinyint(1) not null,
	condition_text varchar(255) not null,
	condition_icon text not null,
	condition_code int unsigned not null,
	wind_mph double(8,2) not null,
	wind_kph double(8,2) not null,
	wind_degree int not null,
	wind_dir varchar(255) not null,
	pressure_mb double(8,2) not null,
	pressure_in double(8,2) not null,
	precip_mm double(8,2) not null,
	precip_in double(8,2) not null,
	humidity int unsigned not null,
	cloud int unsigned not null,
	feelslike_c double(8,2) not null,
	feelslike_f double(8,2) not null,
	uv double(8,2) not null,
	gust_mph double(8,2) not null,
	gust_kph double(8,2) not null,
	constraint weather_data_location_id_foreign
		foreign key (location_id) references locations (id)
)
collate=utf8mb4_unicode_ci;
```

### fetch the API data and store it
`docker-compose exec -it weather_app_laravel.test_1 php artisan weather:store Montreal`

### setup a cronjob 
Install cron and vim inside the container
`docker-compose exec -it weather_app_laravel.test_1 apt update && apt install cron vim`
Schedule the cron to run every 30 minutes
Run `docker-compose exec -it weather_app_laravel.test_1 crontab -e`
Then add : `*/30 * * * * php artisan weather:store Montreal`

### fetch the data manually through an API end-point
call `http://localhost/api/weather/fetch?location=Montreal`
Example response 
```json
{
	"last_updated_epoch": "2022-01-14T15:30:00+00:00",
	"location_id": 1,
	"last_updated": "2022-01-14 10:30",
	"temp_c": -9,
	"temp_f": 15.8,
	"is_day": 1,
	"condition_text": "Sunny",
	"condition_icon": "\/\/cdn.weatherapi.com\/weather\/64x64\/day\/113.png",
	"condition_code": 1000,
	"wind_mph": 9.4,
	"wind_kph": 15.1,
	"wind_degree": 350,
	"wind_dir": "N",
	"pressure_mb": 1020,
	"pressure_in": 30.13,
	"precip_mm": 0,
	"precip_in": 0,
	"humidity": 65,
	"cloud": 0,
	"feelslike_c": -15.1,
	"feelslike_f": 4.9,
	"uv": 2,
	"gust_mph": 13.4,
	"gust_kph": 21.6,
	"id": 16
}
```

 


