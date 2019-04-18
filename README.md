<table>
        <tr>
            <td><img width="120" src="https://cdnjs.cloudflare.com/ajax/libs/octicons/8.5.0/svg/rocket.svg" alt="onboarding" /></td>
            <td><strong>Archived Repository</strong><br />
            The code of this repository was written during a <a href="https://marmelab.com/blog/2018/09/05/agile-integration.html">Marmelab agile integration</a>. <a href="https://marmelab.com/blog/2018/01/08/jeu-du-taquin-en-php.html">The associated blog post</a> illustrates the efforts of the new hiree, who had to implement a board game in several languages and platforms as part of his initial learning.<br />
        <strong>This code is not intended to be used in production, and is not maintained.</strong>
        </td>
        </tr>
</table>

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
