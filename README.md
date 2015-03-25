##Speed of Sound

###Calculation of speed of sound in humid air

This is a port into PHP from the original Javascript function by Richard Lord, National Physical Laboratory which can be found here: http://resource.npl.co.uk/acoustics/techguides/speedair/

From the original introduction:

> The calculator presented here computes the zero-frequency speed of sound in humid air according to Cramer (J. Acoust. Soc. Am., 93, p2510, 1993), with saturation vapour pressure taken from Davis, Metrologia, 29, p67, 1992, and a mole fraction of carbon dioxide of 0.0004.

> Range of validity: the calculator is only valid over the temperature range 0 to 30 ° C (273.15 - 303.15 K) and for the pressure range 75 - 102 kPa

This PHP version accepts temperature in fahrenheit, and uses a default air pressure of 101.325 kPa.

###Usage:

Option 1:

```php
$speed = new \Kassa\Physics\SpeedOfSound(); //using defaults for temp & humidity
$speed->getSpeed(); //return array including temperature, humidity, and speed of sound
```
