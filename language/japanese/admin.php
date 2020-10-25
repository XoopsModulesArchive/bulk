<?php

//=========================================================
// Bulk Import Module
// Language pack for Japanese
// 2005-07-24 K.OHWADA
// 有朋自遠方来
//=========================================================

define('_BULK_IMPORT', '一括登録');
define('_BULK_IMPORT_FILE', 'ファイルからの一括登録のデモ');
define('_EXEC', '実行');
define('_SAMPLE', '見本');
define('_FINISH_FAULT', 'エラーにより強制終了した');
define('_ERR_UNMATCH_REC', '該当するレコードがない');
define('_ERR_MANY_REC', '該当するレコードが２つ以上ある');

// users ユーザ登録
define('_US_ADD_USERS', 'ユーザ 一括登録');
define('_US_ADD_USERS_DSC', 'ユーザ名, メールアドレス, パスワード をカンマ(,)で区切って記述する');
define('_US_ADD_USERS_DSC_1', 'ユーザ名, メールアドレス, パスワード をタブ(TAB)で区切って記述する');
define('_US_NEW_USER_ADDED', 'ユーザを追加した');
define('_US_NO_USER', 'ユーザ名がない');
define('_US_NO_EMAIL', 'メールアドレスがない');
define('_US_NO_PASS', 'パスワードがない');

// xoopsheadline ヘッドライン
define('_HL_ADD_BULK', 'xoopsheadline 一括登録');
define('_HL_ADD_BULK_DSC', 'サイト名, URL, RSSのURL をカンマ(,)で区切って記述する');
define('_HL_ADD_BULK_DSC_1', 'サイト名, URL, RSSのURL をタブ(TAB)で区切って記述する');
define('_HL_NO_NAME', 'サイト名がない');
define('_HL_NO_URL', 'URLがない');
define('_HL_NO_RSSURL', 'RSSのURLがない');
define('_HL_HEADLINE_ADDED', 'ヘッドラインを追加した');

// xoopspoll 投票
define('_PL_ADD_BULK', 'xoopspoll 一括登録');
define('_PL_ADD_BULK_DSC1', '１行目に、質問, 説明, 複数選択の可否(1/0) をカンマ(,)で区切って記述する');
define('_PL_ADD_BULK_DSC2', '２行目以降に、選択肢を記述する');
define('_PL_ADD_BULK_DSC3', '説明 の中で、改行するときは、(\n) を使用する');
define('_PL_ADD_BULK_DSC_1', '１行目に、質問, 説明, 複数選択の可否(1/0) をタブ(TAB)で区切って記述する');
define('_PL_ADD_BULK_DSC_4', '横棒 (---) で区切ると、繰返して指定できる');
define('_PL_NO_QUESTION', '質問がない');
define('_PL_NO_OPTION', '選択肢ない');
define('_PL_POLL_ADDED', '投票を追加した');

// xoopsfaq FAQ
define('_XD_ADD_BULK', 'xoopsfaq 一括登録');
define('_XD_ADD_BULK_DSC1', '１行目に、カテゴリを記述する');
define('_XD_ADD_BULK_DSC2', '２行目以降に、質問, 回答 をカンマ(,)で区切って記述する');
define('_XD_ADD_BULK_DSC3', '回答 の中で、改行するときは、(\n) を使用する');
define('_XD_ADD_BULK_DSC_2', '２行目以降に、質問, 回答 をタブ(TAB)で区切って記述する');
define('_XD_ADD_BULK_DSC_4', '横棒 (---) で区切ると、繰返して指定できる');
define('_XD_NO_CAT', 'カテゴリがない');
define('_XD_NO_QUESTION', '質問がない');
define('_XD_NO_ANSWER', '回答がない');
define('_XD_FAQ_ADDED', 'FAQを追加した');

// sections セクション
define('_SC_ADD_BULK', 'sections 一括登録');
define('_SC_ADD_BULK_DSC1', '１行目に、カテゴリを記述する');
define('_SC_ADD_BULK_DSC2', '２行目以降に、タイトル, 内容 をカンマ(,)で区切って記述する');
define('_SC_ADD_BULK_DSC3', '内容 の中で、改行するときは、(\n) を使用する');
define('_SC_ADD_BULK_DSC_2', '２行目以降に、タイトル, 内容 をタブ(TAB)で区切って記述する');
define('_SC_ADD_BULK_DSC_4', '横棒 (---) で区切ると、繰返して指定できる');
define('_SC_NO_CAT', 'カテゴリがない');
define('_SC_NO_TITLE', '質問がない');
define('_SC_NO_CONT', '回答がない');
define('_SC_ARTICLE_ADDED', '記事を追加した');

// news ニュース
define('_NW_ADD_BULK_TOPIC', 'news トピック一括登録');
define('_NW_ADD_BULK_TOPIC_DSC1', 'トピック を記述する');
define('_NW_ADD_BULK_TOPIC_DSC2', '子トピックは、行頭に左矢印括弧(>)を記述する');
define('_NW_ADD_BULK_TOPIC_DSC_0', '１行目に、親のトピックを記述する');
define('_NW_ADD_BULK_TOPIC_DSC_1', '２行目以降に、トピック を記述する');
define('_NW_ADD_BULK_STORY', 'news ニュース一括登録');
define('_NW_ADD_BULK_STORY_DSC', 'タイトル, 本文 をカンマ(,)で区切って記述する');
define('_NW_ADD_BULK_STORY_DSC_0', '１行目に、トピックを記述する');
define('_NW_ADD_BULK_STORY_DSC_1', '２行目以降に、タイトル, 本文 をタブ(TAB)で区切って記述する');
define('_NW_ADD_BULK_STORY_DSC_2', '横棒 (---) で区切ると、繰返して指定できる');
define('_NW_NEW_TOPIC_ADDED', 'トピックを追加した');
define('_NW_NEW_STORY_ADDED', 'ニュースを追加した');
define('_NW_NO_TOPIC', 'トピックがない');
define('_NW_NO_TITLE', 'タイトルがない');
define('_NW_NO_HOMEBODY', '本文がない');
define('_NW_NO_CATEGORY', '該当するカテゴリがない');
define('_NW_MANY_CATEGORY', '該当するカテゴリが２つ以上あります');
define('_NW_ERR_LAYER', 'カテゴリの階層構造で２段階以上 下を指定しています');
define('_NW_ERR_CID', 'トピック番号が正しくない');
define('_NW_ERR_PID', '親のトピック番号が正しくない');

// mylinks リンク集
define('_ML_ADD_BULK_CAT', 'mylinks カテゴリ一括登録');
define('_ML_ADD_BULK_CAT_DSC1', 'カテゴリ を記述する');
define('_ML_ADD_BULK_CAT_DSC2', '子カテゴリは、行頭に左矢印括弧(>)を記述する');
define('_ML_ADD_BULK_CAT_DSC_0', '１行目に、親のカテゴリ を記述する');
define('_ML_ADD_BULK_CAT_DSC_1', '２行目以降に、カテゴリ を記述する');
define('_ML_ADD_BULK_LINK', 'mylinks リンク一括登録');
define('_ML_ADD_BULK_LINK_DSC', 'タイトル, URL, 説明 をカンマ(,)で区切って記述する');
define('_ML_ADD_BULK_LINK_DSC_0', '１行目に、カテゴリ を記述する');
define('_ML_ADD_BULK_LINK_DSC_1', '２行目以降に、タイトル, URL, 説明 をタブ(TAB)で区切って記述する');
define('_ML_ADD_BULK_LINK_DSC_2', '横棒 (---) で区切ると、繰返して指定できる');
define('_ML_NO_CAT', 'カテゴリがない');
define('_ML_NO_TITLE', 'タイトルがない');
define('_ML_NO_URL', 'URLがない');
define('_ML_NO_DESCRIPTION', '説明がない');
define('_ML_ERR_LAYER', 'カテゴリの階層構造で２段階以上 下を指定しています');
define('_ML_ERR_CID', 'カテゴリ番号が正しくない');
define('_ML_ERR_PID', '親のカテゴリ番号が正しくない');

// mydownloads ダウンロード集
define('_MD_ADD_BULK_CAT', 'mydownloads カテゴリ一括登録');
define('_MD_ADD_BULK_CAT_DSC1', 'カテゴリ を記述する');
define('_MD_ADD_BULK_CAT_DSC2', '子カテゴリは、行頭に左矢印括弧(>)を記述する');
define('_MD_ADD_BULK_CAT_DSC_0', '１行目に、親のカテゴリ を記述する');
define('_MD_ADD_BULK_CAT_DSC_1', '２行目以降に、カテゴリ を記述する');
define('_MD_ADD_BULK_DOWN', 'mydownloads ファイル一括登録');
define('_MD_ADD_BULK_DOWN_DSC', 'タイトル, URL, 説明 をカンマ(,)で区切って記述する');
define('_MD_ADD_BULK_DOWN_DSC_0', '１行目に、カテゴリ を記述する');
define('_MD_ADD_BULK_DOWN_DSC_1', '２行目以降に、タイトル, URL, 説明 をタブ(TAB)で区切って記述する');
define('_MD_ADD_BULK_DOWN_DSC_2', '横棒 (---) で区切ると、繰返して指定できる');
define('_MD_NO_CAT', 'カテゴリがない');
define('_MD_NO_TITLE', 'タイトルがない');
define('_MD_NO_URL', 'URLがない');
define('_MD_NO_DESCRIPTION', '説明がない');
define('_MD_ERR_LAYER', 'カテゴリの階層構造で２段階以上 下を指定しています');
define('_MD_ERR_CID', 'カテゴリ番号が正しくない');
define('_MD_ERR_PID', '親のカテゴリ番号が正しくない');

// newbb フォーラム
define('_BB_ADD_BULK_CAT', 'newbb カテゴリ一括登録');
define('_BB_ADD_BULK_CAT_DSC', 'カテゴリ を記述する');
define('_BB_ADD_BULK_FORUM', 'newbb フォーラム一括登録');
define('_BB_ADD_BULK_FORUM_DSC', 'フォーラム名, 説明 をカンマ(,)で区切って記述する');
define('_BB_ADD_BULK_FORUM_DSC_0', '１行目に、カテゴリ を記述する');
define('_BB_ADD_BULK_FORUM_DSC_1', '２行目以降に、フォーラム名, 説明 をタブ(TAB)で区切って記述する');
define('_BB_ADD_BULK_FORUM_DSC_2', '横棒 (---) で区切ると、繰返して指定できる');
define('_BB_ADD_BULK_TOPIC', 'newbb トピック一括登録');
define('_BB_ADD_BULK_TOPIC_DSC', '題目, 内容 をカンマ(,)で区切って記述する');
define('_BB_ADD_BULK_TOPIC_DSC_0', '１行目に、フォーラム を記述する');
define('_BB_ADD_BULK_TOPIC_DSC_1', '２行目以降に、題目, 内容 をタブ(TAB)で区切って記述する');
define('_BB_ADD_BULK_TOPIC_DSC_2', '横棒 (---) で区切ると、繰返して指定できる');
define('_BB_ADD_BULK_POST', 'newbb 投稿一括登録');
define('_BB_ADD_BULK_POST_DSC', '題目, 内容 をカンマ(,)で区切って記述する');
define('_BB_CAT', 'カテゴリ');
define('_BB_FORUM', 'フォーラム');
define('_BB_NEW_CAT_ADDED', 'カテゴリを追加した');
define('_BB_NEW_FORUM_ADDED', 'フォーラムを追加した');
define('_BB_NEW_TOPIC_ADDED', 'トピックを追加した');
define('_BB_NO_CAT', 'カテゴリがない');
define('_BB_NO_FORUM_NAME', 'フォーラム名がない');
define('_BB_NO_FORUM_DESC', '説明がない');
define('_BB_NO_TOPIC_TITLE', 'トピック名がない');
define('_BB_NO_SUBJECT', '題名がない');
define('_BB_NO_POST_TEXT', '内容がない');
define('_BB_ERR_LAYER', 'カテゴリの階層構造で２段階以上 下を指定しています');
define('_BB_ERR_CID', 'カテゴリ番号が正しくない');
define('_BB_ERR_FORUM_ID', 'フォーラム番号が正しくない');
define('_BB_ERR_TOPIC_ID', 'トピック番号が正しくない');
