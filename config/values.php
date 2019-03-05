<?php
return [

	/*
	 * トークン認証
	 */
	//トークンの有効日数30日間
	'expirationDate' => 30,

	/*
	 * APIログ出力
	 */
	//ログメッセージ成型用スペース
	'space' => ' ',

	/*
	 * バッチ処理
	 */
	//APIアクセスログのファイル格納先
	'apiLogFile' => 'logs/api/',
	//APIアクセスログのファイル名
	'apiLogFileName' => 'access-',
	//ログファイル拡張子
	'logsExtension' => '.log',


];