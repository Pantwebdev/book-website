protected function schedule(Schedule $schedule)
{
    $schedule->command('generate:sitemap')->daily();
}