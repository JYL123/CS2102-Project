> CS2102

## Get started
You only need to copy htdocs folder to your local computer.

For each php file, you need to change the credentials in the database connection: 
```
host=localhost port=5431/your_port dbname=Project1/db_server_name user=postgres/your_username password=psql/your_password
```

## Structure 
* php files: php files are linked together via buttons with starting point being either `Log in\Sign up as user` or `Log in\Sign up as admin`
* bootstrapMain.php: it is a bootstrap template for the first page of the application. It is supposed to replace main.php
* query.sql: a record for the sql queries we have so far. 
* images folder: storage of all image files

## Progress 
### Done:
* For user:
```
Login page
User page
Sign up page
Function page (apply to be a driver; post an advertisement; bid for an advertisement)
```

* For admin:
```
Login page
Admin page
Sign up page
Function page (add user, delete user, view ad summary (points, origin, destination, doa, adid), view expired ads, view the most popular ad of the week)
```

### ToDo:
```
Functions and Procedures
```
