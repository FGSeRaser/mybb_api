# MyBB RestAPI

This plugin allows your MyBB board to expose a Public RestAPI.

## Installation

Download the files and upload the content of the `Upload` folder to your MyBB boards root.

Go to `AdminCP > Configuration > Plugins`, locate `MyBB API` and click `Install`

You can now visit the API documentation by going to your MyBB board and appending `api.php`, eg. `https://your_board.com/api.php`.

## Features

This list describes the progress of the plugin, everything below will be implemented unless otherwise mentioned.

### Users

- ✅ GET: Users.php?:id
- ✅ GET: Users.php?:name
- ❌ GET: Users.php
- ❌ GET: Users.php?:page

### Threads
- ✅ POST: Threads_create.php
- ✅ GET: Threads.php?:id
- ✅ GET: Threads.php?:page
- ❌ GET: Threads.php?:author
- ❌ GET: Threads.php?:author&:page
- ❌ GET: Threads.php?:search

Note: the following endpoints are not in planning, but will be considered!

- ❌ PUT: Threads.php?:id

### Posts

- ✅ GET: Posts.php?:id
- ✅ GET: Posts.php?:tid
- ✅ GET: Posts.php?:fid
- ❌ GET: Posts.php?:author
- ❌ GET: Posts.php?:author&:page

Note: the following endpoints aer not in planning, but will be considered!

- ❌ POST: Posts.php
- ❌ PUT: Posts.php?:id

### Groups

- ❌ GET: Groups.php
- ❌ GET: Groups.php?:id
- ❌ GET: Groups.php?:uid

## edited by SeRaser|
Implemented secure API authentication: endpoints now require valid Api-Key (HTTP header) and username (GET/POST), with strict JSON error handling and HTTP status codes. Modular code, ready for central auth handler, all sensitive API endpoints protected.
