<?php

namespace App\Console\Commands;

use App\AccessLog;
use App\AggregateLog;
use Log;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AccessLogAggregate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '昨日のアクセスログを集計する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @throws \Throwable
     */
    public function handle()
    {
        try {

            //定期ジョブスタートログ出力
            Log::channel('batchLog')->info(config('messages.batchStart'));

            //ファイル名取得
            $filename = config('values.apiLogFileName') . Carbon::now()->subDay()->format('Y-m-d') . config('values.logsExtension');

            $filePath = storage_path(config('values.apiLogFile')) . $filename;
            //ログファイルが取得できなかった場合例外を投げる
            $logFile = file_get_contents($filePath);
            $logFile = explode("\n", $logFile);

            //リクエストの処理にかかった時間の平均値とアクセス回数の変数初期化
            $ave_execution_time = 0;
            $accessTimes = 0;

            //リクエストの処理にかかった時間の合計値とアクセス回数合計値取得とaccess_logテーブルにデータ登録
            foreach ($logFile as $log) {
                //ログファイル最終行に空行が存在するため、空行では何もしない
                if ($log !== '') {
                    $log = explode(' ', $log);
                    AccessLog::storeLog($log);
                    $ave_execution_time += $log[6];
                    ++$accessTimes;
                }
            }

            //リクエストの処理にかかった時間の平均値計算
            $ave_execution_time = $ave_execution_time / $accessTimes;
            //aggregate_logsテーブルにデータ登録
            AggregateLog::storeAggregateLog($ave_execution_time, $accessTimes);

        } catch (Exception $exception) {

            //定期ジョブ実行失敗時ログ出力
            Log::channel('batchLog')->warning(config('messages.batchError'));
            //例外をhandlerにスロー
            throw new Exception($exception);

        }

        //定期ジョブ実行完了ログを出力
        Log::channel('batchLog')->info(config('messages.batchFinish'));

    }
}
