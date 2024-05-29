# Example requests

### initialize:

```
/src/init.php
```

### users:

```
/src/api/users/get_all.php
```

```
/src/api/users/get_user.php?token=...
```

```
/src/api/users/register.php

{
    "username": "user12345",
    "password": "password12345",
    "email": "user12345@example.com",
    "fullName": "John Doe"
}
```

```
/src/api/users/login.php

{
    "username": "admin",
    "password": "admin123"
}
```

### sunglasses:

```
/src/api/sunglasses/get.php
?id=1
?&limit=3
?manufacturer=Brand
?name=Model
?polarized=true
?offset=3
?sort=price
?sort=price&order=desc
```

can combinate

```
/src/api/sunglasses/add.php

{
    "manufacturer": "Rayan Gosling",
    "name": "Aviator",
    "image": "aviator123.jpg",
    "polarization": true,
    "description": "Класичні Rayan авіаційні сонцезахисні окуляри з поляризованими лінзами",
    "price": 66.99
}
```

token needed

```
/src/api/sunglasses/update.php

{
    "id": 1,
    "price": 29.99
}
```

token needed

```
/src/api/sunglasses/delete.php

{
    "id": 2
}
```

token needed

### accessories:

```
/src/api/accessories/get.php
?id=1
?&limit=3
?manufacturer=Brand
?name=Model
?offset=3
?sort=price
?sort=price&order=desc
```

```
/src/api/accessories/add.php

{
    "manufacturer": "Dawn house",
    "name": "Tryapka",
    "image": "321321.jpg",
    "description": "sakdnflk f ladslf jdsh fhdjl fhsdjl; fjlsdh fjlshdflj",
    "price": 6.99
}
```

token needed

```
/src/api/accessories/delete.php

{
    "id": 2
}
```

token needed

### frames:

```
/src/api/frames/get.php
?&limit=3
?manufacturer=Brand
?name=Model
?offset=3
?sort=price
?sort=price&order=desc
```

```
/src/api/frames/add.php

{
    "manufacturer": "ТОВ Люксгласс",
    "name": "Сірий метеорит",
    "image": "/database/image/a.png",
    "description": "Опис рамки 123",
    "price": 123.45
}
```

token needed

```
/src/api/frames/delete.php

{
    "id": 2
}
```

token needed

### lens:

```
/src/api/lens/get.php
...
```

```
/src/api/lens/add.php
...
```

token needed

### orders:

```
/src/api/orders/add.php
{
    "order": {
        "deliveryAddress": "123 Main St, Kyiv",
        "fastDelivery": true,
        "totalAmount": 200.00,
        "frameID": 2,
        "lensID": 3,
        "dioptersLeft": 1.5,
        "dioptersRight": 2.0,
        "astigmatismLeft": 0.75,
        "astigmatismRight": 1.0,
        "dp": 63,
        "lensDescription": "Corrective Lens",
        "lensPrice": 50.00,
        "quantity": 1,
        "unitPrice": 150.00
    }
}
```

token needed

```
/src/api/orders/get.php
?token=token
```

token needed
