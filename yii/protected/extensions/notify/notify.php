<?php
/**
* Notify – класс уведомлений пользователя для Codeigniter
*
* Пример применения в php:
*
* Вывод одной ошибки или сообщения
* $this->notify->returnError('Текст ошибки');
*
* Вывод нескольких сообщений
* $this->notify->error('Случилась какая-то ошибка');
* $this->notify->success('Но основную часть мы выполнили');
* $this->notify->returnNotify();
*
* @package codeigniter-notify-library
* @author Eduardo Kozachek <eduard.kozachek@gmail.com>
* @version $Revision: 1.03 $
* @access public
* @see http://nadvoe.org.ua
* @changed 25.05.12 19:21
*/

class Notify extends CApplicationComponent
{
	
	/**
	* returns the sample data
	*
	* @param string $sample the sample data
	
	* @access private
	*/
	private $notify,
		$returnTo,
		$additionalData,
		$mustDie = true,
		$returnResult,
		$region = 'default',
		$ttl = 5,
		$silent;
	
	private $css = "
	
		/** Notification **/
		.notify.default
		{
			display:block;
			position:fixed;
			top:20px;
			right:20px;
			z-index: 50;
		}
		
		.notify.default .notice{
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
		
		.notify.default .notice
		{
			margin-bottom: 10px;
		}
		
		.notify.default .notice .close
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
		
		.notify.default .notice .close:hover
		{
		  color: #000000;
		  text-decoration: none;
		  filter: alpha(opacity=40);
		  -khtml-opacity: 0.4;
		  -moz-opacity: 0.4;
		  opacity: 0.4;
		}

		.notify.default .notice strong
		{
			font-weight: bold;
			color:inherit;
		}
		
		.notify.default .notice.success{
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
		
		.notify.default .notice.error
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
		
		.notify.default .notice.info
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
	
	";
	
	
	
	private $js = '
		
			function notify(json, globalTtl, globalRegion)
			{
				var now = new Date();
				now = now.getTime();
				for (key in json)
				{
					
					if ( key != "data" && key != "comeback" )
					{
						if (typeof globalRegion != "undefined" && globalRegion != null)
							var region = globalRegion;
						else if (json[key].region == null || json[key].region == "default")
							var region = "default";
						else
							var region = json[key].region;
						
						if (typeof globalTtl != "undefined" && globalTtl != null)
							var ttl = globalTtl*1000;
						else
							var ttl = json[key].ttl*1000;
							
							
						$(".notify."+region).prepend("<div data-ttl=\""+ttl+"\" class=\"notice "+json[key].type+"\"><p>"+json[key].message+"</p></div>");
					}
					
					if ( key == "comeback" && json["comeback"] != null && json["comeback"] != "" )
						window.location = json["comeback"];
				}
					
				$(".notice",".notify").click(function(){ $(this).fadeOut(300); });

			}
			
			function notifyIsSuccess(json)
			{
				if (json != undefined && json[0] != undefined && !json[0].isError)
					return true;
				else
					return false;
			}
			
			function notifyError(message, ttl, region)
			{
				var json = [{
					"isError" : 1,
					"type" : "error",
					"message" : message,
					"region"	: region,
					"ttl"		: ttl
				}];
				notify(json);
			}
			
			function notifySuccess(message, ttl, region)
			{
				var json = [{
					"isError" 	: 0,
					"type" 		: "success",
					"message" 	: message,
					"region"	: region,
					"ttl"		: ttl
				}];
				notify(json);
			}
			
			var notifyRegion;
			function notifySetRegion(region)
			{
				notifyRegion = region;
			}
			
			function close_old_notifies()
			{
			
				var now = new Date();
				now = now.getTime();
				if ($(".notify > div:visible").size())
				{
					$(".notify > div:visible").each(function()
					{
						if (!$(this).attr("data-time"))
						{
							$(this).attr("data-time",now);
						}
							
						var notice_time = $(this).attr("data-time");
						var notice_ttl = $(this).attr("data-ttl");
		
						if (notice_ttl != 0 && (now-notice_time) > notice_ttl )
							$(this).fadeOut(800);

					});
				}
			}
			
			
			
			
			$(document).ready(function()
			{
				$(".notice",".notify").live("click",function(){ $(this).fadeOut(300); });
				
				setInterval("close_old_notifies()",500);
				
				/*$(".notice",".notify").hover(function()
				{
					$(this).css("opacity","1");
				},
				function()
				{
					$(this).css("opacity","0.5");
				});*/
			});
	
	';
	

	
	/**
	* Функция выводящая сохраненные в библиотеке стили и javascript
	* Ее подключение обязательно
	*
	* @global string $this->css - стили
	* @global string $this->js - скрипты
	* @return string HTML
	* @access public
	*/
	public function initJsCss()
	{
		$html = '<!--Notify-->';
		$html .= '<style>'.$this->css.'</style>';
		$html .= '<script type="text/javascript">'.$this->js.'</script>';
		$html .= '<!--Notify-->';
	  
		return $html;
	}
	
		public function initJs($in_html = false)
		{
			$html = '';
			if ($in_html)
				$html .= '<!--Notify--><script type="text/javascript">';
			$html .= '/* Notify */';
			$html .= $this->js;
			$html .= '/* Notify */';
			if ($in_html)
				$html .= '</script><!--Notify-->';
		  
			return $html;
		}
		
		public function initCss($in_html = false)
		{
			$html = '';
			if ($in_html)
				$html .= '<!--Notify--><style>';
			$html .= '/* Notify */';
			$html .= $this->css;
			$html .= '/* Notify */';
			if ($in_html)
				$html .= '</style><!--Notify-->';
		  
			return $html;
		}
	
	/**
	* Основная функция возврата
	* На ней выполнение скрипта завершается
	*
	* @param string $json двумерный массив с типом сообщения и текстом
	* @global string $_SERVER['HTTP_REFERER'] | $this->returnTo - адрес предыдущей страницы
	* @uses Session
	* @uses getBaseUrl() Функция возвращающая корневую директорию
	* @uses createUrl() Функция преобразования путей приложения
	* @return array Сохраняет массив сообщений в сессию
	* @return json Выдает json-массив в javascript
	* @access public
	*/
	public function returnNotify()
	{
		if ($this->returnTo != '')
		{
			if ($this->returnTo == '/')
				$this->returnTo = Yii::app()->getBaseUrl(true);
			elseif(strstr($this->returnTo,'http://'))
				$this->returnTo = $this->returnTo;
			else
				$this->returnTo = Yii::app()->createUrl($this->returnTo);
		}
		
		$json = $this->notify;
		
		$json['data'] = $this->additionalData;
		
		
		if (Yii::app()->request->isAjaxRequest)
		{
			$json['comeback'] = $this->returnTo;
			$json = json_encode($json);
			die($json);
		}
		else
		{
			if ($this->returnTo == '')
			{
				if (isset($_SERVER['HTTP_REFERER']))
					$this->returnTo = $_SERVER['HTTP_REFERER'];
				else
				{
					$this->returnTo = Yii::app()->request->getBaseUrl(true);
				}
			}
			
		
			$data = Yii::app()->session->get('notify');
			
			if ($data && is_array($data))
			{
				$json = array_merge($data,$json);
			}
			
			Yii::app()->session['notify'] = $json;

			if ($this->mustDie)
			{
				Yii::app()->request->redirect($this->returnTo);
				$this->returnTo = '';
				die();
			}
			else
				return $this->returnResult OR false;
		}
	}
	
	
	/**
	* Добавление ошибки в очередь
	*
	* @param string $message - Текст сообщения
	* @param string $ttl - Время жизни, 0 - неограничено
	* @param string $region - Регион для вывода сообщения
	* @global string $this->notify - очередь сообщений
	* @access public
	*/
	public function error($message, $ttl = null, $region = null)
	{
		if (!$this->silent)
		{
			$this->notify[] = array(
				"isError"	=> 1,
				"type"		=> "error",
				"message"	=> $message,
				"region" 	=> !is_null($region) ? $region : $this->region,
				"ttl" 		=> !is_null($ttl) ? $ttl : $this->ttl
			);
		}
	}
	
	/**
	* Добавление сообщения в очередь
	*
	* @param string $message - Текст сообщения
	* @param string $ttl - Время жизни, 0 - неограничено
	* @param string $region - Регион для вывода сообщения
	* @global string $this->notify - очередь сообщений
	* @access public
	*/
	public function success($message, $ttl = null, $region = null)
	{
		if (!$this->silent)
		{
			$this->notify[] = array(
				"isError"	=> 0,
				"type"		=> "success",
				"message"	=> $message,
				"region" 	=> !is_null($region) ? $region : $this->region,
				"ttl" 		=> !is_null($ttl) ? $ttl : $this->ttl
			);
		}
	}
	
	/**
	* Добавление сообщения в очередь и прекращение выполнение скрипта
	*
	* @param string $message - Текст сообщения
	* @param string $ttl - Время жизни, 0 - неограничено
	* @param string $region - Регион для вывода сообщения
	* @access public
	*/
	public function returnError($message, $ttl = null, $region = null)
	{
		$this->error($message, $ttl, $region);
		
		$this->returnResult = false;
		
		return $this->returnNotify();
	}
	
	/**
	* Добавление сообщения в очередь и прекращение выполнение скрипта
	*
	* @param string $message - Текст сообщения
	* @param string $ttl - Время жизни, 0 - неограничено
	* @param string $region - Регион для вывода сообщения
	* @access public
	*/
	public function returnSuccess($message, $ttl = null, $region = null)
	{
		$this->success($message, $ttl, $region);
		
		$this->returnResult = true;
		
		return $this->returnNotify();
	}

	/**
	* Добавление данных в ответ
	*
	* @param array $data - Данные
	* @global string $this->additionalData - данные
	* @access public
	*/
	public function setData($data)
	{
		$this->additionalData = $data;
	}
	
	/**
	* Получение данных из ответа
	*
	* @global string $this->additionalData - данные
	* @global string $this->cisession - сессия
	* @access public
	*/
	public function getData()
	{
		// уведомления текущего запроса
		if (isset($this->additionalData))
			$additionalData = $this->additionalData;
		else
		{
			$sess = Yii::app()->session->get('notify');
			
			if (isset($sess['data']) && $sess['data'])
			{
				$additionalData = $sess['data'];
			}
			else
				$additionalData = '';
		}
			
		// уведомления предыдущего запроса
		
		return $additionalData;
	}
	
	/**
	* Установка адреса перенаправления
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
	* Вывод и очистка очереди сообщений
	*
	* @global string $this->notify - Очередь сообщений
	* @uses Session CI_Session
	* @access public
	*/
	public function getMessages($region = 'default')
	{

		// уведомления текущего запроса
		if (isset($this->notify) && is_array($this->notify) && count($this->notify))
			$notifies = $this->notify;
		else
			$notifies = array();
			
		// уведомления предыдущего запроса
		$sess = Yii::app()->session->get('notify');
		
		if (isset($sess) && is_array($sess) && count($sess))
			$notifies = array_merge($notifies,$sess);
		
		// вывод
		$html = '';
		
		if (isset($notifies) && is_array($notifies) && count($notifies))
		{
			foreach($notifies as $field => $n)
			{
				if (is_array($n) && !isset($n['type']))
				{
					foreach($n as $key => $nn)
					{
						if (isset($nn['region']) && $region == $nn['region'] && isset($nn['message']) && $nn['message'])
						{
							$html .= '
							<div ttl="'.$n['ttl'].'" data-ttl="'.($n['ttl']*1000).'" class="notice '.$nn['type'].'">
							  <p>
								  '.$nn['message'].'
							  </p>
							</div>';
							
							unset($notifies[$field]);
						}
					}
				}
				else
				{
					if (isset($n['region']) && $region == $n['region'] && isset($n['message']) && $n['message'])
					{
						$html .= '
						<div ttl="'.$n['ttl'].'" data-ttl="'.($n['ttl']*1000).'" class="notice '.$n['type'].'">
							<p>
								'.$n['message'].'
							</p>
						</div>';
						
						unset($notifies[$field]);
					}
				}
			
			}
		}

		if (!count($notifies))
			Yii::app()->session->remove('notify');
		else
			Yii::app()->session['notify'] = $notifies;

		return '<div class="notify '.$region.'">'.$html.'</div>';
	}
	
	
	/**
	* Метод для предотвращения прекращения выполнения скрипта 
	*
	* @param bool $mustDie - Включение/выключение прерывания скрипта
	* @access public
	*/
	public function mustDie($mustDie = true)
	{
		$this->mustDie = (bool)$mustDie;
	}
	
	
	/**
	* Метод для предотвращения прекращения выполнения скрипта 
	*
	* @param bool $mustDie - Включение/выключение прерывания скрипта
	* @access public
	*/
	public function setSilence($mode = false)
	{
		$this->silent = (bool)$mode;
	}
	
	
	/**
	* Метод для выбора места в скрипте, где именно выводить сообщения
	*
	* @param bool $mustDie - Включение/выключение прерывания скрипта
	* @access public
	*/
	public function setRegion($nameRegion = 'default')
	{
		$this->region = $nameRegion;
	}
	
	
	/**
	* Метод установки времени жизни сообщения
	*
	* @param bool $mustDie - Включение/выключение прерывания скрипта
	* @access public
	*/
	public function seTtl($ttl)
	{
		$this->ttl = $ttl;
	}
	
	
	/**
	* Calls the {@link registerScripts()} method.
	*/
	public function init() {
		
		$this->js = str_replace(array('{timeout}'),array($this->fadeTimeout),$this->js);
		
		Yii::app()->clientScript->registerScript('notify',$this->initJs(),CClientScript::POS_HEAD);
		
		Yii::app()->clientScript->registerCss('notify',$this->initCss());
		
		parent::init();	
	}
	
}