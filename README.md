freebase-php
============

Sample PHP script that reads Person data via the FreeBase.com API and builds a local Person object, using a name
keyed in to the input form. This script only extracts some of the data to build its domain object, but also allows you to see the entire JSON result from FreeBase.com.

The script takes a Person name and calls the FreeBase REST service for a summary of that Person, if found.

Requirements
------------
* Apache WS or IIS
* PHP 5.3+
* PHP-Curl lib enabled
* You will need a valid Google Developer API key. Get one here: https://developers.google.com/apis-explorer/

Installing
------------
* Copy the contents of the 'freebase' directory to your web server.
* Browse to http://yourserver/freebase.

Helpful Links
------------
* The Freebase API documentation can be found here: https://developers.google.com/freebase/


Notes
------------
I originally wrote a version of this script to extract Author data for my site, http://www.worldofquotes.com. You can see it presented here for reference: http://www.worldofquotes.com/author/Mahatma+Gandhi/1/index.html
