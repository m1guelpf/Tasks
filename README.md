<p align="center"><img align="center" src="https://i.imgur.com/CXIG1sI.png"></p>

# Tasks [![StyleCI Badge](https://styleci.io/repos/74145671/shield?style=flat-square&branch=master)](https://styleci.io/repos/74145671/)
Simple tasks & notes manager written in PHP, jQuery and Bootstrap using a custom flat file database.

## What is Tasks?

Tasks is an script that allows you to manage tasks and notes.

## Requirements:

- PHP 5.5.9 or higher

## Installation:

### Deploy to Heroku:
[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/m1guelpf/Tasks/tree/heroku)

### Managed Install:

You can purchase a managed install on [Gumroad](https://gum.co/tasks-installation).

### Manual install:
- Download lastest release from [here](https://github.com/m1guelpf/Tasks/archive/master.zip).
- Upload all the files to your server.
- Edit site name, timezone, site URL, site email and language at includes/config.php
- Access the script and create an account using the register form.
- OPTIONAL: If you want a private install, change 
```php
$signupstatus = true;
```
to 
```php
$signupstatus = false;
```
to disable the signup form.
- Enjoy

## Support:

- If you have any problems when instaling/using the script, [open a ticket](https://support.miguelpiedrafita.com) at my support center.
- If you find any error in the code, [open an issue](https://github.com/m1guelpiedrafita/Tasks/issues/new) or, if you know how to solve it, [make a pull request](https://github.com/m1guelpiedrafita/Tasks/compare).
- If you have new ideas for this script, go ahead and post them in [MP Feedback](http://feedback.miguelpiedrafita.com), under the "Tasks" section.

## Credits:

- [Miguel Piedrafita](https://projects.miguelpiedrafita.com)
- [PHP](https://php.net)

Copyright (C) Miguel Piedrafita. Use of this work is subject to Mozilla Public License 2.0
