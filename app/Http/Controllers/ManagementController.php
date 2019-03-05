<?php

namespace App\Http\Controllers;


use App\AggregateLog;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    /**
     * aggregate_logsテーブルの全件データを取得し、管理画面に遷移
     * 検索用日付をnullで渡す
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function index(){
        $aggregateLogs = AggregateLog::findAll();

        return view('/management', ['aggregateLogs' => $aggregateLogs, 'dayStart' => $dayStart = null, 'dayEnd' => $dayEnd = null,]);
    }

    /**
     * aggregate_logsテーブルで日付検索したデータを取得し、管理画面に遷移
     * 検索用日付を検索時に入力した値で渡す
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function findAggregateLog(Request $request)
    {
        $aggregateLogs = AggregateLog::findAggregateLog($request->dayStart, $request->dayEnd);

        return view('/management', ['aggregateLogs' => $aggregateLogs, 'dayStart' => $request->dayStart, 'dayEnd' => $request->dayEnd,]);
    }
}
