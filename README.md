# anychart simple php integration sample

## Project structure
* public/ - static and dynamic resources
* public/index.html - dashboard html
* public/jsx - react jsx sources
* public/js - javascript sources
* public/init.php - initial data for filters
* public/data.php - data getter for charts with specific filters

## Requirenments
* php 5.3+
* mysql 5.0+
* babel (optional) for converting jsx to js http://babeljs.io

## Database setup
MySQL:

    CREATE DATABASE anychart_sample CHARACTER SET utf8;
    GRANT ALL PRIVILEGES ON anychart_sample TO user@localhost IDENTIFIED BY 'pass';
    FLUSH PRIVILEGES

Bash: `mysql -u user -p anychart_sample < dump.sql`

## Running on server
Copy `public` folder to your server and open folder in browser.

## Running locally
Best way to run sample on your computer is to use vagrant. 
We provide simple Vagrantfile with simple shell provision. Just run `vagrant up` and open http://localhost:8080
