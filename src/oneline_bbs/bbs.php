<?php

/*
 * mysql系の関数は5系と7系で違う。
 * 下記コードは7系で動くように少し修正している。
 */

// データベース接続
// $link = mysql_connect('localhost', 'root', '');
$link = mysqli_connect('localhost', 'root', '');
if (!$link)
{
    // die('データベースに接続できません：'. mysql_error());
    die('データベースに接続できません：'. mysqli_error());
}

// データベース
// mysql_select_db('oneline_bbs', $link);
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
        // $sql = "INSERT INTO `post` (`name`, `comment`, created_at`) VALUES (`"
        //     . mysql_real_escape_string($name) . "`, `"
        //     . mysql_real_escape_string($comment) . "`, `"
        //     . date('Y-m-d H:i:s') . "`)";
        $sql = "INSERT INTO `post` (`name`, `comment`, `created_at`) VALUES ('"
        . mysqli_real_escape_string($link, $name) . "', '"
        . mysqli_real_escape_string($link, $comment) . "', '"
        . date('Y-m-d H:i:s') . "')";

        // 保存する
        // mysql_query($sql, $link);
        mysqli_query($link, $sql);

        // mysql_close($link);
        mysqli_close($link);

        header('Location: http://' .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }
}

?>

<!DOCTYPE html PUBLIC "~//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>

    <form action="bbs.php" method="post">
        <?php if (count($errors)): ?>
        <ul class="error_list">
            <?php foreach ($errors as $error): ?>
            <li>
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <p>
            名前：<input type="text" name="name" /><br />
            ひとこと：<input type="text" name="comment" size="60" /><br />
            <input type="submit" value="送信" />
        </p>
    </form>

    <?php
    // 投稿された内容を取得するSQLを作成して結果を取得
    $sql = "SELECT * FROM `post` ORDER BY `created_at` DESC";
    // $result = mysql_query($sql, $link);
    $result = mysqli_query($link, $sql);
    ?>

    <?php if ($result !== false && mysqli_num_rows($result)): ?>
    <ul>
        <?php while ($post = mysqli_fetch_assoc($result)): ?>
        <li>
            <?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>:
            <?php echo htmlspecialchars($post['comment'], ENT_QUOTES, 'UTF-8'); ?>
            - <?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php endif; ?>

    <?php
    // 取得結果を解放して接続を閉じる
    // mysql_free_result($result);
    // mysql_close($link);
    mysqli_free_result($result);
    mysqli_close($link);
    ?>
</body>
</html>