<?php
//変数宣言
  $editName="";
  $editNum="";
  $editComment="";
  $date=date("Y/m/d H:m:s");
  $password="nino";

  $filename="mission_3-5.txt";
  
 
     if(!empty($_POST["submit_edit"])){
      $edit=$_POST["edit"];
      $lines=file($filename);
      foreach($lines as $line){
          $sr=explode("<>",$line);
          //パスワードと一致したときのみ代入する
          if($edit==$sr[0] && $_POST["send_Password"]==$sr[4]){
              $editNum=$sr[0];
              $editName=$sr[1];
              $editComment=$sr[2];
              
              break;
          }
      }
  }elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["submit"])){
      $edit_post=$_POST["editNum"];
      $lines=file($filename);
      if(!empty($edit_post) && !empty($_POST["send_Password"])){
          
        //foreachとexplodeを使って、パスワード、投稿番号を取得。一致したときのみ作動
        foreach($lines as $line){
            $edit_Part=explode("<>",$line);
            if($edit_Part[0]==$edit_post && $edit_Part[4]==$_POST["send_Password"]){
                
            //↓二つの順番が入れ替わるとうまくいかない。"w"はファイルの中身を消す性質があるから、消した後にファイルを読み込んではいけない。   
            $files=file($filename);
            $fa=fopen($filename,"w");
            
            
            foreach($files as $file){
            $hen=explode("<>",$file);
            $name=$_POST["name"];
            $comment=$_POST["commnet"];
            $num=$hen[0];
            $password=$_POST["send_Password"];
            if($hen[0]==$edit_post){
                fwrite($fa,$num."<>".$name."<>".$comment."<>".$date."<>".$password."<>".PHP_EOL);
            }elseif($hen[0]!=$edit_post){
                fwrite($fa,$file);
            }
            
            
            }
            
            fclose($fa);
        }
            
          
        }
       
       
    }elseif(empty($edit_post) && !empty($_POST["send_Password"])){
      $name=$_POST["name"];
      $comment=$_POST["comment"];
      $count=count(file($filename));
      $num=$count+1;
      $password=$_POST["send_Password"];
      //投稿時にパスワードを受け取る
      
      $Data=$num."<>".$name."<>".$comment."<>".$date."<>".$password."<>";
      
      $fa=fopen($filename,"a");
      
      //パスワードをファイルに追記
      fwrite($fa,$Data.PHP_EOL);
      fclose($fa);
    }
  }
 
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
</head>
<body>


        名前<br>
        <form method="post">
            
            <!-- hidden属性で編集する番号を渡す ブラウザ上に表示されない非表示データを送信することが出来る-->
            <input type="hidden" name="editNum" value=<?php echo $editNum;?>></input>
            
            <input type="text" name="name" placeholder="名前" value=<?php echo $editName; ?>></input>
            <!-- hidden属性は$_POST[""]でvalue属性で指定した値を取得する
                 text属性はテキスト入力フォームで入力されたテキストを$_POST[""]で取得
                                                                     このときvalueは初期値-->
            <br>
        コメント<br>
            <input type="text" name="comment" placeholder="コメント" value=<?php echo $editComment;?>></input>
        <br>
            
         パスワード<br>
                <input type="password" placeholder="パスワード" name="send_Password"></input>
                <input type="submit" name="submit" value="送信"></input>
        </form>
        
        
        
        <form method="post">
            <br><br> 
            
            削除対象番号<br>
                <input type="number" placeholder="削除対象番号" name="delete"></input>
           <br>   
               
            パスワード <br>
                <input type="password" placeholder="パスワード" name="send_Password"></input>
                <input type="submit" value="削除"></input>
        </form>
        
        
        
        <form method="post">
            <br><br>   
            
            編集対象番号<br>
                <input type="number" placeholder="編集対象番号" name="edit"></input>
            <br>    
            パスワード<br>
                <input type="password" placeholder="パスワード" name="send_Password"></input>
                <input type="submit" name="submit_edit" value="編集"></input>
        </form>         
        

<?php

  $filename="mission_3-5.txt";
  $date=date("Y/m/d H:m:s");
  
  //foreachとexplodeを使って、投稿番号が一致かつパスワードが一致という条件を作る
  if(!empty($_POST["send_Password"])){
      $stuffs=file($filename);
      foreach($stuffs as $stuff){
          $Pass=explode("<>",$stuff);
          if($Pass[0]==$_POST["delete"] && $Pass[4]==$_POST["send_Password"]){
              //↓元の消去フォームのコード
              if(!empty($_POST["delete"])){
                  $delete=$_POST["delete"];
                  $lines=file($filename);
                  $fp=fopen($filename,"w");
                  foreach($lines as $line){
                    $ar=explode("<>",$line);
                    if($ar[0]!=$delete && $ar[0]<$delete){
                      fwrite($fp,$line);
                    }elseif($ar[0]!=$delete && $ar[0]>$delete){
                      $ar[0]=$ar[0]-1;
                      
                      //パスワードの後に<>を入れるとうまくいく
                      fwrite($fp,$ar[0]."<>".$ar[1]."<>".$ar[2]."<>".$ar[3]."<>".$ar[4]."<>".PHP_EOL);
                    }
                  }
                  fclose($fp);
                 }
          }
      }
  }
      
    
 
    
    if(file_exists($filename)){
        $fi=file($filename,FILE_IGNORE_NEW_LINES);
        foreach($fi as $f){
            $str=explode("<>",$f);
            $strs=$str[0]." ".$str[1]." ".$str[2]." ".$str[3];
            echo $strs."<br>";
        }
    }
?>

</body>
</html>