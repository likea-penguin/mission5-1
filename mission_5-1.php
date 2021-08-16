<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1.php</title>
</head>
<body>
<?php
//データベース接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//TABLEつくる
    $sql = "CREATE TABLE IF NOT EXISTS tbtest2"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "password varchar(32),"
    . "date timestamp"
    .");";
    $stmt = $pdo->query($sql);
    
    $name=$_POST['name'];
    $comment=$_POST['comment'];
    $pw = $_POST['password'];
    $date=date("Y/m/d H:i:s");
    $editmode=$_POST["editmode"];
    
   if (!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST["password"])) {
       
        if(empty($_POST["editmode"])){
            $sql = $pdo -> prepare("INSERT INTO tbtest2 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $pw, PDO::PARAM_STR);
            $sql -> execute();
       
        }else{
            $sql = 'SELECT * FROM tbtest2';
            $stmt = $pdo->query($sql);
            $lines = $stmt->fetchAll();
            
            foreach ($lines as $line){
                if($editmode == $line["id"]){
        //$rowの中にはテーブルのカラム名が入る
                
                $sql = 'UPDATE tbtest2 SET name=:name,comment=:comment,password=:password WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $editmode, PDO::PARAM_INT);
                $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
                $stmt->execute();
                }
            
            }
         }
   }
    if(!empty($_POST["delete"]) && !empty($_POST["deletepass"])){
        $id = $_POST["delete"];
        $deletepass = $_POST["deletepass"];
        $sql = 'SELECT * FROM tbtest2';
        $stmt = $pdo->query($sql);
        $lines = $stmt->fetchAll();
            
            foreach ($lines as $line){
                if($id == $line["id"] && $deletepass == $line["password"]){
                $sql = 'delete from tbtest2 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                }
            }
        }
        
    if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
        $id = $_POST["edit"];
        $editpass = $_POST["editpass"];
        $sql = 'SELECT * FROM tbtest2';
            $stmt = $pdo->query($sql);
            $lines = $stmt->fetchAll();
            
            foreach ($lines as $line){
                if($id == $line["id"] && $editpass == $line["password"]){
                    $editnumber=$line["id"];
                    $editname=$line["name"];
                    $editcomment=$line["comment"];
                    $editpassword=$line["password"];
                     
                }
            }
    }
   ?>
   
       <form action="" method="post">
       【投稿フォーム】<br>
        <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)){echo $editname;}?>">
        <br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)){echo $editcomment;}?>">
        <input type="hidden" name="editmode" value="<?php if(isset($editnumber)){echo $editnumber;}?>">
        <br>
        <input type="text" name="password" placeholder="パスワード" value="<?php if(isset($editpassword)){echo $editpassword;}?>" >
        <input type="submit" name="submit">
        <br>
        <br>
        【編集フォーム】<br>
        <input type="num" name="edit" placeholder="編集対象番号">
        <br>
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" name="edtsub" value="編集" >
        
        <br>
        <br>
        【削除フォーム】<br>
        <input type="num" name="delete" placeholder="削除対象番号">
        <br>
        <input type="text" name="deletepass" placeholder="パスワード">
        <input type="submit" name="delsub" value="削除" >
        
        </form>
    
<?php
    $sql = 'SELECT * FROM tbtest2';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date']."<br>";
        echo "<hr>";
    }
    ?>

</body>
</html>