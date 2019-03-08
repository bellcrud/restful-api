<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AggregateLog extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['api_uri', 'ave_execution_time', 'total_access_count', 'status_code', 'method'];

    /**
     * aggregate_logsテーブル全件検索
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function findAll()
    {
        return DB::table('aggregate_logs')->paginate(10);
    }


    /**
     * aggregate_logsテーブルに登録する
     * 1日のログの平均処理時間とアクセス数を計算した数値を登録する
     * @param $yesterdayLog
     */
    public static function storeAggregateLog($yesterdayLog)
    {
        DB::transaction(function () use ($yesterdayLog) {
            AggregateLog::create(['api_uri' => $yesterdayLog['end_point'], 'ave_execution_time' => $yesterdayLog['ave_execution_time'], 'total_access_count' => $yesterdayLog['access_count'], 'status_code' => $yesterdayLog['status_code'], 'method' => $yesterdayLog['method']]);
        });
    }

    /**
     * aggregate_logsテーブル日付検索
     * @param $dayStart
     * @param $dayEnd
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function findAggregateLog($dayStart, $dayEnd)
    {

        //入力値があればをCarbon型に整形
        if (!empty($dayStart)) {
            $dayStart = new Carbon($dayStart);
        }
        if (!empty($dayEnd)) {
            $dayEnd = new Carbon($dayEnd);
            //入力値の時刻が'00:00:00'になるため検索時に入力値と同日に作成された日は検索にヒットしないため、時間を1日の最終時刻にする
            $dayEnd = $dayEnd->setTime(23, 59, 59);
        }

        return DB::table('aggregate_logs')->whereBetween('created_at', [$dayStart, $dayEnd])->paginate(10);
    }
}
