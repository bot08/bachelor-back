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