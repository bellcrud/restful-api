<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

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
        $item = new Item();
        \DB::Transaction(function () use ($params, $item) {
            $item->fill($params)->save();
        });

        return $item;
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
        $item = Item::find($id);
        \DB::Transaction(function () use ($params,$id,$item) {
            $item->update($params);
        });

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
        \DB::Transaction(function () use ($id) {
            $item = Item::find($id);
            $item->delete();
        });
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

    /**
     * アイテムの登録数をカウントする
     *
     * @return int
     */
    public static function itemCounter()
    {
        return Item::all()->count();
    }

    /**
     * アイテム全件取得
     * @return mixed
     */
    public static function itemAll()
    {
        $items = Item::all();
        return $items;
    }

}
