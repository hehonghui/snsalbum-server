<?php
/*************************************************************************************
 
*     �������ݿ�
**************************************************************************************/	                          
				$link=mysql_connect("localhost","root","");//or die("�޷���������<br>");
				 

				if($link)
				{
					mysql_select_db("android");
				}
				 mysql_query("set names gbk"); //��������


/*************************************************************************************
 
*     ��ȡ�ǳ�
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
		//	echo"û�д��û�<br>";
			return false;
			}
		}
		else
		{
		//    echo "��ȡ����ʧ��". mysql_error()."<br>";
			return false;
		}
		
}	 
/*************************************************************************************
 
*     �˶Ե����Ƿ�Ϸ�
**************************************************************************************/	
function checkUser($id,$pwd)                    
{

		$result=mysql_query("select id,pwd from user where id='$id' and pwd='$pwd'");
		if($result==true)
		{
			if(mysql_num_rows($result)==1)
			{
		//	 echo "�Ϸ�����<br>";
			 return true;
			 }
			else
			{
		//	echo"���Ϸ�����<br>";
			return false;
			}
		}
		else
		{
		//    echo "�˶����ʧ��". mysql_error()."<br>";
			return false;
		}
		
}
/*************************************************************************************
 
*     �޸�����
**************************************************************************************/	
function changePassword($id,$pwd,$new)                
{

		if(checkUser($id,$pwd)==true)
		{
			if(mysql_query("update user set pwd='$new' where id='$id' and pwd='$pwd'")==true) 
			{
		//		echo "�����޸ĳɹ�<br>";
				return true;
			}
			else
			{
		//	   echo "�޸�����ʧ��<br>". mysql_error()."<br>";
			   return false;
			}
		}
		else
		{
		//	echo "���Ϸ�����<br>";
			return false;
		}
		
}
/*************************************************************************************
 
*     ִ�в������
**************************************************************************************/	
function insertUser($id,$pwd,$name,$email)					
{

		$insertuser="insert into user values('$id','$pwd','$name','$email')";
		if(true== mysql_query($insertuser))
		{
		//     echo "�û������ɹ�<br>";
			 return true;
		}
		else
		{
		//     echo "�û�����ռ��<br>".mysql_error()."<br>";
			 return false;
		}
		
}
/*************************************************************************************
 
*     �޸�����
**************************************************************************************/	
function changeGroupName($id,$picturegroup,$newname)         
{

		$str="update picturegroup set picturegroup='$newname' where id='$id' and picturegroup='$picturegroup'";
		if(mysql_query($str)==true)                    //          ɾ��ԭ���
		{
			rename("IMAGES/$id/$picturegroup","IMAGES/$id/$newname");
		//	echo "�޸����ɹ�<br>";
		    if(hasAffectedLines())
			    return true;
			else
			    return false;
		}
		else
		{
		//	echo "�޸����ʧ��<br>".mysql_error();
			return false;
		}
	
}
/*************************************************************************************
 
*     ������ �������
**************************************************************************************/	
function insertGroup($id,$picturegroup,$share)                  
{ 

		$insertgroup="insert into picturegroup values('$id','$picturegroup','$share')";
		if(mysql_query($insertgroup)==true)            
		{
	//	    echo "�������ɹ�<br>";
			return true;
		}
		else
		{
		//	echo "�������ʧ��,������ͬ�����<br>".mysql_error()."<br>";
			return false;
		}
		
}
/*************************************************************************************
 
*     ɾ����
**************************************************************************************/	
function deleteGroup($id,$picturegroup)					
{

        deldir("IMAGES/$id/$picturegroup");                      //ɾ���ļ���
		$deletepicturegroup="delete from picturegroup where picturegroup.id='$id' and picturegroup.picturegroup='$picturegroup'";
		 if(mysql_query($deletepicturegroup)==true)
		{ 
//			echo " ɾ�����ɹ�<br>";
            if(hasAffectedLines())
			     return true;
		    else
				 return false;
		}
		else
		{
//		   echo "ɾ�����ʧ��<br>". mysql_error()."<br>";
		   return false;
		}
			
}
/*************************************************************************************
 
*     �޸�ͼƬ��
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
		//	   echo"�޸�ͼƬ�ɹ�<br>";
			   if(hasAffectedLines())
			       return true;
		       else
				   return false;
		}
		else
		{
		//	echo"�޸�ͼƬʧ��<br>".mysql_error()."<br>";
			return false;
		}
		
}

/*************************************************************************************
 
*     ����ͼƬ
**************************************************************************************/	
function insertPicture($id,$picturegroup,$pciturename)
{

		$insertpicture="insert into picture values('$id','$picturegroup','$pciturename')";
		if(mysql_query($insertpicture)==true)
		{
		//	   echo"����ͼƬ�ɹ�<br>";
			   return true;
		}
		else
		{
		//	echo "����ͼƬʧ��<br>".mysql_error()."<br>";
			return false;
		}
		
}
/*************************************************************************************
 
*     ִ��ɾ��ͼƬ����
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
	//		   echo"ɾ��ͼƬ�ɹ�<br>";
			   if(hasAffectedLines())
			     return true;
		       else
				 return false;
		}
		else
		{
	//		 echo "ͼƬɾ���ɹ�<br>".mysql_error()."<br>";
			 return false;
		}
		
}
/*************************************************************************************
 
*     ��ȡ������
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
		//	 echo "��ȡ�����ɹ�<br>";
			 return $str;
		 }
		 else
		 {
		//    echo "��ȡ�����ʧ��<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}
/*************************************************************************************
 
*     ��ȡ��������ͼ
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
		//	 echo "��ȡ����ͼ��ɹ�<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "��ȡ����ͼ��ʧ��<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}			
/*************************************************************************************
 
*     ��ȡ�й������ĳ�Ա
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
		//	 echo "��ȡ�й������ĳ�Ա�ɹ�<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "��ȡ�й������ĳ�Աʧ��<br>".mysql_error()."<br>";
			return  false;
		 }

}
/*************************************************************************************
 
*     ��ȡָ����Ա�Ĺ������
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
		//	 echo "��ȡָ����Ա�Ĺ������ɹ�<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "��ȡָ����Ա�Ĺ������ʧ��<br>".mysql_error()."<br>";
			return  false;
		 }
}
/*************************************************************************************
 
*     ɾ���ļ���
**************************************************************************************/	
function deldir($dir)
{

	  //��ɾ��Ŀ¼�µ��ļ���
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
		  //ɾ����ǰ�ļ��У�
		  if(rmdir($dir)) {
			return true;
		  } else {
			return false;
		  }

}  
/*************************************************************************************
 
*     ִ�в������ߵ���
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
 
*     ִ�в�������
**************************************************************************************/	
function sharePoint($id,$xpoint,$ypoint)					
{

		$insertonline="update online  set xpoint='$xpoint',ypoint='$ypoint'  where id='$id'";
		if(true== mysql_query($insertonline))
		{
		//     echo "�û������ɹ�<br>";
			if(hasAffectedLines())
			     return true;
		    else
				 return false;
		}
		else
		{
		//     echo "�û�����ռ��<br>".mysql_error()."<br>";
			 return false;
		}
		
}
/*************************************************************************************
 
*     ��ȡ����IP
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
		//	 echo "��ȡ����ͼ��ɹ�<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "��ȡ����ͼ��ʧ��<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}			
/*************************************************************************************
 
*     ��ȡ��ַ
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
		//	 echo "��ȡ����ͼ��ɹ�<br>";
			 return $str;
		 }
		 else
		 {
	//	    echo "��ȡ����ͼ��ʧ��<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}			
/*************************************************************************************
 
*     ����
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
 
*     �Ƿ�Ӱ�������ݿ�
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
function getAllGroup($id)                                 //��ȡ������
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
		//	 echo "��ȡ�����ɹ�<br>";
			 return $str;
		 }
		 else
		 {
		//    echo "��ȡ�����ʧ��<br>".mysql_error()."<br>";
			return  false;
		 }
		 
}
*/
?>