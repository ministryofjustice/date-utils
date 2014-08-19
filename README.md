holiday-dates
=============

*todo
  - [x] Rename to DateUtils
  - [X] Calculate bank holidays for a given year
  - [X] Allow override mechanism via config
  - [X] Calculate x working days from given date

Basic example

```php
$Date = WorkingDays::workingDaysFrom(\DateTime::createFromFormat('d/m/Y', '01/01/2014'),1);

//$Date will be set to 02/02/2014 which is the next working day after
```
