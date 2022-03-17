<h1 align="center">Leaseweb Portal</h1>


## About 
This application to provide a portal through which user can see and choose a server from list of servers, according to their requirement.

#### User can use below filters to search
- By Storage size with a range like 500GB to 8TB.
- By Hard Disk type like SATA, SAS or SSD.
- By Ram size like 2GB, 4Gb and 8GB in a list.
- By available location.




## To Run The Application with Docker
First download the reo from git 
Start the docker with command -`docker-compose up -d`     
Now we have to set up the application, so run the below commands   
1.`docker-compose exec php-apache /bin/bash `   
2.`cd ..`     
3.`composer update --ignore-platform-req=ext-gd`  
4.`php bin/console doctrine:migrations:migrate`  
5. Do a Post API call on `http://localhost:8000/api/server/store` to store server details


Now you can search for the servers with `http://localhost:8000/api/search/servers/` API endpoint 

## API documentation

API documentation added with postman. To see the documentation please goto this https://documenter.getpostman.com/view/19624423/UVsLRRva   link.






