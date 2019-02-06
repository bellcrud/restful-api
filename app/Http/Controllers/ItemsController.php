<?php

namespace App\Http\Controllers;

use App\Item;
use http\Env\Response;
use Illuminate\Http\Request;

class ItemsController extends Controller
{

    /**
     * アイテム全件取得
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = Item::all();
        return response()->json(["items" => $items]);

    }


    /**
     * アイテム登録
     * @param Request $request
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'max:100',
            'description' => 'max:500',
            'price' => 'digits_between:1,11',
        ]);

        $params = $request->all();

        $item = Item::storeItem($params);
    }


    /**
     * アイテム取得
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //idに格納されている値がintegerか確認
        $input = ['id' => $id];
        $rule = ['id' => 'integer'];
        $validator = \Validator::make( $input, $rule );
        if($validator->fails()) {
            abort(404);
        }

        $item = Item::find($id);
        return response()->json($item);
    }


    /**
     * アイテム更新
     * @param Request $request
     * @param $id
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'max:100',
            'description' => 'max:500',
            'price' => 'digits_between:1,11',
        ]);
        $params = $request->all();
        $item = Item::updateItem($params,$id);
        return response()->json(["item" => $item]);
    }


    /**
     * アイテム削除
     * @param $id
     * @return string
     * @throws \Throwable
     */
    public function destroy($id)
    {
        $input = ['id' => $id];
        $rule = ['id' => 'integer'];
        $validator = \Validator::make( $input, $rule );
        if($validator->fails()) {
            abort(404);
        }
        Item::deleteItem($id);
        return '';
    }


    /**
     * アイテム名検索
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $params = $request->all();
        $keyword = $params['keyword'];

        if(!is_null($keyword)){
            $hitItem = Item::findByKeywordItem($keyword);
            return response()->json($hitItem);
        }

    }

}
