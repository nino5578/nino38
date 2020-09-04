<?php
//ここでmysqlに関する初期設定を行う

//データベースにログイン
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

//テーブルを作成
$sql = "CREATE TABLE IF NOT EXISTS tb_bbs"
 ." ("
 . "id INT AUTO_INCREMENT PRIMARY KEY,"
 . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "pass TEXT"
 .");";
$stmt = $pdo->query($sql);
    
?>

<?php

//編集用データ格納変数　空だったらなんもなくなる、追記モード
$editNumber = "";
$editName = "";
$editComment = "";


?>

<?php

//送信内容によって処理が分かれる　編集が押されたあとすぐの動作
if(!empty($_POST["editok"]) && !empty($_POST["pass_edit"])) {

    //編集番号からデータを求める
    $sql = 'SELECT * FROM tb_bbs';
 $stmt = $pdo->query($sql);
 $results = $stmt->fetchAll();
 foreach ($results as $row){
        
        if($_POST["edit"] == $row['id'] && $_POST["pass_edit"] == $row["pass"]){
            $editNumber = $row["id"];
            $editName = $row["name"];
            $editComment = $row["comment"];
            break;
        }
 
 }

//送信ボタンを押したとき
}else if (!empty($_POST["name"] ) && !empty($_POST["comment"]) && !empty($_POST["pass"]) && !empty($_POST["normal"])){

    $date = date("Y/m/d H:i:s");

    //編集モード
    if($_POST["edit_post"]) {

        $sql = 'SELECT * FROM tb_bbs';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();

        $flag = false;

        foreach ($results as $row){

            //もしidが編集番号に一致　かつ　passが入力されたパスワードに一致　したらflag をtrueにする
            $passw = $row['pass'];
            $num = $row["id"];

            //echo "password : " . $passw . " num : " . $num . " edit_post : " . $_POST["edit_post"] . " post_pass : " . $_POST["pass"] . "<br>";

            if($num == $_POST["edit_post"] && $passw == $_POST["pass"]) $flag = true;
       
        }
   
        if ($flag){
    
            $id = $_POST["edit_post"]; 
         $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");

         $sql = 'UPDATE tb_bbs SET name=:name,comment=:comment,date=:date WHERE id=:id';
         $stmt = $pdo->prepare($sql);
         $stmt->bindParam(':name', $name, PDO::PARAM_STR);
         $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

        }

    }else{

    //追記モード
    $sql = $pdo -> prepare("INSERT INTO tb_bbs (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
 $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
 $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass = $_POST["pass"];
 $sql -> execute();

    }
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
     <meta charset="UTF-8">
     <title>データベースを用いた投稿機能</title>
    </head>
    <body>
        
         名前<br>
        <form method="post">
            
            <!-- hidden属性で編集する番号を渡す ブラウザ上に表示されない非表示データを送信することが出来る-->
            <input type="hidden" name="edit_post" value=<?php echo $editNumber;?>></input>
            
            <input type="text" name="name" placeholder="名前" value=<?php echo $editName; ?>></input>
            <!-- hidden属性は$_POST[""]でvalue属性で指定した値を取得する
                 text属性はテキスト入力フォームで入力されたテキストを$_POST[""]で取得
                                                                     このときvalueは初期値-->
            <br>
        コメント<br>
            <input type="text" name="comment" placeholder="コメント" value=<?php echo $editComment;?>></input>
        <br>
            
         パスワード<br>
                <input type="text" placeholder="パスワード" name="pass"></input>
                <input type="submit" name="normal" value="送信"></input>
        </form>
        
        
        
        <form method="post">
            <br><br> 
            
            削除対象番号<br>
                <input type="number" placeholder="削除対象番号" name="bye"></input>
           <br>   
               
            パスワード <br>
                <input type="text" placeholder="パスワード" name="pass_bye"></input>
                <input type="submit" value="削除"></input>
        </form>
        
        
        
        <form method="post">
            <br><br>   
            
            編集対象番号<br>
                <input type="number" placeholder="編集対象番号" name="edit"></input>
            <br>    
            パスワード<br>
                <input type="text" placeholder="パスワード" name="pass_edit"></input>
                <input type="submit" name="editok" value="編集"></input>
        </form>   
        

    
<?php
    
if(!empty($_POST["bye"]) && !empty($_POST["pass_bye"])){

    $sql = 'SELECT * FROM tb_bbs';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();

    $flag = false;

    foreach ($results as $row){
        //passが入力されたパスワードに一致　したらflag をtrueにする
        $passw = $row['pass'];
        $n = $row["id"];
         //echo "password : " . $passw . " num : " . $num . " edit_post : " . $_POST["edit_post"] . " post_pass : " . $_POST["pass"] . "<br>";
        if($n == $_POST["bye"] && $passw == $_POST["pass_bye"]) $flag = true;
    }

    if ($flag){
        $delete = $_POST["bye"];
        $id = $delete;
     $sql = 'delete from tb_bbs where id=:id';
     $stmt = $pdo->prepare($sql);
     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
     $stmt->execute();
    }
}

$sql = 'SELECT * FROM tb_bbs';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

foreach ($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['date'].'<br>';
 echo "<hr>";
    }

 ?>
    
</body>
</html>