# Guide to Running the Project

## Database

The project uses the test PostgreSQL database on our VPS.

## **PHP** 7.3

Use the latest version of **PHP** 7.3. Download it here:

https://windows.php.net/download/
https://windows.php.net/downloads/releases/php-7.3.17-nts-Win32-VC15-x64.zip (7.3.17)

## Symfony and the Local Server

To start a local server in the project directory, serve the /public directory.
Alternatively, install the symfony-cli tool from <https://symfony.com/download>.

Then run `symfony server:start --no-tls` in the project directory.

A live version of the project is also available at <https://edt.sachafroment.fr>.

## Documentation

Documentation for installing the environment is available in `/docs/`.

## Downloading Dependencies

Install **composer** to download the dependencies automatically.
Then run `composer update`.

## Code

Most of the relevant code is in these directories:

- `/src/`
- `/templates/`
- `/config/packages/easy_admin.yaml`
