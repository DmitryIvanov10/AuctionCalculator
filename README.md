# Auction calculator
## Test app to calculate auction results
The app assumes there is an input of the **auction bids** from **buyers**  
Then it will calculate the **winner** and the **winning bid** depending on the **auction type**  
Currently, the only available **auction type** is **"second-price sealed-bid"**

## Install and run:
1. clone repository to your local host
2. ```docker-compose up --build -d```
3. get inside the container `docker exec -it php sh` 
4. run ```composer install``` inside the container
5. run `tests` using ```php bin/phpunit```
6. alternatively you can set up your IDE to run tests inside the container