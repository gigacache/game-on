# Game On (Event Booking API)

Game On is an Event Booking API built with CakePHP 5, running on PHP 8.4, MySQL 8, and phpMyAdmin, fully containerised using Docker.

---
## Prerequisites

Before you begin, ensure you have Docker Desktop installed and running/open. All make commands should be ran in game-on's root directory.

## Installation

```
make build-docker 
```
Go to phpMyAdmin @ [localhost:8082](http://localhost:8082/) and create a database called `game_on`.

```
make build-app 
```

Go to [localhost:8080](http://localhost:8080/)

