##PHP - Speed of Sound

###Calculation of speed of sound in humid air

This is a port into PHP from the original Javascript implementation by Richard Lord (National Physical Laboratory), which can be found here: http://resource.npl.co.uk/acoustics/techguides/speedair/

###What For?

The php class accepts temperature and humidty as arguments, and calculates the resulting speed of sound. This calculation is helpful to people who make musical instruments, for example, where the speed of sound at various temperatures is needed to calculate the wavelength of notes.

From the original introduction on the National Physical Laboratory website:

> The calculator presented here computes the zero-frequency speed of sound in humid air according to Cramer (J. Acoust. Soc. Am., 93, p2510, 1993), with saturation vapour pressure taken from Davis, Metrologia, 29, p67, 1992, and a mole fraction of carbon dioxide of 0.0004.

> Range of validity: the calculator is only valid over the temperature range 0 to 30 ° C (273.15 - 303.15 K) and for the pressure range 75 - 102 kPa

This PHP version accepts temperature in fahrenheit, and uses a default air pressure of 101.325 kPa.

###Usage:

Include the `SpeedOfSound.php` script. You can then use it in any of these ways:

Option 1:

```php
$sos = new \Kassa\Physics\SpeedOfSound(); //using defaults for temp & humidity
$data = $sos->getSpeed(); //returns array including temperature, humidity, and speed of sound
print_r($data);
```

Option 2:

```php
$sos = new \Kassa\Physics\SpeedOfSound(75, 40); //set temperature and humidity
$data = $sos->getSpeed(); //returns array including temperature, humidity, and speed of sound
print_r($data);
```

Option 3:

```php
$sos = new \Kassa\Physics\SpeedOfSound();
$fahrenheit = $sos->toFahrenheit(20); //convert from celsius first
$sos->setEnvironment($fahrenheit, 45); //then set temp and humidity
$data = $sos->getSpeed();
print_r($data);
```

Alternately, you can use the namespace like this:

```php
use Kassa\Physics;
$sos = new SpeedOfSound();
$data = $sos->getSpeed(); //etc...
```

###Return Values

The calculated speed of sound is returned in cm/s.

Data is returned as an array, like this:

```php
Array ( 
  [temperature] => 68
  [humidity] => 50
  [speed] => 34399
)
```

Let me know if you find this useful!

###In Action:

You can see a working example of this on the [Kassa Flutes][1] website, here: https://kassaflutes.com/flute-calc

[1]:https://kassaflutes.com
