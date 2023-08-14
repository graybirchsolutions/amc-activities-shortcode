# AMC Activities Database Shortcode Change Log

## Purpose

A WordPress shortcode plugin to retrieve and display a list of events from the AMC Activities Database.

See the README.md file bundled with this release for details on installation and usage.

This plugin HAS NOT been submitted to the WordPress repository. It is available exclusively here on GitHub at https://github.com/graybirchsolutions/amc-actdb-shortcode/releases/latest.

## Changes
The following changes have been applied through the release history of this plugin.

### v2.0.3
* Fix an issue that prevented display of custom image in expanded event block (#18)
* Make sure that the host has the proper PHP settings on activation (#17)
* Fix an issue that prevented the plugin from activating under PHP 8.1 or higher (#16)

### v2.0.2
* Fix month index value to correct date display (event was shown 1 month later than scheduled)
* Event links now open in a new tab

### v2.0.1
* Fix loader display issue when no events found

### v2.0.0 (Major Release)
* Implement event list rendering via AJAX query
* Implement a local API to avoid cross-origin queries from the browser (AMC API lacks CORS support)
* Improve event rendering to include collapsable event description details.
* Add committee(s) to event summary (useful when querying by Chapter or by Activity type across multiple committees)
* Add support for plugin updates through the WordPress admin page

### v1.0.2
* Change repository links from BitBucket to GitHub

### v1.0.1
* Adding installable zip asset to the release

### v1.0.0 (Major Release)
* Initial Release

## Known Issues
* Limited error handling. Errors from the query response are simply parsed as "No events found". Contact the plugin author if the plugin fails to retrieve events.