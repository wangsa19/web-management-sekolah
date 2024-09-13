<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## How to use
1. download package 

        composer install
        
        npm install


2. Run

        cp .env.example .env

3. Generate key

        php artisan key:generate

4. Migrate

        php artisan migrate

5. Run Serve

        php artisan serve

        npm run dev

6. Create new User

        php artisan make:filament-user

7. Publishing translations

        php artisan vendor:publish --tag=filament-panels-translations