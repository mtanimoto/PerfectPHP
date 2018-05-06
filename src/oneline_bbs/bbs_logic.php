<?php

// データベース接続
$link = mysqli_connect('localhost', 'root', '');
if (!$link)
{
    die('データベースに接続できません：'. mysqli_error());
}

// データベース
mysqli_select_db($link, 'oneline_bbs');

$errors = array();

// POSTなら保存処理実行
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // 名前が正しく入力されているかチェック
    $name = null;
    if (!isset($_POST['name']) || !strlen($_POST['name']))
    {
        $errors['name'] = '名前を入力してください';
    }
    else if (strlen($_POST['name']) > 40)
    {
        $errors['name'] = '名前は40文字以内で入力してください';
    }
    else
    {
        $name = $_POST['name'];
    }

    // ひとことが正しく入力されているかチェック
    $comment = null;
    if (!isset($_POST['comment']) || !strlen($_POST['comment']))
    {
        $errors['comment'] = 'ひとことを入力してください';
    }
    else if (strlen($_POST['comment']) > 200)
    {
        $errors['comment'] = 'ひとことは200文字以内で入力してください';
    }
    else
    {
        $comment = $_POST['comment'];
    }

    // エラーがなければ保存
    if (count($errors) === 0)
    {
        // 保存するためのSQL文を作成
        $sql = "INSERT INTO `post` (`name`, `comment`, `created_at`) VALUES ('"
        . mysqli_real_escape_string($link, $name) . "', '"
        . mysqli_real_escape_string($link, $comment) . "', '"
        . date('Y-m-d H:i:s') . "')";

        // 保存する
        mysqli_query($link, $sql);

        mysqli_close($link);

        header('Location: http://' .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }
}

include 'views/bbs_view.php';

?>

