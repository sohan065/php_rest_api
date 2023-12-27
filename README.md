### Project Setup

First Clone the repository:

```bash
git clone https://github.com/sohan065/php_rest_api.git

```

Go to the project directory.

```bash
cd php_rest_api
```

#### Database Configuration.

Configure your database in `connection.php` file.

```

$serverName = your_server_name;
$database = your_db_name;
$username = your_db_username;
$password = your_db_password;

```

Then run the project if you are in local machine

```bash
php -s localhost:5000
```

It will serve the app on `http://localhost:5000/` .

if you want you can change your port number.

### Review create

open postman and make a http request which method will be `POST` and copy the link bellow if you exactly run your project in `5000` port in your local machine.

```bash
http://localhost:5000/
```

in body, data will be json format and fields are:

```
   user:your_user_id
   product:your_product_id
   text:text
```

### Review get

make a http request which method will be `GET` and will be

```bash
http://localhost:5000/
```

### Review update

open postman and make a http request which method will be `PUT` and copy the link bellow if you exactly run your project in `5000` port in your local machine. pass the review id in query parameter.

```bash
http://localhost:5000/1
```

in body data will be json format and fields are:

```
   user:updated_user_id
   product:updated_product_id
   text:updated_text
```

### Review update

open postman and make a http request which method will be `DELETE` and copy the link bellow if you exactly run your project in `5000` port in your local machine. pass the review id in query parameter which you want to delete

```bash
http://localhost:5000/1
```
