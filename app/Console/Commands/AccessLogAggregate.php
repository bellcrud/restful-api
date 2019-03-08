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

            //ファイル保存先パスを取得
            $filePath = storage_path(config('values.apiLogFile')) . $filename;

            //ファイルを取得
            $logFile = file_get_contents($filePath);

            //ファイルを取得できなかった場合ログ出力し、処理をここで終了する
            if (!$logFile) {
                Log::channel('batchLog')->info(config('messages.batchFileNotFound'));
                exit;
            }

            //ファイル内にデータがなかった場合ログ出力し、処理をここで終了する
            $logFile = explode("\n", $logFile);
            if (empty($logFile)) {
                Log::channel('batchLog')->info(config('messages.batchFileEmpty'));
                exit;
            }

            //access_logテーブルにデータ登録
            foreach ($logFile as $log) {
                //ログファイル最終行に空行が存在するため、空行では何もしない
                if ($log !== '') {
                    //空白行で文字列を切り分け、配列に変換
                    $log = explode(' ', $log);
                    AccessLog::storeLog($log);
                }
            }

            //集計データ各API × HTTPステータスコードのアクセス数と平均処理時間のデータを取得
            $yesterdayLogs = AccessLog::aggregateYesterdayLog();

            //集計データを登録
            foreach ($yesterdayLogs as $yesterdayLog) {
                AggregateLog::storeAggregateLog((array)$yesterdayLog);
            }

        } catch (Exception $exception) {

            //定期ジョブ実行失敗時ログ出力
            Log::channel('batchLog')->warning(config('messages.batchError'));
            exit;

        }

        //定期ジョブ実行完了ログを出力
        Log::channel('batchLog')->info(config('messages.batchFinish'));

    }
}
