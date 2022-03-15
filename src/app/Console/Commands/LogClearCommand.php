<?php

namespace Uipps\LaravelLogClear\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;

class LogClearCommand extends Command
{
    /*
     * The name and signature of the console command.
     *
     * @var string

  不要跟如下冲突：
  -h, --help                     Display help for the given command. When no command is given display help for the list command
  -q, --quiet                    Do not output any message
  -V, --version                  Display this application version
      --ansi|--no-ansi           Force (or disable --no-ansi) ANSI output
  -n, --no-interaction           Do not ask any interactive question
      --env[=ENV]                The environment the command should run under
  -v|vv|vvv, --verbose           Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

     */
    protected $signature = 'log:clear
                            {--a|all= : all the logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear log files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 删除所有.log文件
        array_map('unlink', array_filter((array) glob(storage_path('logs/*.log'))));
        $this->comment('Logs have been cleared!');
    }
}
