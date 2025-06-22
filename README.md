<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Overview

Project demonstration Video

https://www.loom.com/share/9f70ab67831e43809168f7b40a789933?sid=04420c4d-19f1-42e6-8f36-7b4268cc2859

All tasks mentioned in the Tasks section of the pdf doc is completed 

- Store translations for multiple locales (e.g., en , fr , es ) with the ability to add new
  languages in the future.
- Tag translations for context (e.g., mobile , desktop , web ).
- Expose endpoints to create, update, view, and search translations by tags, keys, or
  content.
- Provide a JSON export endpoint to supply translations for frontend applications like
  Vue.js.
- Json endpoint should always return updated translations whenever requested.

## Technical Requirements

All technical requirements mentioned in the doc are applied.

- Follow PSR-12 standards and use a scalable database schema.
- Follow SOLID design principles.
- Optimized sql queries.
- Implement token-based authentication to secure the API
- No external libraries for CRUD or translation services should be used.

## Plus points

Due to limited luxury of time, I could only prioritize on essentials.

This way, you can have a better insight of my skills.

But still, docker setup is done, but due to some performance issues i'm having with windows, had to drop it.

## Setup

- .env file has been provided.
- Use your xamp/wamp/laragon to host php
- php artisan migrate:fresh --seed (This will setup all tables along with seeders having 100k+ translations)
- php artisan serve
