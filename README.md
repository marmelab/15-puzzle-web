# 15-puzzle-web

Play the 15-puzzle game in a web browser.

## Contributing

### Help

Print all available commands

``` bash
make
```

### Install

Initialize the git submodules

``` bash
git module init
git submodule update
```

Note: don't forget to launch `git submodule update` each time the 15-puzzle-go is updated.

Install the dependencies, compile the code and build the dockers

``` bash
make install
```

### Test

Launch the unit and integration tests

``` bash
make test
```

## Run the project

### Start the game

Run the 15-puzzle game

``` bash
make run
```

Note: the game will be available at `localhost:8080`

### Stop the game

Stop the 15-puzzle game

``` bash
make stop
```

### Display the logs

Display serveur and db logs

``` bash
make logs
```
