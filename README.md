# Todo Parser Plugin

Todo Parser Plugin is a WordPress plugin that fetches todo items from an API, saves them to the database, and provides functionality to display and manage these todos.

## Features

- Fetches todo items from an external API
- Stores todo items in the WordPress database
- Provides an admin interface to view, search, and update todos
- Includes a shortcode to display random incomplete todos on any post or page

## Installation

1. Download the plugin zip file
2. Upload the plugin to your WordPress site
3. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

### Admin Interface

After activation, you'll find a new menu item "Todo Parser" in your WordPress admin panel. Here you can:

- View all todos
- Search todos by title
- Update todos from the API

### Shortcode

The plugin provides a shortcode to display random incomplete todos on any post or page. 

To use the shortcode:

1. Edit a post or page
2. Add the following shortcode where you want the todos to appear:

   ```
   [random_todos]
   ```

3. By default, this will display 5 random incomplete todos

You can also specify the number of todos to display:

````
[random_todos limit="3"]
````

This will display 3 random incomplete todos.

## Development

This plugin uses Composer for dependency management. If you're making changes to the plugin, make sure to run:

````
composer install
````


This will install the required dependencies (Monolog and PSR-3).

## Logging

The plugin uses Monolog for logging. Log files are stored in the `logs` directory within the plugin folder.


