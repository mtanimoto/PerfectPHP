<?php
// 投稿された内容を取得するSQLを作成して結果を取得
$sql = "SELECT * FROM `post` ORDER BY `created_at` DESC";
// $result = mysql_query($sql, $link);
$result = mysqli_query($link, $sql);

// 取得した結果を$postsに格納
$posts = array();
if ($result !== false && mysqli_num_rows($result))
{
    while ($post = mysqli_fetch_assoc($result))
    {
        $posts[] = $post;
    }
}

// 取得結果を解放して接続を閉じる
mysqli_free_result($result);
mysqli_close($link);
?>

<!DOCTYPE html PUBLIC "~//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>

    <form action="bbs_improve.php" method="post">
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

    <?php if (count($posts) > 0): ?>
    <ul>
        <?php foreach ($posts as $post): ?>
        <li>
            <?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>:
            <?php echo htmlspecialchars($post['comment'], ENT_QUOTES, 'UTF-8'); ?>
            - <?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</body>
</html>