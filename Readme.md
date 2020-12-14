# Categories & Products API REST
## **START PROJECT**

**On root directory:**

1- Build Docker containers
```
$ docker-compose up -d --build
```
2- Access to app container to install packages
```
$ docker exec -it app bash
$ composer update
```
3- Create database, execute migrations and seed database
```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
$ php bin/console doctrine:fixtures:load
```

## **API DOCS**
## **Category**

## *Create new category*
POST http://localhost/api/category - HTTP/1.1 - Content-Type (application/json)

### **Request body:**
### *Parameters:*
- **Name:** string *Required*
- **description:** string *Optional*

**Body:**
```
{
 "name": "Category name",
 "description": "Category description"
}
```
**Response 200:**
```
{
  "timestamp": "2020-12-14T03:43:46+01:00",
  "statusCode": 200,
  "statusText": "OK",
  "data": {
    "id": 1,
    "name": "Category name",
    "description": "Category description"
  }
}
```

## *Update category*
PUT http://localhost/api/category/{categoryID} - HTTP/1.1 - Content-Type (application/json)


### **Query string:**
### *Parameters*
- **categoryID:** int *Required*

### **Request body:**
### *Parameters*
- **Name:** string *Required*
- **description:** string *Optional*

**Body:**
```
{
 "name": "Category name updated"
}
```
**Response 200:**
```
{
  "timestamp": "2020-12-14T03:57:13+01:00",
  "statusCode": 200,
  "statusText": "OK",
  "data": {
    "id": 1,
    "name": "Category name updated",
    "description": null
  }
}
```

## *Delete category*
DELETE http://localhost/api/category/{categoryID} - HTTP/1.1 - Content-Type (application/json)
### **Query string:**
### *Parameters*
- **categoryID:** int *Required*

**Response 200:**
```
{
  "timestamp": "2020-12-14T04:18:04+01:00",
  "statusCode": 200,
  "statusText": "OK",
  "data": "Category deleted correctly"
}
```
## **Products**

## *Create new product*
POST http://localhost/api/product - HTTP/1.1 - Content-Type (application/json)

### **Request body:**
### *Parameters:*
- **name:** string *Required*
- **category:** int (related category ID) *Optional*
- **price:** float *Required*
- **currency:** string (only can be EUR or USD) *Required*
- **featured:** bool (is product featured) *Required*

**Body:**
```
 {
	 "name": "Product name",
	 "category": 5,
	 "price": 20,
	 "currency": "EUR",
	 "featured": false
 }
```
**Response 200:**
```
{
  "timestamp": "2020-12-14T09:22:16+01:00",
  "statusCode": 200,
  "statusText": "OK",
  "data": "Product created correctly"
}
```

## *List all products*
GET http://localhost/api/product - HTTP/1.1 - Content-Type (application/json)

**Response 200:**
```
{
  "timestamp": "2020-12-14T09:29:48+01:00",
  "statusCode": 200,
  "statusText": "OK",
  "data": [
    {
      "id": 9,
      "name": "Nisi.",
      "price": 11,
      "currency": "EUR",
      "categoryName": "Facilis."
    }
  ]
}
```

## *List all featured products*
GET http://localhost/api/product/featured?currency - HTTP/1.1 - Content-Type (application/json)

### **Query string:**
### *Parameters*
- **currency:** string (only can be EUR or USD) *Optional*

If currency parameter is sended, all products will be shown on the currency selected

**Response 200:**
```
{
  "timestamp": "2020-12-14T09:29:48+01:00",
  "statusCode": 200,
  "statusText": "OK",
  "data": [
    {
      "id": 9,
      "name": "Nisi.",
      "price": 11,
      "currency": "EUR",
      "categoryName": "Facilis."
    }
  ]
}
```


## **TEST EXECUTION**
1- Access to app container to run tests
```
$ docker exec -it app bash
```
2- Create test database, execute migrations and seed database
```
$ php bin/console doctrine:database:create --env=test
$ php bin/console doctrine:schema:update --env=test --force
$ php bin/console doctrine:fixtures:load --env=test
```
3- Execute tests
```
$ composer require --dev symfony/phpunit-bridge
$ php ./vendor/bin/simple-phpunit
```