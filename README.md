Date Utils
=============
[![Build Status](https://travis-ci.org/brettminnie/date-utils.svg?branch=travis-ci)](https://travis-ci.org/brettminnie/date-utils)

Allows for the easy calculation of working days between two points. Automatically generates UK bank holidays, these can
be overridden via config

Basic example

```php
$Date = WorkingDays::workingDaysFrom(\DateTime::createFromFormat('d/m/Y', '01/01/2014'),1);

//$Date will be set to 02/02/2014 which is the next working day after
```
