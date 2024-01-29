# PHP & Vue.js Application

## Description

This application is built with PHP and Vue.js. It includes two main parts:
1. A PHP App for REST Apis
2. A Vue.js frontend that interacts with PHP

### PHP App
A PHP app that provides Rest APIs for retrieving a subscriber and inserting a new subscriber.

#### Read existing subscribers:
```
Endpoint:  'mailerlite/v1/subscribers'
Method: 'GET'
Content-Type: 'application/json'
```

#### Validate and insert a new given subscriber:
```
Endpoint: 'mailerlite/v1/subscriber'
Method: 'POST'
Content-Type: 'application/json'
```
##### Payload:
```json
{
    "email": "subscriber@example.com",
    "name": "Subscriber Name",
    "last_name": "Subscriber Name",
    "status": "active|inactive"
}
```

### Vue.js frontend

The frontend interacts with the Rest APIs from the PHP app. After validating, it displays a list of subscribers with pagination and support for adding a new subscriber.

## Prerequisites

Before you begin, ensure you have met the following requirements:
- You have installed Docker and added support for Docker Compose commands. Instructions are available [here](https://docs.docker.com/compose/install/).
- You have installed GNU Make utility, which is commonly used to automate the build process of software projects. Make is often pre-installed if you're using a Unix-like system (such as Linux or macOS). Run this command to verify the installation:
`make --version`

## Installation

### Make Commands

#### Setup an APP
```
make fresh
```

`make fresh` command will select the required database image based on the operating system you are using. It will also copy the required environment files.

Make sure that locally 8000, 3306, and 8080 are not in use. The app relies on these ports to be available. Fresh installation will set up `node_modules`, and `composer packages`. It also has `build scripts` in place.

Once you execute `make fresh`, Kindly monitor running services via `make ps`. 

You may run `make check-vue-ready` to validate the status of the frontend application.

This command will continue logging the following messages on the screen.
```
Checking if Vue.js application is ready...
Waiting for Vue.js application...
Waiting for Vue.js application...
Waiting for Vue.js application...
```
Once the app is ready it will log the following message on the screen.
```
Checking if Vue.js application is ready...
Waiting for Vue.js application...
Vue.js application is ready.
```

#### Other useful make commands
```
fresh                     Make Fresh Docker Setup Based on OS Environment
start                     Start Docker Environment
ps                        List Docker Containers
check-vue-ready           Check if Vue.js application is ready
monitor-php-logs          Monitor PHP Logs
monitor-db-logs           Monitor DB Logs
monitor-vue-logs          Monitor Vue Logs
down                      Stop Docker Environment
ssh-php                   SSH into PHP Container
ssh-mysql                 SSH into Mysql Container
install-composer-dev      Install Composer Dev Dependencies
generate-test-report      Generate Test Report
seed-subscribers          Insert fake subscribers into database
cleanup                   Cleanup Docker Environment including Volumes
```

### Manually Using Docker Compose Commands

#### Setup Env Files
```
cp .env.example .env
cp ./app/.env.example ./app/.env
```

#### MacOs - Build and Run
```
DB_IMAGE=mariadb:10.2 docker compose up --build -d
```

#### Other - Build and Run
```
DB_IMAGE=mysql:5.7 docker compose up --build -d
```

## Usage

Once the app is installed and running. Kindly navigate to http://localhost:8080/ to use.
