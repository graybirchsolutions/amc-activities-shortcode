# AMC Activities Shortcode WordPress Plugin

[AMC Activities Shortcode](https://bitbucket.org/graybirch/amc-actdb-shortcode) is a WordPress plugin to pull and format selected trips/activities from the [AMC Activities Database](https://activities.outdoors.org/).

The AMC provides a simple API that returns a list of activities in XML format for the use of its member chapters. This plugin will format a query to the API and parse the returned list into valid HTML structure that is returned through the shortcode handler for display on the WordPress front end.

## Features

The plugin creates a WordPress shortcode that can be used in any page, post or section that supports shortcodes. The shortcode format is

    [amc_actdb chapter=id committee=id activity=id display=[short|long] limit=n]

The chapter ID is mandatory. All other attributes are optional, but it is recommended to us other parameters (committee, activity or limit) to restrict the list as [amc_actdb chapter=xx] by itself will return **all** future activities from the database.

The ***display*** attribute controls whether the plugin renders the events in short
or long format. 

- *Short* format displays the event title, date, status, type & level and the trip leader name and email. 

- In addition to the details in the short format, the *long* format includes the event description and a featured image if one was provided for the event.

The ***limit*** attribute controls how many events are displayed. The events are returned in chronological order so using limit=5 would restrict the display to the next 5 upcoming events.

Functionality to retrieve prior events is not available in the API.

## Requirements

* PHP >= 7.1
* WordPress >= 5.2

## Installation

1. Download the zip archive of the plugin from the [Bitbucket GIT repository](https://bitbucket.org/graybirch/amc-actdb-shortcode) to your computer or web server.
2. Upload or extract the `amc-actdb-shortcode` folder to your site’s `/wp-content/plugins/` directory. You can also use the *Add new* option found in the *Plugins* menu in WordPress.
3. Activate the plugin from the *Plugins* menu in WordPress.

***Alternative Installation***

1. Login to your web server with your site's admin userid and navigate to your site's '/wp-content/plugins/' directory.
2. Use **git** to pull the archive from the repository using the command 
    ```
    git clone https://bitbucket.org/graybirch/amc-actdb-shortcode.git
    ```
3. Logon to your WordPress installation and activate the plugin from the *Plugins* page.

## Contributors

- Martin Jensen (marty@graybirch.solutions)