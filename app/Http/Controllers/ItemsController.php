<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as StatusCode;//追加
use Illuminate\Contracts\Validation\Validator;  // 追加
use Illuminate\Support\Facades\Validator as Validate;


use Illuminate\Support\Facades\Log;//追加 エラー時ログ出力

class ItemsController extends Controller
{
    /**
     * @param Validator $validator
     */
    public function errorValidation(Validator $validator)
    {
        $response['errors'] = $validator->errors()->toArray();

        abort_if($validator->fails(), StatusCode::HTTP_UNPROCESSABLE_ENTITY, $validator->errors(), $response['errors']);

    }

    /**
     * アイテム全件取得
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = Item::itemAll();
        $itemsCount = $items->count();
        //アイテムが存在していない場合メッセージを返す。
        if ($itemsCount === 0) {
            return response()->json(
                ["message" => "アイテムが登録されていません"],
                StatusCode::HTTP_OK,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
        return response()->json(
            ["items" => $items, "count" => $itemsCount],
            StatusCode::HTTP_OK,
            [],
            JSON_UNESCAPED_UNICODE
        );

    }


    /**
     * アイテム登録
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params = $this->imageDecode($params);
        $validator = Validate::make($params, [
            'name' => 'max:100|required',
            'description' => 'max:500|required',
            'price' => 'digits_between:1,9|required',
            'image' => 'required|base64',
        ]);

        if ($validator->fails()) {
            $this->errorValidation($validator);
        }

        //画像登録
        $params['image'] = self::imageUpload($params['image']);

        $item = Item::storeItem($params);
        return response()->json(
            ["item" => $item, "message" => "登録が完了しました。"],
            StatusCode::HTTP_CREATED,
            [],
            JSON_UNESCAPED_UNICODE
        );
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
        $rule = ['id' => 'numeric'];
        $validator = Validate::make($input, $rule);
        if ($validator->fails()) {
            abort(StatusCode::HTTP_NOT_FOUND);
        }

        $item = Item::find($id);
        if ($item) {
            //return response()->json(['item' => $item]);
            return response()->json(
                ['item' => $item],
                StatusCode::HTTP_OK,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            abort(StatusCode::HTTP_NOT_FOUND);
        }
    }


    /**
     * アイテム更新
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        //idのバリデーションチェック
        $input = ['id' => $id];
        $rule = ['id' => 'numeric'];
        $validator = Validate::make($input, $rule);
        if ($validator->fails()) {
            abort(StatusCode::HTTP_UNPROCESSABLE_ENTITY);
        }

        //対象データがあれば更新する
        $item = Item::find($id);
        if ($item) {
            $params = $request->all();
            $params = self::imageDecode($params);
            $validator = Validate::make($params, [
                'name' => 'max:100',
                'description' => 'max:500',
                'price' => 'digits_between:1,9',
                'image' => 'base64',
            ]);

            if ($validator->fails()) {
                $this->errorValidation($validator);
            }


            //imageプロパティが空でなければ、画像をストレージに保存
            if (array_key_exists('image', $params)) {

                //元画像削除
                self::imageDelete($item->getAttribute('image'));

                //画像登録
                $params['image'] = self::imageUpload($params['image']);
            }

            //データ更新処理
            $item = Item::updateItem($params, $id);
            $message = '更新が完了しました。';
            return response()->json(
                ['item' => $item, 'message' => $message],
                StatusCode::HTTP_CREATED,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } else {

            abort(StatusCode::HTTP_NOT_FOUND);
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
        $rule = ['id' => 'numeric'];
        $validator = Validate::make($input, $rule);
        if ($validator->fails()) {
            abort(StatusCode::HTTP_UNPROCESSABLE_ENTITY);
        }

        //対象データの存在確認

        $item = Item::find($id);
        if ($item) {
            self::imageDelete($item->getAttribute('image'));
            //削除処理
            Item::deleteItem($id);

            $message = '削除しました。';
            return response()->json(
                ['message' => $message],
                StatusCode::HTTP_CREATED,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            abort(StatusCode::HTTP_NOT_FOUND);
        }

    }


    /**
     * アイテム名検索
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $keyword = $request->query('keyword');

        //キーワードがあれば検索する。なければメッセージのみを返す
        if (!is_null($keyword)) {

            $items = Item::findByKeywordItem($keyword);
            $itemCount = $items->count();
            //ヒットした件数が1件以上であれば、アイテム情報を返す。なければメッセージのみを返す
            if ($itemCount !== 0) {

                return response()->json(
                    ['items' => $items, 'itemCount' => $itemCount],
                    StatusCode::HTTP_OK,
                    [],
                    JSON_UNESCAPED_UNICODE
                );

            } else {

                $messages = "キーワードに当てはまるアイテムがありませんでした。";

                return response()->json(
                    ['messages' => $messages],
                    StatusCode::HTTP_OK,
                    [],
                    JSON_UNESCAPED_UNICODE
                );
            }
        } else {
            $messages = "キーワードに当てはまるアイテムがありませんでした。";

            return response()->json(
                ['messages' => $messages],
                StatusCode::HTTP_OK,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }


    }

    /**
     * 画像の登録
     * 画像ファイルをアップロードし、ファイルのimageディレクトリ以下のファイルパスを返す。
     * 保存するファイル形式は.png
     * ①ストレージをpublicに設定
     * ②confファイルから、保存先パスを取得
     * ③ファイル名を作成
     * ④base64をバイナリデータにエンコード
     * ⑤ファイルをアップロードする
     * @param $image
     * @return string
     */
    public function imageUpload($image)
    {
        /**
         * ファイルの保存の準備
         */
        //保存先の指定処理
        $disk = Storage::disk('public');
        $storeDir = config('filesystems.image');

        // 保存ファイル用変数を初期化
        $storeFile = null;
        // MIMETYPEを取得
        $mime_type = finfo_buffer(finfo_open(), $image, FILEINFO_MIME_TYPE);

        $storeFilename = strcmp($mime_type, 'image/png') == 0 ? uniqid() . '_image.png' : uniqid() . '_image.jpg';
        $storeFile = sprintf('%s/%s', $storeDir, $storeFilename);


        //保存データのアップロード
        $disk->put($storeFile, $image);

        //ブラウザで確認する用のURLに変更
        $storeFile = '/storage/' . $storeFile;
        return $storeFile;
    }

    /**
     * 画像の削除
     * 引数ファイル名を元に画像を削除する
     * @param $fileName
     */
    public function imageDelete($fileName)
    {
        $disk = Storage::disk('public');

        //ファイルパスを修正
        $fileName = str_replace('/storage/', '', $fileName);
        $disk->delete($fileName);
    }

    /**
     * 画像Base64をエンコードする
     * エンコードしたデータは配列型$paramsに要素を追加する
     * エンコード前チェック項目
     * ①jsonで送信されえきたデータが、pngまたはjpeg
     * ②エンコードが成功したかどうか
     * @param $params
     * @return mixed
     */
    public function imageDecode($params)
    {
        // 1. imageが存在しているか
        if (empty($params['image'])) {
            return $params;
        }

        // 2. data:image/png;base64　または　data:image/png;base64が文字列に存在しているか & ログ
        if (preg_match('/data:image\/png;base64/', $params['image'])) {
            // 3. base_64のデコード
            $base64_encode_image = str_replace('data:image/png;base64,', '', $params['image']);
            $base64_decode_image = base64_decode($base64_encode_image);

            // 4. base_64のデコードは成功したか & ログ
            if (!$base64_decode_image) {
                Log::info('data:image\/png;base64のデコードに失敗しました。');
                return $params;
            }

            $params['image'] = $base64_decode_image;
            return $params;
            // 2. data:image/jpeg;base64　または　data:image/jpg;base64が文字列に存在しているか & ログ
        } elseif (preg_match('/data:image\/jpeg;base64/', $params['image'])) {
            // 3. base_64のデコード
            $base64_encode_image = str_replace('data:image/jpeg;base64,', '', $params['image']);
            $base64_decode_image = base64_decode($base64_encode_image);

            // 4. base_64のデコードは成功したか & ログ
            if (!$base64_decode_image) {
                Log::info('data:image\/jpeg;base64のデコードに失敗しました。');
                return $params;
            }

            $params['image'] = $base64_decode_image;
            return $params;

        } else {
            Log::info('正しいデータではありませんでした。');
            return $params;
        }
    }

    /**
     * 画像登録用データ確定
     * 配列型$paramsの要素を変更する
     * $params['image']にデコードしたデータを格納
     * 不要になった配列要素を削除
     * @param $params
     * @return mixed
     */
    public function imageFinalData($params)
    {
        $params['image'] = $params['decodeImage'];
        unset($params['decodeImage']);

        return $params;
    }
}
