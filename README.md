# AAXIS PHP Challenge

## Prerequisites
Before running the project, make sure you have Docker instaled.

## Getting Started
### Clone with https
```
git clone https://github.com/BovedaR/aaxis-test.git
cd crud
```

### Clone with ssh
```
git clone git@github.com:BovedaR/aaxis-test.git
cd crud
```

## Setting up the Project

### Generate the keys

* Get "server-php" CONTAINER ID
    ```
    docker ps
    ```

* Execute the following command replacing CONTAINER ID to generate the key pairs for jwt
    ```
    docker exec -it CONTAINER php bin/console lexik:jwt:generate-keypair
    ```

## Run the Project
```
docker-compose up --build
```

### Running the migrations

* Get "server-php" CONTAINER ID
    ```
    docker ps
    ```

* Run the migrations replacing CONTAINER ID

    ```
    sudo docker exec -it CONTAINERID  php bin/console doctrine:migrations:migrate
    ```


## API Docs

To see the API Docs, [click here](./server/README.md).
