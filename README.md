**This repository is archived and no longer maintained. Instead, we recommend using [Carbon](https://packagist.org/packages/nesbot/carbon) with the [business-day mixin](https://packagist.org/packages/cmixin/business-day) which supports all of the functionality of the date-utils library, but with a better API.**

---

# Date Utils

[![Build Status](https://travis-ci.org/ministryofjustice/date-utils.svg?branch=master)](https://travis-ci.org/ministryofjustice/date-utils)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ministryofjustice/date-utils/build-status/master)

Allows for the easy calculation of working days between two points.
Automatically generates UK bank holidays, these can be overridden via config

Basic example

```php
$date = WorkingDays::workingDaysFrom(new \DateTime('2014-01-01'), 1);

//$date will be set to 2014-01-02 which is the next working day
```

### Tests

Build container so we can run PHP 5.4

    docker-compose --project-name moj-date-utils build test
    
Run unit tests

    docker-compose --project-name moj-date-utils run test
