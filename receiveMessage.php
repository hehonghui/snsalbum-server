<?
/*************************************************************************************
 
*     引入数据库操作文件
**************************************************************************************/
include ("DB.php");                 
include ("sendmail.php");
session_start();
/*************************************************************************************/


     $protocol=$_REQUEST['protocol'];
     switch($protocol)
	 {
/*************************************************************************************
 
*     注册
**************************************************************************************/	 
	 case "regist": 	
	
							 $id=$_REQUEST['id'];
							 $password=$_REQUEST['password'];
							 $name=$_REQUEST['name'];
							 $email=$_REQUEST['email'];
							 
							 
							 $id=iconv("utf-8","gbk",$id);                               //转换格式
							 $password=iconv("utf-8","gbk",$password);
							 $name=iconv("utf-8","gbk",$name);
							 $email=iconv("utf-8","gbk",$email);
		
							
							if(insertuser(addslashes($id),addslashes($password),addslashes($name),addslashes($email))==true)
							 {
								 
								 if(is_dir("IMAGES/$id")==false)
									  mkdir("IMAGES/$id");                              //创建根目录
									  
							    $_SESSION["temp"]="success";
								header('Location: send.php');
								
								$body="dear $name: congratulation  for your success regist.welcome to join us!!!!!";
							    sendMail($email,$body);                                //发送邮件
								
							}
							else
							{	
							  $_SESSION["temp"]="fail";
							    header('Location: send.php');
							}
							
	 break;
/*************************************************************************************

*     修改密码
**************************************************************************************/
	 case "changePassword": 	
	 						 
							 $id=$_REQUEST['id'];
							 $password=$_REQUEST['password'];
							 $newPassword=$_REQUEST['newPassword'];

							 
							 
							 $id=iconv("utf-8","gbk",$id);                               //转换格式
							 $password=iconv("utf-8","gbk",$password);
							 $newPassword=iconv("utf-8","gbk",$newPassword);

							 
							if(changePassword(addslashes($id),addslashes($password),addslashes($newPassword))==true)
							 {
								 
							    $_SESSION["temp"]="success";
								header('Location: send.php');
								
							}
							else
							{	
							  $_SESSION["temp"]="fail";
							    header('Location: send.php');
							}
	 break;
/*************************************************************************************
 
*     登入
**************************************************************************************/	
	 case "login":  		
					 $id=$_REQUEST['id'];
				     $password=$_REQUEST['password'];
					 $ip=$_REQUEST['ip'];
					 
					 $id=iconv("utf-8","gbk",$id);                               //转换格式
					 $password=iconv("utf-8","gbk",$password);	
					 $ip=iconv("utf-8","gbk",$ip);			 
					 
					 
					 if(checkuser(addslashes($id),addslashes($password)))
					 {
					    if(insertOnline(addslashes($id),addslashes($ip)))
						{
						        $str=getUserName(addslashes($id));
								if($str)
								{
									$str="success;;".$str;
									$str=iconv("gbk","utf-8",$str);         //utf-8编码后发送
									$_SESSION["temp"]=$str;
									header('Location: send.php');
								}
								else
								{
									$_SESSION["temp"]="fail";
									header('Location: send.php');
								}
						 }
						else
						{
							$_SESSION["temp"]="fail";
							header('Location: send.php');
						}
					 }
					 else
					 {
							 $_SESSION["temp"]="fail";
							  header('Location: send.php');
					 }
	 break;
/*************************************************************************************
 
*     获取相册
**************************************************************************************/	
	 case "getAlbums":  		
					 $id=$_REQUEST['id'];

					 
					 $id=iconv("utf-8","gbk",$id);                               //转换格式		 
					 

					$str=getAllGroup(addslashes($id));
					if($str)
					 {
							$str=iconv("gbk","utf-8",$str);         //utf-8编码后发送
							$_SESSION["temp"]=$str;
							header('Location: send.php');
					 }  
					else
					 {
							$_SESSION["temp"]="fail";
							header('Location: send.php');
					 }
			
	 break;
/*************************************************************************************
 
*    获取图片名字
**************************************************************************************/		 
	 case "getImageName":   
					$id=$_REQUEST['id'];
					$albumName=$_REQUEST['albumName'];
					
		
				    $id=iconv("utf-8","gbk",$id);                               //转换格式
				    $albumName=iconv("utf-8","gbk",$albumName);
							 
							 
					$str=getAllPictureName(addslashes($id),addslashes($albumName));
					if($str==true)
					{
					        $str=iconv("gbk","utf-8",$str);         //utf-8编码后发送
							$_SESSION["temp"]=$str;
							header('Location: send.php');
					}
					else
					{
							$_SESSION["temp"]="fail";
							header('Location: send.php');
					}
	 break;
/*************************************************************************************
 
*     请求缩略图
**************************************************************************************/			 
	 case "getSmallImage":  
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$imageName=$_REQUEST['imageName'];
			
				$id=iconv("utf-8","gbk",$id);                               //转换格式
				$albumName=iconv("utf-8","gbk",$albumName);
				$imageName=iconv("utf-8","gbk",$imageName);				
				
				
				$str="IMAGES/$id/$albumName/"."small/"."$imageName";
				if($str==true)
				{ 
						$_SESSION["temp"]=file_get_contents($str);
						header('Location: send.php');
				}
				else
				{
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				}
	 break;
/*************************************************************************************
 
*     获取大图
**************************************************************************************/			 
	 case "getImage":  		
					$id=$_REQUEST['id'];
				    $albumName=$_REQUEST['albumName'];
				    $imageName=$_REQUEST['imageName'];
					
				    $id=iconv("utf-8","gbk",$id);                               //转换格式
				    $albumName=iconv("utf-8","gbk",$albumName);
				    $imageName=iconv("utf-8","gbk",$imageName);						

					
					$str="IMAGES/$id/$albumName/$imageName";
					if($str==true)
					{ 
						$_SESSION["temp"]=file_get_contents($str);
						header('Location: send.php');
				    }
					else
				   {
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				   }
	 
	 break;
/*************************************************************************************
 
*     上传
**************************************************************************************/		 
	 
	 case "upload": 	
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$imageName=$_REQUEST['imageName'];
				
				$id=iconv("utf-8","gbk",$id);                               //转换格式
				$albumName=iconv("utf-8","gbk",$albumName);
				$imageName=iconv("utf-8","gbk",$imageName);					
				
				
				/*
				if(is_dir("IMAGES/$id/$albumName")==true)
					$path="IMAGES/$id/$albumName/$imageName";
				$image=$_REQUEST['image'];
				$binary=base64_decode($image); 
				header('Content-Type: bitmap; charset=utf-8'); 
				$file = fopen($path, 'wb'); 
				fwrite($file, $binary); 
				fclose($file);
				
				if(is_dir("IMAGES/$id/$albumName/small")==true)
					 $smallPath="IMAGES/$id/$albumName/small/$imageName";
				
				$smallImage=$_REQUEST['smallImage'];
				$binary=base64_decode($smallImage); 
				header('Content-Type: bitmap; charset=utf-8'); 
				$file = fopen($smallPath, 'wb'); 
				fwrite($file, $binary); 
				fclose($file);
				*/
                
                $userAlbumName = "IMAGES/$id/$albumName" ;
                if(!is_dir($userAlbumName) ) {
                	mkdir($userAlbumName) ;
                }
				$path="$userAlbumName/$imageName";
				$image=$_REQUEST['image'];
				$binary=base64_decode($image); 
				header('Content-Type: bitmap; charset=utf-8'); 
				$file = fopen($path, 'wb'); 
				fwrite($file, $binary); 
				fclose($file);
				
				$smallAlbum = $userAlbumName."/small" ;
				if(!is_dir($smallAlbum) ) {
					mkdir($smallAlbum) ;
				}
				$smallPath=$smallAlbum."/$imageName";
				
				$smallImage=$_REQUEST['smallImage'];
				$binary=base64_decode($smallImage); 
				header('Content-Type: bitmap; charset=utf-8'); 
				$file = fopen($smallPath, 'wb'); 
				fwrite($file, $binary); 
				fclose($file);
	
				$str=insertPicture(addslashes($id),addslashes($albumName),addslashes($imageName));
					if($str==true)
					{
						$_SESSION["temp"]="success";
						header('Location: send.php');
				   }
					else
				   {
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				  }
	 break;
/*************************************************************************************
 
*     修改图片名
**************************************************************************************/		 
	 
	 case "changeImage":    
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$imageName=$_REQUEST['imageName'];
				$newName=$_REQUEST['newName'];
				
				
				$id=iconv("utf-8","gbk",$id);                               //转换格式
				$albumName=iconv("utf-8","gbk",$albumName);
				$imageName=iconv("utf-8","gbk",$imageName);	
				$newName=iconv("utf-8","gbk",$newName);					
				
				
				$str=changePictureName(addslashes($id),addslashes($albumName),addslashes($imageName),addslashes($newName));
					if($str==true)
				   {
						$_SESSION["temp"]="success";
						header('Location: send.php');
				   }						
					else
				   {
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				   }
	 break;
/*************************************************************************************
 
*     删除图片
**************************************************************************************/	
 
	 
	 case "deleteImage":    
	 
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$imageName=$_REQUEST['imageName'];
				
				$id=iconv("utf-8","gbk",$id);                               //转换格式
				$albumName=iconv("utf-8","gbk",$albumName);
				$imageName=iconv("utf-8","gbk",$imageName);					
				
				
				$str=deletePicture(addslashes($id),addslashes($albumName),addslashes($imageName));
				if($str==true)
				{
						$_SESSION["temp"]="success";
						header('Location: send.php');
				}
				else
				{
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				}
	 break;
/*************************************************************************************
 
*     删除相册
**************************************************************************************/		 
	 
	 case "deleteAlbum":    
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				
				$id=iconv("utf-8","gbk",$id);                               //转换格式
				$albumName=iconv("utf-8","gbk",$albumName);
				
				
				$str=deleteGroup(addslashes($id),addslashes($albumName));
				if($str==true)
				{
						$_SESSION["temp"]="success";
						header('Location: send.php');
				}
				else
				{
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				}
	 break;
/*************************************************************************************
 
*     创建相册
**************************************************************************************/		 
	 
	 case "addAlbum":       
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$share=$_REQUEST['share'];



				$id=iconv("utf-8","gbk",$id);                               //转换格式
				$albumName=iconv("utf-8","gbk",$albumName);
				$share=iconv("utf-8","gbk",$share);
				
				
				
				$str=insertGroup(addslashes($id),addslashes($albumName),addslashes($share));
				if($str==true)
				  {
					   if(is_dir("IMAGES/$id/$albumName")==false)
							mkdir("IMAGES/$id/$albumName");             //创建相册
					   if(is_dir("IMAGES/$id/$albumName/small")==false)
							mkdir("IMAGES/$id/$albumName/small");             //创建缩略图相册

						$_SESSION["temp"]="success";
						header('Location: send.php');
				  }
				else
				{
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				}
	 break;
	 
	 
	 
/*************************************************************************************
 
*     修改相册名
**************************************************************************************/		 
	 
	 case "changeAlbum":       
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$newName=$_REQUEST['newName'];

	

				$id=iconv("utf-8","gbk",$id);                               //转换格式
				$albumName=iconv("utf-8","gbk",$albumName);
				$newName=iconv("utf-8","gbk",$newName);
				
				$str=changeGroupName($id,$albumName,$newName) ;
				if($str==true)
				  {
						$_SESSION["temp"]="success";
						header('Location: send.php');
				  }
				else
				{
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				}
	 break;
/*************************************************************************************
 
*     获取所有共享好友
**************************************************************************************/		
	 
	 case "getAllSharePeople":
	 			$str=getAllSharePeople();
				if($str==true)
				 {
				        $str=iconv("gbk","utf-8",$str);         //utf-8编码后发送
						$_SESSION["temp"]=$str;
						header('Location: send.php');
				 }
				else
				{
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				}
	 
	 break;
/*************************************************************************************
 
*     获取指定好友的共享相册
**************************************************************************************/	
	 
	 case "getShareAlbum":
	 			$id=$_REQUEST['id'];

				$id=iconv("utf-8","gbk",$id);                               //转换格式
				
				$str=getShareAlbum($id);
				if($str==true)
				 {
				        $str=iconv("gbk","utf-8",$str);         //utf-8编码后发送
						$_SESSION["temp"]=$str;
						header('Location: send.php');
				 }
				else
				{
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				}

	 break;

	 
/*************************************************************************************
 
*     获取共享好友的坐标
**************************************************************************************/	
	 case "sharePoint": 	
	 						 
							 $id=$_REQUEST['id'];
							 $xpoint=$_REQUEST['xpoint'];
							 $ypoint=$_REQUEST['ypoint'];

							 
							 $id=iconv("utf-8","gbk",$id);                               //转换格式
							 $xpoint=iconv("utf-8","gbk",$xpoint);
							 $ypoint=iconv("utf-8","gbk",$ypoint);
		
									
							
							if(sharePoint(addslashes($id),addslashes($xpoint),addslashes($ypoint))==true)
							 {
							    $_SESSION["temp"]="success";
								header('Location: send.php');
							 }
							else
							{	
							    $_SESSION["temp"]="fail";
							    header('Location: send.php');
							}
	 break;
/*************************************************************************************
 
*     获取好友IP
**************************************************************************************/		
	 case "getIP":  		
			 	      
					 $str=getIP();
					 if($str)
					 {
					         $str=iconv("gbk","utf-8",$str);         //utf-8编码后发送
							 $_SESSION["temp"]=$str;
							 header('Location: send.php');
		
					 }
					 else
					 {
							 $_SESSION["temp"]="fail";
							  header('Location: send.php');
					 }
	 break;
/*************************************************************************************
 
*    获取好友坐标
**************************************************************************************/		
	 case "getAddress":  		
			 	 
					 $str=getAddress();
					 if($str)
					 {
					         $str=iconv("gbk","utf-8",$str);         //utf-8编码后发送
							 $_SESSION["temp"]=$str;
							 header('Location: send.php');
		
					 }
					 else
					 {
							 $_SESSION["temp"]="fail";
							  header('Location: send.php');
					 }
	 break;
/*************************************************************************************
 
*     下线
**************************************************************************************/	
	 
	 case "offLine":    
				$id=$_REQUEST['id'];
				
				$id=iconv("utf-8","gbk",$id);                               //转换格式
					
				$str=offLine(addslashes($id));
				   if($str)
				   {
						$_SESSION["temp"]="success";
						header('Location: send.php');
				   }						
					else
				   {
						$_SESSION["temp"]="fail";
						header('Location: send.php');
				   }
	 break;
	 
/*************************************************************************************
 
*     修改是否共享
**************************************************************************************/	
	 
	 case "changeShare":    
		                     $id=$_REQUEST['id'];
							 $albumName=$_REQUEST['albumName'];
							 $share=$_REQUEST['share'];


							 $id=iconv("utf-8","gbk",$id);                               //转换格式
							 $albumName=iconv("utf-8","gbk",$albumName);
							 $share=iconv("utf-8","gbk",$share);

							 
							if(changeShare(addslashes($id),addslashes($albumName),addslashes($share))==true)
							 {
								 
							    $_SESSION["temp"]="success";
								header('Location: send.php');
								
							}
							else
							{	
							  $_SESSION["temp"]="fail";
							    header('Location: send.php');
							}
	 break;
	  
/*************************************************************************************
 
*     协议错误
**************************************************************************************/	
	 
	 default:             $_SESSION["temp"]="wrong protocol";
						  header('Location: send.php');
						 
	break;
	 }
 ?>