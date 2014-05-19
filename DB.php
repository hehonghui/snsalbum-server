<?php
/*************************************************************************************
 
*     连接数据库
**************************************************************************************/	                          
				$link=mysql_connect("localhost","root","");//or die("无法建立连接<br>");
				 

				if($link)
				{
					mysql_select_db("android");
				}
				 mysql_query("set names gbk"); //设置字体


/*************************************************************************************
 
*     获取昵称
**************************************************************************************/	
function getUserName($id)                    
{

		$result=mysql_query("select name from user where id='$id'");
		if($result==true)
		{
			$line=mysql_fetch_array($result,MYSQL_ASSOC);
			if($line==true)
			{
			 return $line[name];
			}
			else
			{
		//	echo"没有此用户<br>";
			return false;
			}
		}
		else
		{
		//    echo "获取名字失败". mysql_error()."<br>";
			return false;
		}
		
}	 
/*************************************************************************************
 
*     核对登入是否合法
**************************************************************************************/	
function checkUser($id,$pwd)                    
{

		$result=mysql_query("select id,pwd from user where id='$id' and pwd='$pwd'");
		if($result==true)
		{
			if(mysql_num_rows($result)==1)
			{
		//	 echo "合法登入<br>";
			 return true;
			 }
			else
			{
		//	echo"不合法登入<br>";
			return false;
			}
		}
		else
		{
		//    echo "核对身份失败". mysql_error()."<br>";
			return false;
		}
		
}
/*************************************************************************************
 
*     修改密码
**************************************************************************************/	
function changePassword($id,$pwd,$new)                
{

		if(checkUser($id,$pwd)==true)
		{
			if(mysql_query("update user set pwd='$new' where id='$id' and pwd='$pwd'")==true) 
			{
		//		echo "密码修改成功<br>";
				return true;
			}
			else
			{
		//	   echo "修改密码失败<br>". mysql_error()."<br>";
			   return false;
			}
		}
		else
		{
		//	echo "不合法登入<br>";
			return false;
		}
		
}
/*************************************************************************************
 
*     执行插入操作
**************************************************************************************/	
function insertUser($id,$pwd,$name,$email)					
{

		$insertuser="insert into user values('$id','$pwd','$name','$email')";
		if(true== mysql_query($insertuser))
		{
		//     echo "用户创建成功<br>";
			 return true;
		}
		else
		{
		//     echo "用户名被占用<br>".mysql_error()."<br>";
			 return false;
		}
		
}
/*************************************************************************************
 
*     修改组名
**************************************************************************************/	
function changeGroupName($id,$picturegroup,$newname)         
{

		$str="update picturegroup set picturegroup='$newname' where id='$id' and picturegroup='$picturegroup'";
		if(mysql_query($str)==true)                    //          删除原相册
		{
			rename("IMAGES/$id/$picturegroup","IMAGES/$id/$newname");
		//	echo "修改相册成功<br>";
		    if(hasAffectedLines())
			    return true;
			else
			    return false;
		}
		else
		{
		//	echo "修改相册失败<br>".mysql_error();
			return false;
		}
	
}
/*************************************************************************************
 
*     增加组 创建相册
**************************************************************************************/	
function insertGroup($id,$picturegroup,$share)                  
{ 

		$insertgroup="insert into picturegroup values('$id','$picturegroup','$share')";
		if(mysql_query($insertgroup)==true)            
		{
	//	    echo "创建相册成功<br>";
			return true;
		}
		else
		{
		//	echo "创建相册失败,已有相同的相册<br>".mysql_error()."<br>";
			return false;
		}
		
}
/*************************************************************************************
 
*     删除组
**************************************************************************************/	
function deleteGroup($id,$picturegroup)					
{

        deldir("IMAGES/$id/$picturegroup");                      //删除文件夹
		$deletepicturegroup="delete from picturegroup where picturegroup.id='$id' and picturegroup.picturegroup='$picturegroup'";
		 if(mysql_query($deletepicturegroup)==true)
		{ 
//			echo " 删除相册成功<br>";
            if(hasAffectedLines())
			     return true;
		    else
				 return false;
		}
		else
		{
//		   echo "删除相册失败<br>". mysql_error()."<br>";
		   return false;
		}
			
}
/*************************************************************************************
 
*     修改图片名
**************************************************************************************/	
function changePictureName($id,$picturegroup,$picturename,$newname)     
{

		$str1="IMAGES/$id/$picturegroup/$picturename";
		$str2="IMAGES/$id/$picturegroup/$newname";
		rename($str1,$str2);
		$str1="IMAGES/$id/$picturegroup/small/$picturename";
		$str2="IMAGES/$id/$picturegroup/small/$newname";
		rename($str1,$str2);
		$str="update picture set picturename='$newname' where id='$id' and picturegroup='$picturegroup' and picturename='$picturename'";
		if(mysql_query($str)==true) 
		{
		//	   echo"修改图片成功<br>";
			   if(hasAffectedLines())
			       return true;
		       else
				   return false;
		}
		else
		{
		//	echo"修改图片失败<br>".mysql_error()."<br>";
			return false;
		}
		
}

/*************************************************************************************
 
*     增加图片
**************************************************************************************/	
function insertPicture($id,$picturegroup,$pciturename)
{

		$insertpicture="insert into picture values('$id','$picturegroup','$pciturename')";
		if(mysql_query($insertpicture)==true)
		{
		//	   echo"增加图片成功<br>";
			   return true;
		}
		else
		{
		//	echo "增加图片失败<br>".mysql_error()."<br>";
			return false;
		}
		
}
/*************************************************************************************
 
*     执行删除图片操作
**************************************************************************************/	
function deletePicture($id,$picturegroup,$picturename)					
{

		$small="IMAGES/$id/$picturegroup/small/$picturename";
			  unlink("$small");
		$picture="IMAGES/$id/$picturegroup/$picturename";
			   unlink("$picture");
		$str="delete from picture where id='$id' and picturegroup='$picturegroup' and picturename='$picturename'";
		if(mysql_query($str)==true)
		{
	//		   echo"删除图片成功<br>";
			   if(hasAffectedLines())
			     return true;
		       else
				 return false;
		}
		else
		{
	//		 echo "图片删除成功<br>".mysql_error()."<br>";
			 return false;
		}
		
}
/*************************************************************************************
 
*     获取所有组
**************************************************************************************/		
function getAllGroup($id)                                 
{

		$result=mysql_query("select picturegroup from picturegroup where id='$id'");
		if($result==true)
		{
			 $str=getUserName($id);	
			while($line=mysql_fetch_array($result,MYSQL_ASSOC))
			 {
					  $num=mysql_query("select id from picture where picturegroup='$line[picturegroup]' and id='$id'");
					  $num=mysql_num_rows($num);
					  $str=$str.";;"."$line[picturegroup]".";;"."$num";
			 }
		//	 echo "获取相册组成功<br>";
			 return $str;
		 }
		 else
		 {
		//    echo "获取相册组失败<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}
/*************************************************************************************
 
*     获取所有缩略图
**************************************************************************************/	
function getAllPictureName($id,$picturegroup)              
{

		$result=mysql_query("select picturename from picture where id='$id' and picturegroup='$picturegroup'");
		if($result==true)
		{
			$bool=true;
			while($line=mysql_fetch_array($result,MYSQL_ASSOC))
			 {
				  if($bool)  
				  {
					  $str="$line[picturename]";
					  $bool=false;
				  } 
				  else
					  $str=$str.";;"."$line[picturename]";
			 }
		//	 echo "获取缩略图组成功<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "获取缩略图组失败<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}			
/*************************************************************************************
 
*     获取有共享相册的成员
**************************************************************************************/	
function getAllSharePeople()                      
{

		$result_id=mysql_query("select id from picturegroup where share='yes' group by id");
		if($result_id==true)
		{
			$bool=true;
			while($id=mysql_fetch_array($result_id,MYSQL_ASSOC))
			 {
				  $name=getUserName($id[id]);
				  if($bool)  
				  {
					  $str="$id[id]".";;";
					  $str=$str."$name";
					  $bool=false;
				  } 
				  else
					  $str=$str.";;"."$id[id]".";;"."$name";
			 }
		//	 echo "获取有共享相册的成员成功<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "获取有共享相册的成员失败<br>".mysql_error()."<br>";
			return  false;
		 }

}
/*************************************************************************************
 
*     获取指定成员的共享相册
**************************************************************************************/	
function getShareAlbum($id)         
{
		  
		$result_group=mysql_query("select picturegroup from picturegroup where share='yes' and id='$id'");
		$str=getUserName($id);
		if($result_group==true)
		{
			while($group=mysql_fetch_array($result_group,MYSQL_ASSOC))
			 {
			      $num=mysql_query("select id from picture where picturegroup='$group[picturegroup]' and id='$id'");
				  $num=mysql_num_rows($num);

				  $str=$str.";;"."$group[picturegroup]".";;"."$num";
				
			 }
		//	 echo "获取指定成员的共享相册成功<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "获取指定成员的共享相册失败<br>".mysql_error()."<br>";
			return  false;
		 }
}
/*************************************************************************************
 
*     删除文件夹
**************************************************************************************/	
function deldir($dir)
{

	  //先删除目录下的文件：
		  $dh=opendir($dir);
		  while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
			  $fullpath=$dir."/".$file;
			  if(!is_dir($fullpath)) {
				  unlink($fullpath);
			  } else {
				  deldir($fullpath);
			  }
			}
		  }
		  closedir($dh);
		  //删除当前文件夹：
		  if(rmdir($dir)) {
			return true;
		  } else {
			return false;
		  }

}  
/*************************************************************************************
 
*     执行插入上线的人
**************************************************************************************/	
function insertOnline($id,$ip)					
{
        
		$result=mysql_query("select id from online where id='$id'");
		$line=mysql_fetch_array($result,MYSQL_ASSOC);
		if($line)
		{
		        $result=mysql_query("update online set ip='$ip' where id='$id'");
				if($result)
				   return true;
				else
				   return false;
		}
		else
		{
				$insertonline="insert into online(id,ip) values('$id','$ip')";
				if(true== mysql_query($insertonline))
				{
					 return true;
				}
				else
				{
					 return false;
				}
		}
}
/*************************************************************************************
 
*     执行插入坐标
**************************************************************************************/	
function sharePoint($id,$xpoint,$ypoint)					
{

		$insertonline="update online  set xpoint='$xpoint',ypoint='$ypoint'  where id='$id'";
		if(true== mysql_query($insertonline))
		{
		//     echo "用户创建成功<br>";
			if(hasAffectedLines())
			     return true;
		    else
				 return false;
		}
		else
		{
		//     echo "用户名被占用<br>".mysql_error()."<br>";
			 return false;
		}
		
}
/*************************************************************************************
 
*     获取所有IP
**************************************************************************************/	
function getIP()              
{

		$result=mysql_query("select user.id as id,name,ip  from online inner join user where online.id=user.id ");
		if($result==true)
		{
			$bool=true;
			while($line=mysql_fetch_array($result,MYSQL_ASSOC))
			 {
				  if($bool)  
				  {
					  $str="$line[id]".";;"."$line[name]".";;"."$line[ip]";
					  $bool=false;
				  } 
				  else
					  $str=$str.";;"."$line[id]".";;"."$line[name]".";;"."$line[ip]";
			 }
		//	 echo "获取缩略图组成功<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "获取缩略图组失败<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}			
/*************************************************************************************
 
*     获取地址
**************************************************************************************/	
function getAddress()              
{

		$result=mysql_query("select user.id as id,name,xpoint,ypoint  from online inner join user where online.id=user.id ");
		if($result==true)
		{
			$bool=true;
			while($line=mysql_fetch_array($result,MYSQL_ASSOC))
			 {
					 if($line[xpoint] && $line[ypoint])
					{	 
						  if($bool)  
						  {
							  $str="$line[id]".";;"."$line[name]".";;"."$line[xpoint]".";;"."$line[ypoint]";
							  $bool=false;
						  } 
						  else
							  $str=$str.";;"."$line[id]".";;"."$line[name]".";;"."$line[xpoint]".";;"."$line[ypoint]";
					}
			 }
		//	 echo "获取缩略图组成功<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "获取缩略图组失败<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}			
/*************************************************************************************
 
*     下线
**************************************************************************************/	
function offLine($id)              
{

		$result=mysql_query("delete from online where  id='$id'");
		if($result==true)
		 {
			if(hasAffectedLines())
			     return true;
		    else
				 return false;
		 }
		 else
		 {
			return  false;
		 }
		 
}			
/*************************************************************************************
 
*     是否影响了数据库
**************************************************************************************/		

function hasAffectedLines()
{
		if(mysql_affected_rows()>0)
		{
		return true;
		}
		else
		{
		return false;
		}
}	

/*
function getAllGroup($id)                                 //获取所有组
{

		$result=mysql_query("select picturegroup,share from picturegroup where id='$id'");
		if($result==true)
		{
			 $str=getUserName($id);	
			 while($line=mysql_fetch_array($result,MYSQL_ASSOC))
			 {
					  $num=mysql_query("select id from picture where picturegroup='$line[picturegroup]'");
					  $num=mysql_num_rows($num);
					  $str=$str.";;"."$line[picturegroup]".";;"."$num".";;"."$line[share]";
			 }
		//	 echo "获取相册组成功<br>";
			 return $str;
		 }
		 else
		 {
		//    echo "获取相册组失败<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}
*/
?>