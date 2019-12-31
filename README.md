# Signup form for OnApp with vCloud Director
This is a simple Laravel app using a Bootstrap UI to allow users to signup to OnApp. This will create an Organization in OnApp and vCloud Director with your chosen Company Name and Buckets and then create a user inside of this Organization.

You will then be able to login to OnApp with the credentials supplied and deploy your resources from Orchestration Models or each component (eg, Resource Pool, Org Network, etc) seperately.

## Requirements
 - Web Server (Tested with Nginx)
 - PHP 7.2+
 - Composer
## Installation
1. Clone the repository to your web directory
```sh
git clone https://github.com/jimfreeman/onapp-vcd-signup.git
```
2. Go to the directory and run:
```sh
composer install
```
3. Create the ```.env``` file:
```sh
cp .env.example .env
```
4. Generate application key:
```sh
php artisan key:generate
```
5. Ensure your files have the correct permissions and owner:
```sh
chown -R www-data:www-data /var/www/html/onapp-vcd-signup/
chmod -R 755 /var/www/html/onapp-vcd-signup/
```
6. Ensure your webroot (in your web server configuration file) is set to ```project_directory```/public
7. Open the ```config/onapp_signup.php``` file and enter the details about your own environment:
```php
<?php

return [
    'onapp_protocol' => 'https', // select either http or https
    'onapp_url' => 'xxx', // the url of your onapp instance. eg. cp.onapp.test
    'onapp_username' => 'xxx', // username of an OnApp user with administrator permissions
    'onapp_password' => 'xxx', // the password for the above user
    'onapp_compute_resource_id' => 0, // the compute resource ID of your vCloud Director instance
    'onapp_bucket_id' => 0, // the bucket ID you want to use for newly created accounts
    'onapp_role_id' => 0, // the role ID of the role you want new users to be created as. Recommended: 'vCloud Organization Administrator'
];
```
8. Restart your web server and access your IP/FQDN via a web browser.

![Signup Form](https://github.com/jimfreeman/onapp-vcd-signup/blob/master/image.png)

## Issues

Let me know: jim.freeman@onapp.com
