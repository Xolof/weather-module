# Weather-module

### Travis CI
[![Build Status](https://travis-ci.com/Xolof/weather-module.svg?branch=main)](https://travis-ci.com/Xolof/weather-module)

### Scrutinizer
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Xolof/weather-module/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Xolof/weather-module/?branch=main)[![Code Coverage](https://scrutinizer-ci.com/g/Xolof/weather-module/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/Xolof/weather-module/?branch=main)[![Build Status](https://scrutinizer-ci.com/g/Xolof/weather-module/badges/build.png?b=main)](https://scrutinizer-ci.com/g/Xolof/weather-module/build-status/main)[![Code Intelligence Status](https://scrutinizer-ci.com/g/Xolof/weather-module/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)

## Installation

Install the module from Packagist.

`composer require xolof/weather-module`

### Copy files

Copy config files.

`rsync -av vendor/xolof/weather-module/config/ ./config/`

Copy tests.

`rsync -av vendor/xolof/weather-module/test/ ./test/`

Copy views.

`rsync -av vendor/xolof/weather-module/view/ ./view/`

Copy javascript and CSS files.

`rsync -av vendor/xolof/weather-module/htdocs/ ./htdocs/`


### Install Leaflet.js in your Anax directory.

Download `leaflet.css` to `htdocs/css`.

Download the directory `images` from leaflet.js and put it in `htdocs/css`.

Add `css/leaflet.css` and `css/weather-module.css` to the config in `config/page.php`.

Download `leaflet.js` and `leaflet.js.map` to `htdocs/js`.


### Add API-keys

Add the file data/GEOTAG_KEY containing your API-key for the nominatim API. [https://nominatim.openstreetmap.org](https://nominatim.openstreetmap.org)

Add the file data/WEATHER_KEY containing your API-key for the openweathermap API. [https://api.openweathermap.org](https://api.openweathermap.org)

The weather service should now be available on the route `/weather`.
