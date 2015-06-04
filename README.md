# Date Utils

[![Build Status](https://travis-ci.org/ministryofjustice/date-utils.svg?branch=master)](https://travis-ci.org/ministryofjustice/date-utils)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/build-status/master)

Allows for the easy calculation of working days between two points.
Automatically generates UK bank holidays, these can be overridden via config

Basic example

```php
$Date = WorkingDays::workingDaysFrom(new \DateTime('2014-01-01'), 1);

//$Date will be set to 2014-01-02 which is the next working day
```
