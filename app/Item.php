<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;


class Item extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'image',
    ];


    /**
     * アイテム作成
     *
     * @param $params
     * @throws \Throwable
     */
    public static function storeItem($params)
    {
        //DBファザードのtransactionメソッドのためロールバック・コミットは記述なし
        try {
            \DB::Transaction(function () use ($params) {
                $item = new Item();
                $item->fill($params);
                $item->save();
            });
        }catch(QueryException $exception){
            throw $exception;
        }
    }

    /**
     * アイテム更新
     *
     * @param $params
     * @param $id
     * @throws \Throwable
     */
    public static function updateItem($params, $id)
    {
        //DBファザードのtransactionメソッドのためロールバック・コミットは記述なし
        try {
            \DB::Transaction(function () use ($params,$id) {
                $item = Item::find($id);
                $item->fill($params);
                $item->save();
            });
        }catch(QueryException $exception){
            throw $exception;
        }
        //変更後再度アイテム取得
        $item = Item::find($id);
        return $item;
    }

    /**
     * アイテム削除
     *
     * @param $id
     * @throws \Throwable
     */
    public static function deleteItem($id)
    {
        //DBファザードのtransactionメソッドのためロールバック・コミットは記述なし
        try {
            \DB::Transaction(function () use ($id) {
                $item = Item::find($id);
                $item->delete();
            });
        }catch(QueryException $exception){
            throw $exception;
        }
    }

    /**
     * アイテム商品名LIKE検索
     *
     * @param $keyword
     * @return \Illuminate\Support\Collection
     */
    public static function findByKeywordItem($keyword)
    {
        $hitTodos = Item::where('name', 'LIKE', "%$keyword%")->get();
        return $hitTodos;
    }

}
