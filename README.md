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
`yes`
 


