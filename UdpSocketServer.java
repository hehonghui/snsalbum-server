package server;
/**
 * Copyright (c) 2012,UIT-ESPACE( TEAM: UIT-GEEK)
 * All rights reserved.
 *
 * @Title: Server.java 
 * @Package:  
 * @Author: �κ��(Mr.Simple) 
 * @E-mail:bboyfeiyu@gmail.com
 * @Version: V1.0
 * @Date��2012-11-14 ����10:39:31
 * @Description:
 * 1.
 *
 */
import java.io.IOException;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.InetAddress;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Vector;

import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;


public class UdpSocketServer {
	private final static int PORT = 9876;
	public static void main(String[] rrgs) throws IOException {
		serverFrame frame = new serverFrame();
		boolean bStop = false;
		// ������SOCKET
		DatagramSocket serverSocket = new DatagramSocket( PORT );
		while (!bStop) {
			try {
				byte[] receiveData = new byte[1024];
				DatagramPacket revPacket = new DatagramPacket(receiveData, receiveData.length);
				println("1��sokcet�ȴ�������...");
				serverSocket.receive( revPacket );

				// ����ת����Ϣ���߳���������Ϣ
				new Thread( new SendThread(serverSocket ,revPacket, frame) ).start();
			} catch (Exception e) {
				e.printStackTrace();
				bStop = true;
			}
			
		}// end of while()
		
		serverSocket.close();
		serverSocket = null;
	}

	private static void println(String msg) {
		System.out.println(msg);
	}

}

/**
 * 
 * @ClassName: SendThread
 * @Description: ת����Ϣ���ڲ���
 * @Author: Mr.Simple
 * @E-mail: bboyfeiyu@gmail.com
 * @Date 2012-11-14 ����10:52:11
 * 
 */
class SendThread implements Runnable {
	// Ŀ�������Ķ˿�
	private int mTargetPort = 8765;
	// ����ת����Ϣ��socket
	private DatagramSocket mSocket = null;
	// Ҫת����packet
	private DatagramPacket mPacket = null;

	private serverFrame frame = null;
	private Map<String, String> addrMap = new HashMap<String, String>();

	/**
	 * 
	 * @Constructor:
	 * @param data
	 *            ���յ������ߵ�DatagramPacket
	 * @Description: ���յ���Ϣ���ת���߳�
	 */
	public SendThread(DatagramSocket socket, DatagramPacket data, serverFrame frame) {
	
		this.frame = frame;
		mSocket = socket;
		this.mPacket = data;
		// �����ߵ��û���ӵ�������
		addToAddrList();
	}

	/**
	 * run����
	 */
	public void run() {
		try {

			// �����յ�������ת�����ַ�������,�ӵ��ĸ�ʽΪ "CHAT_MSG;;Ŀ�����IP;;������IP;;�������ǳ�;;����"
			String content = new String(mPacket.getData(), 0,
												mPacket.getLength());
			println("2�����յ�������Ϊ: " + content);
			
			// ��ȡ�����Ƿ����ߵ�IP��ַ��˿�
			InetAddress addr = mPacket.getAddress();
			int port = mPacket.getPort();

			println("�����ߵ�IPΪ: " + addr.toString() + ",�˿�: " + port);

			if (!content.contains(";;")) {
				frame.addMsg("�����ָ���ʧ��", "", "", "","");
				return;
			}

			// �����ݷ������
			String cont[] = content.split(";;");
			// ��ȡĿ�����IP
			String targetAddr = cont[1];
			// ͨ������IP��ȡ��������IP�Լ��˿�
			
			
			String userInfo = searchPortFromMap(targetAddr);
			if ( userInfo == null ) {
				println("������������ʧ��...");
				frame.addMsg("������������ʧ��", "", "", "","");
				return;
			}
			
			String target[] = userInfo.split(":");
			targetAddr = target[0];
			mTargetPort = Integer.parseInt(target[1]);

			// Ҫת�������� ,��ʽΪ "CHAT_MSG;;������IP;�ǳ�;;����"
			String send = cont[0] + cont[2] + cont[3] + cont[4];
			byte[] sendData = send.getBytes();

			// ����Ҫ���ͳ�ȥ��DatagramPacket
			frame.addMsg(addr.toString(), String.valueOf(port), target[0], target[1], send);
			DatagramPacket sendPacket = new DatagramPacket(sendData,
									sendData.length, InetAddress.getByName( targetAddr ), mTargetPort);
			println("Ŀ��IP: " + sendPacket.getAddress().toString() + ", Ŀ��˿�: "
							+ sendPacket.getPort());
			// ����packet
			mSocket.send( sendPacket );
			println("3.��Ϣת���ɹ�\n\n");

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	
	/**
	 * 
	 * @Method: addToAddrList
	 * @Description: �����ӵ����������û���IP��˿ڴ��map,addr�д洢�����û��Ĺ�������IP��˿�
	 * @return void ��������
	 * @throws
	 */
	private void addToAddrList() {
		// �����յ�������ת�����ַ�������,�ӵ��ĸ�ʽΪ "CHAT_MSG;;Ŀ�����IP;;������IP;;�������ǳ�;;����"
		String content = new String(mPacket.getData(), 0, mPacket.getLength());
		String userIp = "";
		
		if (content.startsWith("$") && content.endsWith("$")) {
			// ��ȡ��ʵ��IP��ַ
			userIp = content.substring(1, content.length() - 1);
			println("�û���ʵIPΪ �� " + userIp) ;
			
			// ��ȡ�����Ƿ����ߵ�IP��ַ��˿�,���ҽ�����ӵ�map�д洢
			String addr = mPacket.getAddress().toString().replace("/", "");
			int port = mPacket.getPort();
			
			
			addr = addr + ":" + port;
			// ����Ѿ���������ɾ��,������µ�����
			if ( addrMap.containsKey( userIp ) ){
				addrMap.remove( userIp ) ;
			}
			// �����ݴ��map��
			addrMap.put(userIp, addr);
			println("������û�,����IP����Ϊ-->" + addr.toString() + ", Map size=" + addrMap.size() + "\n");
			
			Iterator iter = addrMap.keySet().iterator();
			Vector array = new Vector();
			String temp="";
			while(iter.hasNext())
			{
				String key="";
				String value="";
				
				key += iter.next();
				value += addrMap.get(key);
				temp = key+"  |  "+value;
				array.add(temp);
			}
			frame.updateOnline(array);
		}
		
	}

	/**
	 * 
	 * @Method: searchPortFromMap
	 * @Description: ��map���������IP��Ӧ�Ĺ��������Լ��˿�
	 * @param fip
	 *            �����ѵ�IP��ַ
	 * @return String ��������
	 * @throws
	 */
	private String searchPortFromMap(String fip) throws Exception {

		String userInfo = addrMap.get( fip ) ;
		println("����ip : " + fip + ", �ҵ�������Ϊ �� " + userInfo ) ;
		return userInfo;
	}

	private void println(String msg) {
		System.out.println(msg);
	}
}
    class serverFrame extends JFrame{
	JList  onlineList =new JList();
	JScrollPane scrollMsg = null;
	JScrollPane scrollOnline = null;
	JTextArea msg= new JTextArea();
	JLabel  lab_online = new JLabel("����IP��Ӧ��");
	JLabel  lab_msg = new JLabel("��Ϣת����");
    static int count=1;
    serverFrame()
	{
    	super("��̨������");
		onlineList =new JList();
		scrollOnline = new JScrollPane(onlineList);
		scrollMsg = new JScrollPane(msg);
		
		lab_online.setBounds(10, 10, 100, 20);
		lab_msg.setBounds(270, 10, 100, 20);
		
		scrollMsg.setBounds(270, 30, 800, 350);
		scrollOnline.setBounds(10, 30, 250, 350);


		msg.setEditable(false);

		this.add(lab_online);
		this.add(lab_msg);
		this.add(scrollMsg);
		this.add(scrollOnline);

		this.setLayout(null);
		this.setSize(1100,440);
		this.setLocation(300,100);
		this.setDefaultCloseOperation(EXIT_ON_CLOSE);
		this.setVisible(true);
	}
	public void updateOnline(Vector array)
	{
		this.remove(scrollOnline);

		JList onlineList =new JList(array);
		JScrollPane scrollOnline = new JScrollPane(onlineList);
		scrollOnline.setBounds(10, 30, 250, 350);
		this.add(scrollOnline);
		this.setVisible(true);
	}
	public  void addMsg(String senderIP,String senderPort,String recverIP,String recverPort,String message)
	{
		msg.append((count++)+"������IP:"+senderIP+"�˿�: "+senderPort+" | ������IP:"+recverIP+"�˿�:"+recverPort+"  ��Ϣ:"+message+"\n");
	}
}
