# BEA API Visual Explorer

The Bureau of Economic Analysis (BEA) publishes economic statistics in a
variety of formats. BEA Data Retrieval Application Programming Interface
includes detailed instructions for retrieving data and meta-data published 
by BEA. The API is intended to provide programmatic access to
published economic statistics using industry-standard methods and procedures.
The API is not very complicated but responses include many additional parameters.
This project helps visually present the data. Users could find needed data and
appropriate parameters for API requests.


## Getting Started

1) Get 36-character UserID on the [https://apps.bea.gov/API/signup/index.cfm] (https://apps.bea.gov/API/signup/index.cfm)
Write your BeaApi Key in config file:

*/src/public/config.php*

```
<?php

define("BEA_KEY", 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX', true);

define("BEA_URL", 'http://www.bea.gov/api/data');
```

2) Run php server from */src/public* directory by command:

```
php -S localhost:8080
```
This server at the backend will make request to the BEA API Server.

3) Start browser and follow url:

```
localhost:8080/bea
```

4) Click on datasets' names to get list of available parameters. Also you can
click on parameters' names to get availale values for them.



## Author

* **Andreas Gesundberg**
