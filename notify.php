<?php
/**
* Notify � ����� ����������� ������������ ��� Codeigniter
*
* ������ ���������� � php:
*
* ����� ����� ������ ��� ���������
* $this->notify->returnError('����� ������');
*
* ����� ���������� ���������
* $this->notify->error('��������� �����-�� ������');
* $this->notify->success('�� �������� ����� �� ���������');
* $this->notify->returnNotify();
*
* @package codeigniter-notify-library
* @author Eduardo Kozachek <eduard.kozachek@gmail.com>
* @version $Revision: 1 $
* @access public
* @see http://nadvoe.org.ua
*/

class Notify
{
	/**
	* returns the sample data
	*
	* @param string $sample the sample data
	
	* @access private
	*/
	private $notify, $returnTo, $ci_session, $additionalData;
	
	private $css = "
	<style>
		/** Notification **/
		.notify
		{
			display:block;
			position:fixed;
			top:20px;
			right:20px;
		}
		
		.notice{
		  position: relative;
		  padding: 15px 15px;
		  margin-bottom: 18px;
		  color: #404040;
		  background-color: #eedc94;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#fceec1), to(#eedc94));
		  background-image: -moz-linear-gradient(top, #fceec1, #eedc94);
		  background-image: -ms-linear-gradient(top, #fceec1, #eedc94);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fceec1), color-stop(100%, #eedc94));
		  background-image: -webkit-linear-gradient(top, #fceec1, #eedc94);
		  background-image: -o-linear-gradient(top, #fceec1, #eedc94);
		  background-image: linear-gradient(top, #fceec1, #eedc94);
		  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fceec1', endColorstr='#eedc94', GradientType=0);
		  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		  border-color: #eedc94 #eedc94 #e4c652;
		  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
		  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
		  border-width: 1px;
		  border-style: solid;
		  -webkit-border-radius: 4px;
		  -moz-border-radius: 4px;
		  border-radius: 4px;
		  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.25);
		  -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.25);
		  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.25);
		}
		
		.notice
		{
			margin-bottom: 10px;
		}
		
		.notice .close
		{
			font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
			float: right;
			color: #000000;
			font-size: 20px;
			font-weight: bold;
			line-height: 13.5px;
			text-shadow: 0 1px 0 #ffffff;
			filter: alpha(opacity=20);
			-khtml-opacity: 0.2;
			-moz-opacity: 0.2;
			opacity: 0.2;
			text-decoration: none;
		}
		
		.notice .close:hover
		{
		  color: #000000;
		  text-decoration: none;
		  filter: alpha(opacity=40);
		  -khtml-opacity: 0.4;
		  -moz-opacity: 0.4;
		  opacity: 0.4;
		}

		.notice strong
		{
			font-weight: bold;
			color:inherit;
		}
		
		.notice.success{
		  background-color: #57a957;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#62c462), to(#57a957));
		  background-image: -moz-linear-gradient(top, #62c462, #57a957);
		  background-image: -ms-linear-gradient(top, #62c462, #57a957);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #62c462), color-stop(100%, #57a957));
		  background-image: -webkit-linear-gradient(top, #62c462, #57a957);
		  background-image: -o-linear-gradient(top, #62c462, #57a957);
		  background-image: linear-gradient(top, #62c462, #57a957);
		  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#62c462', endColorstr='#57a957', GradientType=0);
		  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		  border-color: #57a957 #57a957 #3d773d;
		  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
		  color:#FFF;
		}
		.notice.error
		{
		  background-color: #c43c35;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#ee5f5b), to(#c43c35));
		  background-image: -moz-linear-gradient(top, #ee5f5b, #c43c35);
		  background-image: -ms-linear-gradient(top, #ee5f5b, #c43c35);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ee5f5b), color-stop(100%, #c43c35));
		  background-image: -webkit-linear-gradient(top, #ee5f5b, #c43c35);
		  background-image: -o-linear-gradient(top, #ee5f5b, #c43c35);
		  background-image: linear-gradient(top, #ee5f5b, #c43c35);
		  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ee5f5b', endColorstr='#c43c35', GradientType=0);
		  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		  border-color: #c43c35 #c43c35 #882a25;
		  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
		  color: #FFF!important;
		}
		
		.notice.info
		{
		  background-color: #339bb9;
		  background-repeat: repeat-x;
		  background-image: -khtml-gradient(linear, left top, left bottom, from(#5bc0de), to(#339bb9));
		  background-image: -moz-linear-gradient(top, #5bc0de, #339bb9);
		  background-image: -ms-linear-gradient(top, #5bc0de, #339bb9);
		  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #5bc0de), color-stop(100%, #339bb9));
		  background-image: -webkit-linear-gradient(top, #5bc0de, #339bb9);
		  background-image: -o-linear-gradient(top, #5bc0de, #339bb9);
		  background-image: linear-gradient(top, #5bc0de, #339bb9);
		  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5bc0de', endColorstr='#339bb9', GradientType=0);
		  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		  border-color: #339bb9 #339bb9 #22697d;
		  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
		  color:#FFF;
		}
	</style>
	";
	
	
	
	private $js = '
		<script type="text/javascript">
			function notify(json)
			{
				var now = new Date();
				now = now.getTime();
				for (key in json)
				{
					if ( key != "data" && key != "comeback" )
						jQuery(".notify").prepend("<div time=\""+now+"\" class=\"notice "+json[key].type+"\"><p>"+json[key].message+"</p></div>");
						
					if ( key == "comeback" && json["comeback"] != null && json["comeback"] != "" )
						window.location = json["comeback"];
				}
					
				jQuery(".notice",".notify").click(function(){ jQuery(this).fadeOut(300); });
			}
			
			function notifyError(message)
			{
				var json = [{
					"isError" : 1,
					"type" : "error",
					"message" : message
				}];
				notify(json);
			}
			
			function notifySuccess(message)
			{
				var json = [{
					"isError" : 0,
					"type" : "success",
					"message" : message
				}];
				notify(json);
			}
			
			function close_old_notifies()
			{
			
				var now = new Date();
				now = now.getTime();
				jQuery(".notify").children().each(function()
				{
					var notice_time = jQuery(this).attr("time");
					
					if ( (now-notice_time) > 2500 )
						jQuery(this).fadeOut(800);
					else
						jQuery(this).attr("time",now);
				});
			}
			
			
			
			jQuery(document).ready(function()
			{
				jQuery(".notice",".notify").live("click",function(){ jQuery(this).fadeOut(300); });
				
				setInterval("close_old_notifies()",2500);
				
				jQuery(".notice",".notify").hover(function()
				{
					jQuery(this).css("opacity","1");
				},
				function()
				{
					jQuery(this).css("opacity","0.5");
				});
			});
		</script>
	';
	
	
	
	/**
	* ����������� ��������� ������ ������ ����� ������ � ���������� ����� �������
	*
	* @access public
	*/
	function __construct()
	{
		// ��������� Codeigniter
        $this->_ci =& get_instance();
		
		// ��������� ������
		$this->ci_session = $this->_ci->session;
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			$this->isPosted = true;
		else
			$this->isPosted = false;

	}
	
	
	/**
	* ������� ��������� ����������� � ���������� ����� � javascript
	* �� ����������� �����������
	*
	* @global string $this->css - �����
	* @global string $this->js - �������
	* @return string HTML
	* @access public
	*/
	public function initJsCss()
	{
		$html = '<!--Notify-->';
		$html .= $this->css;
		$html .= $this->js;
		$html .= '<!--Notify-->';
	  
		return $html;
	}
	
	
	/**
	* �������� ������� ��������
	* �� ��� ���������� ������� �����������
	*
	* @param string $json ��������� ������ � ����� ��������� � �������
	* @global string $_SERVER['HTTP_REFERER'] | $this->returnTo - ����� ���������� ��������
	* @uses Session ������ codeigniter
	* @uses base_url() ������� ������������ �������� ����������
	* @uses site_url() ������� �������������� ����� ����������
	* @return array ��������� ������ ��������� � ������
	* @return json ������ json-������ � javascript
	* @access public
	*/
	public function returnNotify($json='')
	{
		if ($this->returnTo != '')
		{
			if ($this->returnTo == '/')
				$this->returnTo = base_url();
			elseif(strstr($this->returnTo,'http://'))
				$this->returnTo = $this->returnTo;
			else
				$this->returnTo = site_url($this->returnTo);
		}
		
		if ($json=='')
			$json = $this->notify;
		
		$json['data'] = $this->additionalData;
		$json['comeback'] = $this->returnTo;
		
		if ($this->_ci->input->is_ajax_request())
		{
			$json = json_encode($json);
			die($json);
		}
		else
		{
			if ($this->returnTo == '' && isset($_SERVER['HTTP_REFERER']))
			{
				$this->returnTo = $_SERVER['HTTP_REFERER'];
			}
			else
				$this->returnTo = base_url();

			$this->ci_session->add_userdata('notify',$json);

			redirect($this->returnTo);
			
			$this->returnTo = '';
			die();
		}
	}
	
	
	/**
	* ���������� ������ � �������
	*
	* @param string $message - ����� ���������
	* @global string $this->notify - ������� ���������
	* @access public
	*/
	public function error($message)
	{
		$this->notify[] = array("type"=>"error","message"=>$message);
	}
	
	/**
	* ���������� ��������� � �������
	*
	* @param string $message - ����� ���������
	* @global string $this->notify - ������� ���������
	* @access public
	*/
	public function success($message)
	{
		$this->notify[] = array("type"=>"success","message"=>$message);
	}
	
	/**
	* ���������� ��������� � ������� � ����������� ���������� �������
	*
	* @param string $message - ����� ���������
	* @global string $this->notify - ������� ���������
	* @access public
	*/
	public function returnError($message)
	{
		$json = array(array("isError"=>1,"type"=>"error","message"=>$message));
		
		$this->returnNotify($json);
	}
	
	/**
	* ���������� ��������� � ������� � ����������� ���������� �������
	*
	* @param string $message - ����� ���������
	* @global string $this->notify - ������� ���������
	* @access public
	*/
	public function returnSuccess($message)
	{
		$json = array(array("isError"=>0,"type"=>"success","message"=>$message));
		
		$this->returnNotify($json);
	}

	/**
	* ���������� ������ � �����
	*
	* @param array $data - ������
	* @global string $this->additionalData - ������
	* @access public
	*/
	public function setData($data)
	{
		$this->additionalData = $data;
	}
	
	/**
	* ��������� ������ �� ������
	*
	* @global string $this->additionalData - ������
	* @global string $this->ci_session - ������
	* @access public
	*/
	public function getData()
	{
		// ����������� �������� �������
		if (isset($this->additionalData))
			$additionalData = $this->additionalData;
		else
		{
			$sess = $this->ci_session->userdata('notify');
			
			if (isset($sess['data']) && $sess['data'])
			{
				$additionalData = $sess['data'];
			}
			else
				$additionalData = '';
		}
			
		// ����������� ����������� �������
		
		return $additionalData;
	}
	
	/**
	* ��������� ������ ���������������
	*
	* @param string $url - URL
	* @global string $this->returnTo - URL
	* @access public
	*/
	public function setComeback($url)
	{
		$this->returnTo = $url;
	}
	
	/**
	* ����� � ������� ������� ���������
	*
	* @global string $this->notify - ������� ���������
	* @uses Session CI_Session
	* @access public
	*/
	public function getMessages()
	{

		// ����������� �������� �������
		if (isset($this->notify) && is_array($this->notify) && count($this->notify))
			$notifies = $this->notify;
		else
			$notifies = array();
			
		// ����������� ����������� �������
		$sess = $this->ci_session->userdata('notify');
		
		if (isset($sess) && is_array($sess) && count($sess))
			$notifies = array_merge($notifies,$sess);
		
		// �����
		$html = '';
		if (isset($notifies) && is_array($notifies) && count($notifies))
		{
			foreach($notifies as $n)
			{
				if (is_array($n) && !isset($n['type']))
				{
					foreach($n as $nn)
					{
					  $html .= '
					  <div time="'.(time()*1000).'" class="notice '.$nn['type'].'">
						<p>
							'.$nn['message'].'
						</p>
					  </div>';
					}
				}
				else
				{
					$html .= '
					<div time="'.(time()*1000).'" class="notice '.$n['type'].'">
					  <p>
						  '.$n['message'].'
					  </p>
					</div>';
				}
			}
		}
		
		$this->ci_session->unset_userdata('notify');
		
		return '<div class="notify">'.$html.'</div>';
	}
	
	
	
}