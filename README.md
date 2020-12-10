# AMC Activities Shortcode WordPress Plugin

[AMC Activities Shortcode](https://github.com/graybirchsolutions/amc-actdb-shortcode) is a WordPress plugin to pull selected trips/activities from the [AMC Activities Database](https://activities.outdoors.org/) and display them in a calendar/agenda format on any WordPress-based website.

## Features

The plugin creates a WordPress shortcode that can be used in any page, post or section that supports shortcodes. The shortcode format is

    [amc_actdb chapter=id committee=id activity=id limit=n display=short|long]

The chapter ID is mandatory. All other attributes are optional, but it is recommended to use other parameters (committee, activity or limit) to restrict the list as [amc_actdb chapter=xx] by itself will return **all** future activities from the database.

The ***display*** attribute controls whether the plugin renders the events in short or long format. 

- *Short* format displays the event title, date, status, type & level and the trip leader name and email. 

- In addition to the details in the short format, the *long* format includes the event description and a featured image if one was provided for the event.

The ***limit*** attribute controls how many events are displayed. The events are returned in chronological order so using limit=5 would restrict the display to the next 5 upcoming events.

Functionality to retrieve past events is not available through the AMC's API, so the plugin cannot display past events.

## Requirements

* PHP >= 7.1
* WordPress >= 5.2

## Installation

1. Download the zip archive of the plugin from the [GitHub repository](https://github.com/graybirchsolutions/amc-actdb-shortcode) to your computer or web server.
2. Upload or extract the `amc-actdb-shortcode` folder to your site’s `/wp-content/plugins/` directory. You can also use the *Add new* option found in the *Plugins* menu in WordPress.
3. Activate the plugin from the *Plugins* menu in WordPress.

***Alternate Installation***

1. Login to your web server with your site's admin userid and navigate to your site's '/wp-content/plugins/' directory.
2. Use **git** to pull the archive from the repository using the command 
    ```
    git clone https://github.com/graybirchsolutions/amc-actdb-shortcode.git
    ```
3. Logon to your WordPress installation and activate the plugin from the *Plugins* page.

## Contributors

- Martin Jensen (marty@graybirch.solutions)
