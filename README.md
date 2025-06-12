# How to start the website?


### Server
Use OpenServer or another local server. You can also use a remote server.

### Make .env file
Create a file named `.env` in the root directory of the project. You can use the `.env.sample` file as a reference.

### Init DB
You need manual open follow link:
```
https://{domain}/init_db.php
```

## Dev mode

### Installing Bun (if you don't have it)
Follow this link: [Bun Installation](https://bun.sh/docs/installation)

### Install dependencies
```bash
bun install
```

### Run js bundle maker
```bash
bun run js
```
