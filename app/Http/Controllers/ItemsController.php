<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as StatusCode;//追加
use Illuminate\Contracts\Validation\Validator;  // 追加
class ItemsController extends Controller
{
    public function errorValidation (Validator $validator)
    {
        $response['errors']  = $validator->errors()->toArray();

        abort_if($validator->fails(), StatusCode::HTTP_UNPROCESSABLE_ENTITY, $validator->errors(),$response['errors'] );

    }

    /**
     * アイテム全件取得
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $itemsCount = Item::itemCounter();
        $items = Item::all();

        //アイテムが存在していない場合メッセージを返す。
        if($itemsCount == 0){
            return response()->json(["message" => "アイテムが登録されていません"]);
        }
        return response()->json(["items" => $items, "count" => $itemsCount]);
    }


    /**
     * アイテム登録
     * @param Request $request
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'max:100|required',
            'description' => 'max:500|required',
            'price' => 'digits_between:1,11|required',
            'image' => 'required'
        ]);

        if ($validator->fails()){
            $this->errorValidation($validator);
        }

        $params = $request->all();

        $item = Item::storeItem($params);
        return response()->json(["item" => $item, "message" => "登録が完了しました。"]);
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
        if($item) {
            return response()->json(['item' => $item]);
        }else{
            abort(404);
        }
    }


    /**
     * アイテム更新
     * @param Request $request
     * @param $id
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        //idのバリデーションチェック
        $input = ['id' => $id];
        $rule = ['id' => 'integer'];
        $validator = \Validator::make( $input, $rule );
        if($validator->fails()) {
            abort(421);
        }

        //対象データがあれば更新する
        $item = Item::find($id);
        if($item){
            $validator = \Validator::make($request->all(), [
                'name' => 'max:100',
                'description' => 'max:500',
                'price' => 'digits_between:1,11',
            ]);

            if ($validator->fails()){
                $this->errorValidation($validator);
            }

            $params = $request->all();
            $item = Item::updateItem($params,$id);
            return response()->json(["item" => $item]);
        }else{

                abort(404);
        }
    }


    /**
     * アイテム削除
     * @param $id
     * @return string
     * @throws \Throwable
     */
    public function destroy($id)
    {
        //idのバリデーション チェック
        $input = ['id' => $id];
        $rule = ['id' => 'integer'];
        $validator = \Validator::make( $input, $rule );
        if($validator->fails()) {
            abort(421);
        }

        //対象データの存在確認

        $item = Item::find($id);
        if($item) {
            //削除処理
            Item::deleteItem($id);

            $message = '削除しました.';
            return \response()->json(['message' => $message]);
        }else{
            abort(404);
        }

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

        //キーワードがあれば検索する。なければメッセージのみを返す
        if(!is_null($keyword)){
            $hitItemCount = Item::findByKeywordItem($keyword)->count();

            $hitItem = Item::findByKeywordItem($keyword);

            //ヒットした件数が1件以上であれば、アイテム情報を返す。なければメッセージのみを返す
            if($hitItemCount != 0){

            return response()->json(['hitItem' => $hitItem, 'hitItemCount' => $hitItemCount]);

            }else{

                $messages = "キーワードに当てはまるアイテムがありませんでした。";

                return response()->json(['messages' => $messages]);
            }
        }else{
            $messages = "キーワードに当てはまるアイテムがありませんでした。";

            return response()->json(['messages' => $messages]);
        }


    }

}
