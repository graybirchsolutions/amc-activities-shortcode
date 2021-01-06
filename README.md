# AMC Activities Shortcode WordPress Plugin

<<<<<<< HEAD
[AMC Activities Shortcode](https://github.com/graybirchsolutions/amc-actdb-shortcode) is a WordPress plugin to display selected trips/activities from the Appalachian Mountain Club (AMC) [Activities Database](https://activities.outdoors.org/) on your own website.

The AMC provides a simple API that returns a list of activities in XML format for the use of its member chapters. This plugin will format a query to that API and parse the returned list into valid HTML structure that is returned through the shortcode handler for display on the WordPress front end.
=======
[AMC Activities Shortcode](https://github.com/graybirchsolutions/amc-actdb-shortcode) is a WordPress plugin to pull selected trips/activities from the [AMC Activities Database](https://activities.outdoors.org/) and display them in a calendar/agenda format on any WordPress-based website.
>>>>>>> feature/RestAPI

## Features

The plugin creates a WordPress shortcode that can be used in any page, post or section that supports shortcodes. The shortcode format is

    [amc_actdb chapter=id committee=id activity=id limit=n display=short|long]

The chapter ID is mandatory. All other attributes are optional, but it is recommended to use other parameters (committee, activity or limit) to restrict the list as [amc_actdb chapter=xx] by itself will return **all** future activities from the database.

The ***display*** attribute controls whether the plugin renders the events in short or long format. 

- *Short* format displays the event title, date, status, type & level and the trip leader name and email. 

- In addition to the details in the short format, the *long* format includes the event description and a featured image if one was provided for the event.

The ***limit*** attribute controls how many events are displayed. The events are returned in chronological order so using limit=5 would restrict the display to the next 5 upcoming events.

<<<<<<< HEAD
Functionality to retrieve prior events is not available in the AMC API and cannot be added through this plugin.
=======
Functionality to retrieve past events is not available through the AMC's API, so the plugin cannot display past events.
>>>>>>> feature/RestAPI

## Requirements

* PHP >= 7.1
* WordPress >= 5.2

## Installation

<<<<<<< HEAD
1. Download the zip archive of the plugin from the [GitHub GIT repository](https://github.com/graybirchsolutions/amc-actdb-shortcode/releases/latest) to your computer or web server.
=======
1. Download the zip archive of the plugin from the [GitHub repository](https://github.com/graybirchsolutions/amc-actdb-shortcode) to your computer or web server.
>>>>>>> feature/RestAPI
2. Upload or extract the `amc-actdb-shortcode` folder to your site’s `/wp-content/plugins/` directory. You can also use the *Add new* option found in the *Plugins* menu in WordPress.
3. Activate the plugin from the *Plugins* menu in WordPress.

***Alternate Installation***

1. Login to your web server with your site's admin userid and navigate to your site's '/wp-content/plugins/' directory.
2. Use **git** to pull the archive from the repository using the command 
    ```
    git clone https://github.com/graybirchsolutions/amc-actdb-shortcode.git
    ```
3. Logon to your WordPress installation and activate the plugin from the *Plugins* page.

## Notes

This version of the software renders events through the website **backend** when WordPress generates the page.

Page caching causes a copy the rendered page - including the rendered event list - to be stored in a cache database
in order to speed up user access to the website pages. This cached file may contain outdated information until
the cache is refreshed. This will typically manifest as the website not showing recently posted events until the cache
files are regenerated.

If you are using a caching plugin, we recommend setting the cache timeout between 4 and 24 hours to help keep the content current.

In the next version of this plugin the software will add **frontend** rendering via AJAX queries from the user's browser.
Using frontend queries will mean that the browser retrieves upcoming event data in realtime, which means the user should
always see up-to-date event information.

## Affiliation

The author(s) of this plugin are not affiliated with nor endorsed by the Appalachian Mountain Club (AMC). 

The AMC maintains a read-only XML API to allow
committees and chapters to access public data on events posted to the AMC Activities Database at https://activities.outdoors.org.
This plugin has been written to allow committee and chapter websites that use WordPress as their website software
to easily present upcoming event information through the use of this software.

The AMC adds the following disclaimer to it's API:

>The Appalachian Mountain Club has developed this service for web display of chapter trips by AMC Chapters only and expressly disclaims the use of this service for any other purpose. AMC will not be responsible for manipulation or misuse of data available via this service.

## License

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

Please see [http://www.gnu.org/licenses/gpl-3.0.html](http://www.gnu.org/licenses/gpl-3.0.html) for details.

## Contributors

- Martin Jensen (marty@graybirch.solutions)
