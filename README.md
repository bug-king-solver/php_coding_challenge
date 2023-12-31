-   Connect to the ‘random user data’ api (https://randomuser.me/api/) and make 10 requests.
-   Extract each user’s full name, phone, email and country
-   Take the batch of API responses and sort them by reverse alphabetical order, according to last name.
-   Convert the sorted, batched data into a valid XML response.
-   Make the above XML response available via an API route/endpoint that you create.
-   Write a phpunit test
-   Add validation and error responses
-   Make your API take a limit as a parameter.
-   Dockerize your application.

How To Deploy

### For first time only !

-   `docker-compose up -d`
-   `docker-compose exec php bash`
-   `composer setup`

### From the second time onwards

-   `docker-compose up -d`
-   `docker-compose exec php bash`
