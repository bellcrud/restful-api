<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AccessLog extends Model
{
    protected $fillable = [
        'method', 'execution_time', 'status_code', 'end_point'
    ];

    /**
     * アクセスログ登録
     * 以下のデータを登録
     * HTTPメソッド/APIエンドポイント/HTTPステータスコード/処理時間
     * @param $log
     * @throws \Throwable
     */
    public static function storeLog($log)
    {
        DB::transaction(function () use ($log) {
            AccessLog::create(['method' => $log[3], 'end_point' => $log[4], 'status_code' => $log[5], 'execution_time' => $log[6]]);
        });
    }

    /**
     * 集計データ取得
     * 1日の各API × HTTPステータスコードのアクセス数と平均処理時間のデータを取得
     * @return \Illuminate\Support\Collection
     */
    public static function aggregateYesterdayLog()
    {
        return DB::table('access_logs')->select(DB::raw(' count(*) as access_count,method,end_point,status_code,avg(execution_time) as ave_execution_time'))
            ->whereBetween('created_at',[new Carbon('yesterday'),new Carbon('tomorrow')])->groupBy('end_point','method','status_code')->get();
    }
}
