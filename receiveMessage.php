<?
/*************************************************************************************
 
*     �������ݿ�����ļ�
**************************************************************************************/
include ("DB.php");                 
include ("sendmail.php");
session_start();
/*************************************************************************************/


     $protocol=$_REQUEST['protocol'];
     switch($protocol)
	 {
/*************************************************************************************
 
*     ע��
**************************************************************************************/	 
	 case "regist": 	
	
							 $id=$_REQUEST['id'];
							 $password=$_REQUEST['password'];
							 $name=$_REQUEST['name'];
							 $email=$_REQUEST['email'];
							 
							 
							 $id=iconv("utf-8","gbk",$id);                               //ת����ʽ
							 $password=iconv("utf-8","gbk",$password);
							 $name=iconv("utf-8","gbk",$name);
							 $email=iconv("utf-8","gbk",$email);
		
							
							if(insertuser(addslashes($id),addslashes($password),addslashes($name),addslashes($email))==true)
							 {
								 
								 if(is_dir("IMAGES/$id")==false)
									  mkdir("IMAGES/$id");                              //������Ŀ¼
									  
							    $_SESSION["temp"]="success";
								header('Location: send.php');
								
								$body="dear $name: congratulation  for your success regist.welcome to join us!!!!!";
							    sendMail($email,$body);                                //�����ʼ�
								
							}
							else
							{	
							  $_SESSION["temp"]="fail";
							    header('Location: send.php');
							}
							
	 break;
/*************************************************************************************

*     �޸�����
**************************************************************************************/
	 case "changePassword": 	
	 						 
							 $id=$_REQUEST['id'];
							 $password=$_REQUEST['password'];
							 $newPassword=$_REQUEST['newPassword'];

							 
							 
							 $id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     ����
**************************************************************************************/	
	 case "login":  		
					 $id=$_REQUEST['id'];
				     $password=$_REQUEST['password'];
					 $ip=$_REQUEST['ip'];
					 
					 $id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
									$str=iconv("gbk","utf-8",$str);         //utf-8�������
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
 
*     ��ȡ���
**************************************************************************************/	
	 case "getAlbums":  		
					 $id=$_REQUEST['id'];

					 
					 $id=iconv("utf-8","gbk",$id);                               //ת����ʽ		 
					 

					$str=getAllGroup(addslashes($id));
					if($str)
					 {
							$str=iconv("gbk","utf-8",$str);         //utf-8�������
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
 
*    ��ȡͼƬ����
**************************************************************************************/		 
	 case "getImageName":   
					$id=$_REQUEST['id'];
					$albumName=$_REQUEST['albumName'];
					
		
				    $id=iconv("utf-8","gbk",$id);                               //ת����ʽ
				    $albumName=iconv("utf-8","gbk",$albumName);
							 
							 
					$str=getAllPictureName(addslashes($id),addslashes($albumName));
					if($str==true)
					{
					        $str=iconv("gbk","utf-8",$str);         //utf-8�������
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
 
*     ��������ͼ
**************************************************************************************/			 
	 case "getSmallImage":  
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$imageName=$_REQUEST['imageName'];
			
				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     ��ȡ��ͼ
**************************************************************************************/			 
	 case "getImage":  		
					$id=$_REQUEST['id'];
				    $albumName=$_REQUEST['albumName'];
				    $imageName=$_REQUEST['imageName'];
					
				    $id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     �ϴ�
**************************************************************************************/		 
	 
	 case "upload": 	
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$imageName=$_REQUEST['imageName'];
				
				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     �޸�ͼƬ��
**************************************************************************************/		 
	 
	 case "changeImage":    
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$imageName=$_REQUEST['imageName'];
				$newName=$_REQUEST['newName'];
				
				
				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     ɾ��ͼƬ
**************************************************************************************/	
 
	 
	 case "deleteImage":    
	 
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$imageName=$_REQUEST['imageName'];
				
				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     ɾ�����
**************************************************************************************/		 
	 
	 case "deleteAlbum":    
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				
				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     �������
**************************************************************************************/		 
	 
	 case "addAlbum":       
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$share=$_REQUEST['share'];



				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
				$albumName=iconv("utf-8","gbk",$albumName);
				$share=iconv("utf-8","gbk",$share);
				
				
				
				$str=insertGroup(addslashes($id),addslashes($albumName),addslashes($share));
				if($str==true)
				  {
					   if(is_dir("IMAGES/$id/$albumName")==false)
							mkdir("IMAGES/$id/$albumName");             //�������
					   if(is_dir("IMAGES/$id/$albumName/small")==false)
							mkdir("IMAGES/$id/$albumName/small");             //��������ͼ���

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
 
*     �޸������
**************************************************************************************/		 
	 
	 case "changeAlbum":       
				$id=$_REQUEST['id'];
				$albumName=$_REQUEST['albumName'];
				$newName=$_REQUEST['newName'];

	

				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     ��ȡ���й������
**************************************************************************************/		
	 
	 case "getAllSharePeople":
	 			$str=getAllSharePeople();
				if($str==true)
				 {
				        $str=iconv("gbk","utf-8",$str);         //utf-8�������
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
 
*     ��ȡָ�����ѵĹ������
**************************************************************************************/	
	 
	 case "getShareAlbum":
	 			$id=$_REQUEST['id'];

				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
				
				$str=getShareAlbum($id);
				if($str==true)
				 {
				        $str=iconv("gbk","utf-8",$str);         //utf-8�������
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
 
*     ��ȡ������ѵ�����
**************************************************************************************/	
	 case "sharePoint": 	
	 						 
							 $id=$_REQUEST['id'];
							 $xpoint=$_REQUEST['xpoint'];
							 $ypoint=$_REQUEST['ypoint'];

							 
							 $id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     ��ȡ����IP
**************************************************************************************/		
	 case "getIP":  		
			 	      
					 $str=getIP();
					 if($str)
					 {
					         $str=iconv("gbk","utf-8",$str);         //utf-8�������
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
 
*    ��ȡ��������
**************************************************************************************/		
	 case "getAddress":  		
			 	 
					 $str=getAddress();
					 if($str)
					 {
					         $str=iconv("gbk","utf-8",$str);         //utf-8�������
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
 
*     ����
**************************************************************************************/	
	 
	 case "offLine":    
				$id=$_REQUEST['id'];
				
				$id=iconv("utf-8","gbk",$id);                               //ת����ʽ
					
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
 
*     �޸��Ƿ���
**************************************************************************************/	
	 
	 case "changeShare":    
		                     $id=$_REQUEST['id'];
							 $albumName=$_REQUEST['albumName'];
							 $share=$_REQUEST['share'];


							 $id=iconv("utf-8","gbk",$id);                               //ת����ʽ
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
 
*     Э�����
**************************************************************************************/	
	 
	 default:             $_SESSION["temp"]="wrong protocol";
						  header('Location: send.php');
						 
	break;
	 }
 ?>