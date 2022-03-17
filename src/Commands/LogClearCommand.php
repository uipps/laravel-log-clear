<?php
/*

 php artisan log:clear --dry-run

 php artisan log:clear --dry-run --keepfiles=1

 php artisan log:clear --keepfiles=1

*/

namespace Uipps\LaravelLogClear\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Config\Repository;

use Symfony\Component\Finder\Finder;

class LogClearCommand extends Command
{
   /*

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
                            {--d|dry-run : Run without actually delete any log}
                            {--k|keepfiles= : The number of log files to keep when deleting old log files}';

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
        $this->info(date('Y-m-d H:i:s') . ' begin: ');

        $path_log = storage_path('logs/');

        if ($this->option('dry-run'))
            $this->info('This is a dry-run. Deleting not work. It Just prints some informations!');

        // get all log files
        $files = $this->getAllLogFiles($path_log);

        // give diff info by number. 依据文件数量，给出不同的信息提示
        $total_num = $files->count();
        if ($total_num < 1) {
            $this->info('  There is no file in ' . $path_log);
            return ;
        }
        if (1 == $total_num)
            $this->info('  There is one file in ' . $path_log);
        else
            $this->info('  There are ' . $total_num . ' files in ' . $path_log);

        // 如果要求保留几个文件
        $logFilesToDelete = $files;
        if ($this->option('keepfiles')) {
            $num_keep = $this->getFilesToKeep();
            if ($num_keep > 0) {
                $this->info('  You want keep ' . $num_keep . ' file(s)');
                $logFilesToDelete = $files->slice($num_keep);            // 切片，从数组开头删除几个文件
            }
        }

        // 进行文件删除操作，并输出相应日志。
        //array_map('unlink', $logFilesToDelete->toArray());    // 这样也行，但是没有日志输出
        $msg = '';
        foreach ($logFilesToDelete as $logFile) {
            // \Symfony\Component\Finder\SplFileInfo $logFile
            $msg .= $this->deleteFile($logFile->getRealPath()) . PHP_EOL;
        }
        if ($msg)
            $this->comment($msg);

        $this->info(date('Y-m-d H:i:s') . ' end!');
        return ;
    }

    private function getAllLogFiles($dirctory) {
        // 修改时间排序
        return Collection::make(
            Finder::create()->files()->ignoreDotFiles(true)->in($dirctory)->sortByModifiedTime()->reverseSorting()
        ); // 将最前面的N项删除

        // 也可用
//        return Collection::make(
//            Finder::create()->files()->ignoreDotFiles(true)->in(storage_path('logs/'))
//        )->sort(function ($file1, $file2) {
//            if ($file1->getMTime() > $file2->getMTime()) return 1;
//            else if ($file1->getMTime() < $file2->getMTime()) return -1;
//            else return 1;
//        })->slice($this->getFilesToKeep());
    }

    // number of logs to keep
    protected function getFilesToKeep() {
        if ($this->option('keepfiles'))
            return (int) $this->option('keepfiles');
        return 0;
    }

    protected function deleteFile($filePath) {
        if ($this->option('dry-run'))
            return $filePath . ' would be deleted.';
        unlink($filePath);
        return $filePath . ' has been deleted.';
    }
}
