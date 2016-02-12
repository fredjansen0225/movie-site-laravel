<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new ImdbScheduleScrapesCommand);
Artisan::add(new ImdbScrapeCommand);
Artisan::add(new ImdbUpdateNowPlayingCommand);
Artisan::add(new ImdbUpdateMovieListCommand);
Artisan::add(new PutlockerScheduleScrapesCommand);
Artisan::add(new PutlockerScrapeCommand);
Artisan::add(new PrimewireScheduleScrapesCommand);
Artisan::add(new PrimewireScrapeCommand);
Artisan::add(new ZMovieScheduleScrapesCommand);
Artisan::add(new ZMovieScrapeCommand);
