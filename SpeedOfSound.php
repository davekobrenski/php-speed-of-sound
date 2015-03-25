<?php
namespace Kassa\Physics;
/**
 * Calculation of speed of sound in humid air.
 *
 * This is a port into PHP from the original Javascript function by Richard Lord, National Physical Laboratory
 * which can be found here: http://resource.npl.co.uk/acoustics/techguides/speedair/
 *
 * From the original introduction:
 * The calculator presented here computes the zero-frequency speed of sound in humid air
 * according to Cramer (J. Acoust. Soc. Am., 93, p2510, 1993), with saturation vapour pressure
 * taken from Davis, Metrologia, 29, p67, 1992, and a mole fraction of carbon dioxide of 0.0004.
 *
 * Range of validity: the calculator is only valid over the temperature range 0 to 30 ° C (273.15 - 303.15 K) and for the pressure range 75 - 102 kPa
 *
 * This php port accepts temperature in fahrenheit, and uses a default air pressure of 101.325 kPa
 *
 * @author php version: Dave Kobrenski <dave@kassaflutes.com>
 * @link http://kassaflutes.com
 * @license MIT
 */
class SpeedOfSound
{
  /** default temperature in fahrenheit */
  public $temperature = 68;

  /** default percent humidity in degrees */
  public $humidity = 50;
  public $pressure;
  public $data = array();

  /** atmospheric pressure in kPA */
  const PRESSURE = 101.325;

  /** for converting temps to absolute kelvin */
  const KELVIN = 273.15;
  const E = 2.71828182845904523536;

  public function __construct($temperature = null, $humidity = null)
  {
    $this->setEnvironment($temperature, $humidity);
  }

  public function setEnvironment($temperature, $humidity = null)
  {
    $this->__setPressure();
    if($temperature) $this->temperature = $temperature;
    if($humidity) $this->humidity = $humidity;
  }

  public function getSpeed()
  {
    $this->data["errors"] = array();
    $this->data["temperature"] = $this->temperature;
    $this->data["humidity"] = $this->humidity;

    if(!$this->__checkTemperature()) {
      $this->data["errors"][] = "Temperature must be between 32-86&deg;F (0-30&deg;C)";
    }

    if(!$this->__checkHumidity()) {
      $this->data["errors"][] = "Relative humidity must be between 0 and 100%";
    }

    if(count($this->data["errors"])) {
      $this->data["speed"] = 0;
      return $this->data;
    } else {
      $T = $this->__fahrenheitToCelsius();
      $Rh = $this->humidity;
      $P = $this->pressure;
      $T_kel = self::KELVIN + $T;
      $e = self::E;

      //Molecular concentration of water vapour calculated from Rh:
      $ENH = 3.14 * pow(10,-8) * $P + 1.00062 + pow($T,2) * 5.6 * pow(10,-7);

      $PSV1 = pow($T_kel,2) * 1.2378847 * pow(10,-5) - 1.9121316 * pow(10,-2) * $T_kel;
      $PSV2 = 33.93711047 - 6.3431645 * pow(10,3) / $T_kel;
      $PSV = pow($e, $PSV1) * pow($e,$PSV2);
      $H = $Rh * $ENH * $PSV / $P;
      $Xw = $H / 100.0;
      $Xc = 400.0 * pow(10,-6);

      //Speed calculated using the method of Cramer / JASA vol 93 pg 2510
      $C1 = 0.603055 * $T + 331.5024 - pow($T,2) * 5.28 * pow(10,-4) + (0.1495874 * $T + 51.471935 - pow($T,2) * 7.82 * pow(10,-4)) * $Xw;
      $C2 = (-1.82 * pow(10,-7) + 3.73 * pow(10,-8) * $T - pow($T,2) * 2.93 * pow(10,-10)) * $P + (-85.20931-0.228525 * $T + pow($T,2) *5.91 * pow(10,-5)) * $Xc;
      $C3 = pow($Xw,2) * 2.835149 + pow($P,2) * 2.15 * pow(10,-13) - pow($Xc,2) * 29.179762 - 4.86 * pow(10,-4) * $Xw * $P * $Xc;
      $C = $C1 + $C2 - $C3;

      $this->data["speed"] = round((round($C * pow(10,2)) / pow(10,2)) * 100);
      unset($this->data["errors"]);
      return $this->data;
    }
  }

  /** convert to fahrenheit from celsius if needed */
  public function toFahrenheit($c)
  {
    return ($c *  (9/5)) + 32;
  }

  private function __fahrenheitToCelsius()
  {
    $f = $this->temperature;
    return ($f - 32) * (5/9);
  }

  private function __checkTemperature()
  {
    if(is_numeric($this->temperature) && $this->temperature <= 86 && $this->temperature >=32)
      return true;
      else
      return false;
  }

  private function __checkHumidity()
  {
    if(is_numeric($this->humidity) && $this->humidity <= 100 && $this->humidity >=0)
      return true;
      else
      return false;
  }

  private function __setPressure()
  {
    $this->pressure = self::PRESSURE * 1000.0;
  }
}

?>
