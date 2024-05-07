# Example requests

### initialize:

https://harhive.pp.ua/t/src/init.php

### users:

https://harhive.pp.ua/t/src/api/users/get_all.php

https://harhive.pp.ua/t/src/api/users/register.php
{
    "username": "user12345",
    "password": "password12345",
    "email": "user12345@example.com",
    "fullName": "John Doe"
}

https://harhive.pp.ua/t/src/api/users/login.php
{
    "username": "admin",
    "password": "admin123"
}

### sunglasses:

https://harhive.pp.ua/t/src/api/sunglasses/get.php?&limit=3
?manufacturer=Brand
?name=Model
?polarized=true
?offset=3
?sort=price
?sort=price&order=desc

can combinate

https://harhive.pp.ua/t/src/api/sunglasses/add.php
{
    "manufacturer": "Rayan Gosling",
    "name": "Aviator",
    "image": "aviato111r.jpg",
    "polarization": true,
    "description": "Класичні Rayan авіаційні сонцезахисні окуляри з поляризованими лінзами",
    "price": 66.99
}

token needed