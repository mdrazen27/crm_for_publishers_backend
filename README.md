#CRM system for managing publishers
##About
System for publishers to manage their advertisements and related statistics. 

Consists of two parts:
- Backend: Laravel
- Databases: MySql (MariaDB) and Redis
- Frontend: Vue2 + Vuetify2

##Requirements

```
PHP >= 8.1
MySql (used in developement >= 8.0.22, min unknown)
Redis (used in developement >= 7.0, min unknown)
```
___

##Setup
Copy contents from .env.example file into file named .env

Create new database and put credentials into .env 


Run commands in terminal
```
    php artisan key:generate
    php artisan migrate:fresh --seed
    php artisan serve
```



###Command for seeding random data into redis
```
    php artisan generate-statistic-data {parameter}
```
it can be called with optional parameter of type integer (parameter>=1) 
representing number of random entries
___

###Command for migrating data from redis to mysql
```
    php artisan migrate-redis-statistics
```
- scheduled to run every ten minutes
