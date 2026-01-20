# Game On (Event Booking API)

Game On is an Event Booking API built with CakePHP 5, running on PHP 8.4, MySQL 8, and phpMyAdmin, fully containerised using Docker.

**CakePHP 5 Docs**: [https://book.cakephp.org/5/en/index.html](https://book.cakephp.org/5/en/index.html)

---
## Prerequisites

Before you begin, ensure you have Docker Desktop installed and running/open. All make commands should be ran in game-on's root directory.

## Installation
Run
```
make build-docker 
```
Go to phpMyAdmin @ [localhost:8082](http://localhost:8082/) and create two databases called `game_on` and `game_on_test`.

Run
```
make build-app 
```

Go to [localhost:8080](http://localhost:8080/) - Game on is installed.

#### To view all endpoints
```
make view-routes
```

## Testing

You can use the Postman collection in `postman/Game-on.postman_collection` to test the API.

```
make tests
```

### Unit

```
make unit-tests
```

### Static Analysis 

```
make static-analysis
```

## Notes

The API uses **basic token authentication** for secured endpoints.

- Token for Homer Simpsons (User)
```
c195263efe55ed73c15f0dbd8afa1aa31a88d0f35c14acd790d565210c0a947e
```

- When you create a **new user** via the API, the token will be returned in the response under:

```json
{
  "_client_token": "GENERATED_TOKEN_HERE"
}
```
- The token is already included in the **Postman request headers**, so you don’t need to manually add it.
  
