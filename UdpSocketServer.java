package server;
/**
 * Copyright (c) 2012,UIT-ESPACE( TEAM: UIT-GEEK)
 * All rights reserved.
 *
 * @Title: Server.java 
 * @Package:  
 * @Author: 何红辉(Mr.Simple) 
 * @E-mail:bboyfeiyu@gmail.com
 * @Version: V1.0
 * @Date：2012-11-14 下午10:39:31
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
		// 服务器SOCKET
		DatagramSocket serverSocket = new DatagramSocket( PORT );
		while (!bStop) {
			try {
				byte[] receiveData = new byte[1024];
				DatagramPacket revPacket = new DatagramPacket(receiveData, receiveData.length);
				println("1、sokcet等待数据中...");
				serverSocket.receive( revPacket );

				// 启动转发消息的线程来处理消息
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
 * @Description: 转发消息的内部类
 * @Author: Mr.Simple
 * @E-mail: bboyfeiyu@gmail.com
 * @Date 2012-11-14 下午10:52:11
 * 
 */
class SendThread implements Runnable {
	// 目标主机的端口
	private int mTargetPort = 8765;
	// 用来转发消息的socket
	private DatagramSocket mSocket = null;
	// 要转发的packet
	private DatagramPacket mPacket = null;

	private serverFrame frame = null;
	private Map<String, String> addrMap = new HashMap<String, String>();

	/**
	 * 
	 * @Constructor:
	 * @param data
	 *            接收到发送者的DatagramPacket
	 * @Description: 接收到消息后的转发线程
	 */
	public SendThread(DatagramSocket socket, DatagramPacket data, serverFrame frame) {
	
		this.frame = frame;
		mSocket = socket;
		this.mPacket = data;
		// 将上线的用户添加到表里面
		addToAddrList();
	}

	/**
	 * run方法
	 */
	public void run() {
		try {

			// 将接收到的数据转换成字符串类型,接到的格式为 "CHAT_MSG;;目标好友IP;;发送者IP;;发送者昵称;;内容"
			String content = new String(mPacket.getData(), 0,
												mPacket.getLength());
			println("2、接收到的数据为: " + content);
			
			// 获取到的是发送者的IP地址与端口
			InetAddress addr = mPacket.getAddress();
			int port = mPacket.getPort();

			println("发送者的IP为: " + addr.toString() + ",端口: " + port);

			if (!content.contains(";;")) {
				frame.addMsg("解析分隔符失败", "", "", "","");
				return;
			}

			// 将数据分离出来
			String cont[] = content.split(";;");
			// 获取目标好友IP
			String targetAddr = cont[1];
			// 通过好友IP获取公网出口IP以及端口
			
			
			String userInfo = searchPortFromMap(targetAddr);
			if ( userInfo == null ) {
				println("查找主机出口失败...");
				frame.addMsg("查找主机出口失败", "", "", "","");
				return;
			}
			
			String target[] = userInfo.split(":");
			targetAddr = target[0];
			mTargetPort = Integer.parseInt(target[1]);

			// 要转发的内容 ,格式为 "CHAT_MSG;;发送者IP;昵称;;内容"
			String send = cont[0] + cont[2] + cont[3] + cont[4];
			byte[] sendData = send.getBytes();

			// 构造要发送出去的DatagramPacket
			frame.addMsg(addr.toString(), String.valueOf(port), target[0], target[1], send);
			DatagramPacket sendPacket = new DatagramPacket(sendData,
									sendData.length, InetAddress.getByName( targetAddr ), mTargetPort);
			println("目标IP: " + sendPacket.getAddress().toString() + ", 目标端口: "
							+ sendPacket.getPort());
			// 发送packet
			mSocket.send( sendPacket );
			println("3.消息转发成功\n\n");

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	
	/**
	 * 
	 * @Method: addToAddrList
	 * @Description: 将连接到服务器的用户的IP与端口存进map,addr中存储的是用户的公网出口IP与端口
	 * @return void 返回类型
	 * @throws
	 */
	private void addToAddrList() {
		// 将接收到的数据转换成字符串类型,接到的格式为 "CHAT_MSG;;目标好友IP;;发送者IP;;发送者昵称;;内容"
		String content = new String(mPacket.getData(), 0, mPacket.getLength());
		String userIp = "";
		
		if (content.startsWith("$") && content.endsWith("$")) {
			// 获取真实的IP地址
			userIp = content.substring(1, content.length() - 1);
			println("用户真实IP为 ： " + userIp) ;
			
			// 获取到的是发送者的IP地址与端口,并且将其添加到map中存储
			String addr = mPacket.getAddress().toString().replace("/", "");
			int port = mPacket.getPort();
			
			
			addr = addr + ":" + port;
			// 如果已经存在则先删除,再添加新的内容
			if ( addrMap.containsKey( userIp ) ){
				addrMap.remove( userIp ) ;
			}
			// 将数据存进map中
			addrMap.put(userIp, addr);
			println("存进了用户,公网IP出口为-->" + addr.toString() + ", Map size=" + addrMap.size() + "\n");
			
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
	 * @Description: 从map表里面查找IP对应的公网出口以及端口
	 * @param fip
	 *            即好友的IP地址
	 * @return String 返回类型
	 * @throws
	 */
	private String searchPortFromMap(String fip) throws Exception {

		String userInfo = addrMap.get( fip ) ;
		println("查找ip : " + fip + ", 找到的数据为 ： " + userInfo ) ;
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
	JLabel  lab_online = new JLabel("在线IP对应表");
	JLabel  lab_msg = new JLabel("消息转发表");
    static int count=1;
    serverFrame()
	{
    	super("后台服务器");
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
		msg.append((count++)+"发送者IP:"+senderIP+"端口: "+senderPort+" | 发送者IP:"+recverIP+"端口:"+recverPort+"  消息:"+message+"\n");
	}
}
