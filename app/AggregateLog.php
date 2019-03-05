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
    protected $fillable = ['ave_execution_time', 'total_access_count'];

    /**
     * aggregate_logsテーブル全件検索
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function findAll(){
        //return AggregateLog::paginate(1);
        return DB::table('aggregate_logs')->paginate(10);
    }

    /**
     * aggregate_logsテーブルに登録する
     * 1日のログの平均処理時間とアクセス数を計算した数値を登録する
     * @param $ave_execution_time
     * @param $accessTimes
     * @throws \Throwable
     */
    public static function storeAggregateLog($ave_execution_time, $accessTimes)
    {
        DB::transaction(function () use ($ave_execution_time, $accessTimes) {
            AggregateLog::create(['ave_execution_time' => $ave_execution_time, 'total_access_count' => $accessTimes]);
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
            //入力値の時刻が'00:00:00'になるため検索時に入力値と同日に作成された日は検索にヒットしないため、1日追加する。
            $dayEnd = $dayEnd->addDay();
        }

        return DB::table('aggregate_logs')->whereBetween('created_at', [$dayStart, $dayEnd])->paginate(10);
    }
}
