<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	private $url_auth = "http://localhost/api/admin/auth/index";
	private $key_auth = "3a226cb481d3d46905c35920813b4fca"; 

	private $username = "asdi";
	private $password = "87JQH!B5NAJ36YTG";

	private $key_client = "123";
	
	public function __construct()
	{
       	parent::__construct();  
       			
		$data = $this->request_key(getallheaders(),$this->username,$this->password,$this->key_client);
		$auth = json_decode($data);
		if(($auth->auth_server != $this->key_auth)||($auth->auth_client != sha1($this->key_client))) exit;			         				
    }
    //-----------------------------------------		
	private function postdata($url_auth,$key_server,$key_client)
	{
		$postdata = http_build_query(
		    array(
		        'key_server' => $key_server,
			    'key_client' => $key_client			        
		    )
		);

		$opts = array('http' =>
		    array(
		        'method'  => 'POST',
		        'header'  => 'Content-Type: application/x-www-form-urlencoded',
		        'content' => $postdata
		    )
		);

		$context  = stream_context_create($opts);

		$result = file_get_contents($url_auth, false, $context);

		return $result;
	}
	
	private function request_key($data,$username,$password,$key_client)
	{		
		$auth = explode('-',$data['auth']);
		if(($auth[0] == $username)&&($auth[1] == $password))
		{
			return $this->postdata($this->url_auth,$auth[2],$key_client);
		}
	}
	//-----------------------------------------	
	public function index()
	{		
	}
	public function get()
	{
		if($this->input->server('REQUEST_METHOD') == "GET")
		{
			if ($this->uri->segment(3) == "auth")
			{				
				echo $this->input->get("id",TRUE);
			}
		}
	}
	public function post()
	{
		if($this->input->server('REQUEST_METHOD') == "POST")
		{
			if ($this->uri->segment(3) == "auth")
			{				
				echo $this->input->post("username",TRUE);
				echo $this->input->post("password",TRUE);											
			}
		}
	}
	public function put()
	{
		if($this->input->server('REQUEST_METHOD') == "PUT")
		{
			if ($this->uri->segment(3) == "auth")
			{				
				echo $this->input->input_stream("username",TRUE);
				echo $this->input->input_stream("password",TRUE);							
			}
		}
	}
	public function delete()
	{
		if($this->input->server('REQUEST_METHOD') == "DELETE")
		{
			if ($this->uri->segment(3) == "auth")
			{				
				echo $this->input->input_stream("id",TRUE);															
			}
		}
	}
	public function raw()
	{
		if($this->input->server('REQUEST_METHOD') == "POST")
		{
			if ($this->uri->segment(3) == "auth")
			{				
					$json = file_get_contents("php://input");	
					echo $json;															
			}
		}
		
	}
	
}
