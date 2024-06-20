<?php
/**
 * A simple CSRF class to protect forms against CSRF attacks. The class uses
 * PHP sessions for storage.
 * 
 * @author Raahul Seshadri
 *
 */
class gcalender
{
	private $namespace;
	
	public function __construct()
	{
	}
	
	function sendNotification($arrParam)
	{
		
		include_once("functions/date.func.php");
		$CI =& get_instance();
		
		
		
		$summary    = $arrParam["JUDUL"];
		$description = $arrParam["PESAN"];
		$start_date = dateToPage($arrParam["TANGGAL_AWAL"])."T".($arrParam["JAM_AWAL"]).":00+07:00";
		$end_date = dateToPage($arrParam["TANGGAL_AKHIR"])."T".($arrParam["JAM_AKHIR"]).":00+07:00";
		$arr_email_tujuan = $arrParam["ARR_TUJUAN"];
		
		/*array( 
			array("email" => "rosyidi.alhamdani.ra@gmail.com"),
			array("email" => "shomatz99@gmail.com")
		);*/
		
		$CI->load->library('GoogleClient');
		
		$calendarId = 'notif.teluklamong@gmail.com';
		
		$google = new GoogleClient();
		$client = $google->getClient($code);
		
		$service = new Google_Service_Calendar($client);

		$arrEvent = array(
		  'summary' => $summary,
		  'description' => $description,
		  'start' => array(
			'dateTime' => $start_date  
		  ),
		  'end' => array(
			'dateTime' => $end_date 
		  ),
		);

		// invite email
		if(count($arr_email_tujuan) > 0)
		{
			$arrEvent['attendees'] = $arr_email_tujuan;
			$arrEvent['reminders'] = array(
				'useDefault' => FALSE,
				'overrides' => array(
				  array('method' => 'email', 'minutes' => 1),
				  array('method' => 'popup', 'minutes' => 10),
				),
			);
		}
		
		$event = new Google_Service_Calendar_Event($arrEvent);
		echo(json_encode($arrEvent));
		$service->events->insert($calendarId, $event, ['sendNotifications' => true]);
		
	}
	
	
}