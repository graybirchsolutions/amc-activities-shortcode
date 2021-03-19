# AMC Activities Shortcode WordPress Plugin

[AMC Activities Shortcode](https://github.com/graybirchsolutions/amc-activities-shortcode) is a WordPress plugin to display selected trips/activities from the Appalachian Mountain Club (AMC) [Activities Database](https://activities.outdoors.org/) on your own website.

The AMC provides a simple API for the use of its member chapters that returns a list of upcoming activities. A user can ask for events using specific chapter, committee and/or activity codes. For example, a user might request events from the Boston Chapter + Hiking Committee or the Boston Chapter + Hiking Activities.

This plugin implements a WordPress `shortcode` that queries the API and displays the results on a page or a post as a list of upcoming events.

## Features

The plugin implements a WordPress shortcode that can be used in any page, post or section that supports shortcodes. The shortcode format is

    [amc_activities chapter=id committee=id activity=id limit=n]

The chapter ID is mandatory. All other attributes are optional, but it is recommended to use other parameters (committee, activity or limit) to restrict the list as `[amc_activities chapter=xx]` by itself will return **all** future chapter activities from the database.

The ***limit*** attribute controls how many events are displayed. The events are returned in chronological order so using limit=5 would restrict the display to the next 5 upcoming events.

Functionality to retrieve prior events is not available through the AMC's API and cannot be added through this plugin.

Additional documentation - including a list of valid codes - can be found on our Wiki page at https://github.com/graybirchsolutions/amc-activities-shortcode/wiki

## Requirements

* PHP >= 7.1
* WordPress >= 5.2

## Installation

You can find the latest release of the plugin at https://github.com/graybirchsolutions/amc-activities-shortcode/releases/latest

1. Download the `amc-activities-shortcode.zip` file from the release page to your computer. (*Do NOT download the source files zip*)
2. Log in to your WordPress admin dashboard and navigate to Plugins page and click '**Add New**'
3. From the Add Plugins page, click the '**Upload Plugin**' button at the top and select the zip file you downloaded in step 1.
4. When the plugin is installed, go to the Plugins page and activate the plugin

There is no admin or settings page. Basic usage instructions are provided in the plugin description on the Plugins page. Additional documentation can be found in the project Wiki at https://github.com/graybirchsolutions/amc-activities-shortcode/wiki.


## Notes

For additional details - including a list of the Chapter, Committee and Activity codes - please see the project Wiki at https://github.com/graybirchsolutions/amc-activities-shortcode/wiki.

If you find a bug or are having difficulty getting the plugin working, please open an Issue (bug report) on the GitHub project page at https://github.com/graybirchsolutions/amc-activities-shortcode/issues. (You may need to be logged in to GitHub to add a bug report).

## Affiliation

The author(s) of this plugin are not affiliated with nor endorsed by the Appalachian Mountain Club (AMC). 

The AMC maintains a read-only API to allow its committees and chapters to access public data on events posted to the AMC Activities Database at https://activities.outdoors.org.

This plugin has been written to allow committee and chapter websites that use WordPress as their website software to easily query the AMC's API and present upcoming event information on their website through the use of this software.

The AMC adds the following disclaimer to its API:

>The Appalachian Mountain Club has developed this service for web display of chapter trips by AMC Chapters only and expressly disclaims the use of this service for any other purpose. AMC will not be responsible for manipulation or misuse of data available via this service.

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

Please see [http://www.gnu.org/licenses/gpl-3.0.html](http://www.gnu.org/licenses/gpl-3.0.html) for details.

## Contributors

- Martin Jensen (marty@graybirch.solutions)
