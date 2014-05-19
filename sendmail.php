<?
function sendMail($to,$Body)
{
		require_once 'Mail.php';
	//	flush();                                     
		
		$conf['mail'] = array(
				'host'     => 'smtp.qq.com',   //smtp服务器地址，可以用ip地址或者域名
				'auth'     => true,                 //true表示smtp服务器需要验证，false代码不需要
				'username' => '260786636@qq.com',           //用户名 
				'password' => 'fangqiang'               //密码
		);
		
		$headers['From']    = '260786636@qq.com';              //发信地址
		$headers['To']      = $to;              //收信地址
		$headers['Subject'] = 'test mail send by php';          //邮件标题
		$mail_object = &Mail::factory('smtp', $conf['mail']);   
		
		$body =$Body;
		//邮件正文
		$mail_res = $mail_object->send($headers['To'], $headers, $body);        //发送
		
		if( PEAR::isError($mail_res) )
		{                         //检测错误
		 //  echo "$mail_res->getMessage()";
		   return false;
		}
		else
			 return true;
}
//sendMail("15142436758@163.com","hun");
?>