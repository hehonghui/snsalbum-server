<?
function sendMail($to,$Body)
{
		require_once 'Mail.php';
	//	flush();                                     
		
		$conf['mail'] = array(
				'host'     => 'smtp.qq.com',   //smtp��������ַ��������ip��ַ��������
				'auth'     => true,                 //true��ʾsmtp��������Ҫ��֤��false���벻��Ҫ
				'username' => '260786636@qq.com',           //�û��� 
				'password' => 'fangqiang'               //����
		);
		
		$headers['From']    = '260786636@qq.com';              //���ŵ�ַ
		$headers['To']      = $to;              //���ŵ�ַ
		$headers['Subject'] = 'test mail send by php';          //�ʼ�����
		$mail_object = &Mail::factory('smtp', $conf['mail']);   
		
		$body =$Body;
		//�ʼ�����
		$mail_res = $mail_object->send($headers['To'], $headers, $body);        //����
		
		if( PEAR::isError($mail_res) )
		{                         //������
		 //  echo "$mail_res->getMessage()";
		   return false;
		}
		else
			 return true;
}
//sendMail("15142436758@163.com","hun");
?>