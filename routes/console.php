<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('backup:run')->daily()->at('01:00');
Schedule::command('backup:clean')->daily()->at('02:00');
