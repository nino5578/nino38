<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-27</title>
</head>
<body>
    数字を送信してください
    
    <form action="mission_1.php" method="post">
       
        <i-- formはタグ タグには属性がある
         action属性 どこに変数を送るか指定
         method属性 送信方法を決定
         送信方法 postとget
        -->
        
        
        <input type="text"  placeholder="数字を入力してください" name="num"></input>
        
        <i-- inputタグ  
             type属性で何をするのか決定 "text"=値を入力できるようになる
             name属性で連想配列のキーの名前を決める -->
             
        
        <input type="submit" value="送信"></input>
        
        <i-- "submit"=ボタンを作る
             "value"=ボタンの中に文字を書く"内容" -->
        
        
        
    </form>

<?php
 if(!empty($_POST["num"])){
     $num=$_POST["num"];
     $filename="mission_1.txt";
     $fp=fopen($filename,"a");
     fwrite($fp,$num);
     
     echo"<br>"."書き込み成功！"."<br>";
 
    

    fclose($fp);
 }

     
    if(file_exists($filename)){
     $num=$_POST["num"];
     $filename="mission_1.txt";
     $fp=fopen($filename,"a");
     fwrite($fp,$num);
     $lines=file($filename,FILE_IGNORE_NEW_LINES); //FILE_IGNORE_NEW_LINES配列の各要素の最後に改行文字が含まれない
     foreach($lines as $line){//foreach 配列の反復処理
       $num=$_POST["num"];
       if($num%3==0 && $num%5==0){
         echo"FizzBuzz"."<br>";
       }elseif($num%3==0){
         echo"Fizz"."<br>";
       }elseif($num%5==0){
         echo"Buzz"."<br>";
       }else{
         echo$num."<br>";
 }
 }
 fclose($fp);
}
      
?>


</body>
</html>