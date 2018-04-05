> RouteSharing

## Get started
You only need to copy `htdocs` folder to your local computer.

For each php file, you need to change the credentials in the database connection: 
```
host=localhost port=5431/your_port dbname=Project1/db_server_name user=postgres/your_username password=psql/your_password
```

## Structure 
* php files: php files are linked together via buttons with starting point being either `Log in\Sign up as user` or `Log in\Sign up as admin`
* bootstrapMain.php: it is a bootstrap template for the first page of the application. It is supposed to replace main.php. [The integration between the bootstrap and the old php files has to be discussed].
* query.sql: a record for the sql queries we have so far. 
* background.css: this css file is used for each php file. It is supposed to be replaced by bootstrap. 
* images folder: storage of all image files

## Progress 
### Done:
* For user:
```
index page
User page
```

* For admin:
```
index page
adminPortal page
adminManagePage up page
```

### ToDo:
```
Functions and Procedures
```
