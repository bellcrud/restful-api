<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
}
