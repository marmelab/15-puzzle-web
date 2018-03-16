# 15-puzzle-web

Play the 15-puzzle game in a web browser, in php (framework Symfony).

> See the [related article](https://marmelab.com/blog/2018/01/08/jeu-du-taquin-en-php.html) on the Marmelab blog

## Help

Print all available commands

```bash
make
```

## Build

Install the dependencies, compile the code and build the dockers

```bash
make install
```

## Run the project

### Start the game

Run the 15-puzzle game

```bash
make run
```

Note: the game will be available at `localhost:8080`

### Stop the game

Stop the 15-puzzle game

```bash
make stop
```

## Contributing

### Test

Launch the unit and integration tests

```bash
make test
```

### Display the logs

Display serveur and db logs

```bash
make logs
```
