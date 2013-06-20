<?php
 // $msc = microtime(true);
  require_once('Mail.php');
  include("../issdb.php");
	$day = date("Y-m-d",strtotime(" -1 day"));
  $start_date = "$day 00:00:00";
  $end_date = "$day 23:59:59";

//header('Content-Type: text/plain; charset=utf-8');
//header('Content-type: text/plain');
//header("Content-Disposition: attachment; filename=ISS_Activity_Count_for_{$day}.csv");

  //create tickets
//  $tik_sql = "SELECT * FROM ticket as t WHERE t.create_time BETWEEN '$start_date' AND '$end_date'";
//  $tik_q = $db->prepare($tik_sql);
//  $tiks = $tik_q->fetchAll();

  //users
  $user_sql = "SELECT id,first_name,last_name FROM users WHERE valid_id=1 AND id<>1 ORDER BY last_name ASC";
  $user_query = $db->prepare($user_sql);
  $user_query->execute();
  $users = $user_query->fetchAll();

  $usr = Array();
  $ulist = Array();
  
  //loop user
  foreach ($users as $us) {
    $usr['id'][] = $us['id'];
    $usr['fname'][] = $us['first_name'];
    $usr['lname'][] = $us['last_name'];
    $ulist[] = $us['id'];

  }

  $uids = implode(",",$ulist);
   //create tickets
   $tik_sql = "SELECT t.id,t.create_by FROM ticket as t WHERE t.create_time BETWEEN '$start_date' AND '$end_date' AND t.create_by IN ($uids)";
   $tik_q = $db->prepare($tik_sql);
   $tik_q->execute();
   $tiks = $tik_q->fetchAll();
   $tic = Array();
   foreach ($tiks as $tik) {
     $tic[$tik['create_by']][] = $tik['id'];

   }

      //closed tickets
      $ctik_sql = "SELECT t.ticket_id,t.create_by,t.create_time FROM ticket_history as t WHERE t.create_time BETWEEN '$start_date' AND '$end_date' AND t.create_by IN (1,$uids) AND t.history_type_id = 27 AND t.state_id IN (2,3,10)";
      $ctik_q = $db->prepare($ctik_sql);
      $ctik_q->execute();
      $ctiks = $ctik_q->fetchAll();
      $ctic = Array();
      $aut = Array();
        foreach ($ctiks as $ctik) {
	  if($ctik['create_by']==1){
	      
		$aut[] = $ctik['ticket_id'];
	  }
          $ctic[$ctik['create_by']][] = $ctik['ticket_id'];
        }

	$auto = implode(",",$aut);
//	print_r($auto);die();
      //closed tickets
      $ptik_sql = "SELECT t.ticket_id,t.create_by FROM ticket_history as t WHERE t.ticket_id IN ($auto) AND t.history_type_id = 27 AND t.state_id IN (7,8) ";
      $ptik_q = $db->prepare($ptik_sql);
      $ptik_q->execute();
      $ptiks = $ptik_q->fetchAll();
      $ao = Array();
      foreach ($ptiks as $ptik) {
        $ao[$ptik['ticket_id']] = $ptik['create_by'];
      }
//var_dump($ao);die();
      foreach ($ao as $keys => $ap) {
        $ctic[$ap][] = $keys;
      }



      //external tickets
      $atik_sql = "SELECT t.ticket_id,t.create_by FROM article as t WHERE t.create_time BETWEEN '$start_date' AND '$end_date' AND t.create_by IN ($uids) AND t.article_type_id = 10";
      $atik_q = $db->prepare($atik_sql);
      $atik_q->execute();
      $atiks = $atik_q->fetchAll();
      $atic = Array();
      foreach ($atiks as $atik) {
        $atic[$atik['create_by']][] = $atik['ticket_id'];
      }
      //external tickets
      $itik_sql = "SELECT t.ticket_id,t.create_by FROM article as t WHERE t.create_time BETWEEN '$start_date' AND '$end_date' AND t.create_by IN ($uids) AND t.article_type_id = 9";
      $itik_q = $db->prepare($itik_sql);
      $itik_q->execute();
      $itiks = $itik_q->fetchAll();
      $itic = Array();
      foreach ($itiks as $itik) {
        $itic[$itik['create_by']][] = $itik['ticket_id'];
      }


//   print_r($ctic);
//die();
  //body loop
  $body = "";
  $create = 0;
  $close = 0;
  $external = 0;
  $internal = 0;
  $sum = 0;
  foreach ($usr['id'] as $key => $uid) {
    $total = 0;
    $tcre = (isset($tic[$uid]))? count($tic[$uid]) : 0;
    $ccre = (isset($ctic[$uid]))? count($ctic[$uid]) : 0;
    $acre = (isset($atic[$uid]))? count($atic[$uid]) : 0;
    $icre = (isset($itic[$uid]))? count($itic[$uid]) : 0;
    $total = $acre + $icre;
    $create += $tcre;
    $close += $ccre;
    $external += $acre;
    $internal += $icre;
    $sum += $total;
    if($total==0 && $tcre==0 && $ccre==0){
    }else{
    $body .= "\"{$usr['lname'][$key]}\",\"{$usr['fname'][$key]}\",\"{$tcre}\",\"{$ccre}\",\"{$acre}\",\"{$icre}\",\"{$total}\"\n";
    }
  }
    $body .= "\"\",\"Total\",\"{$create}\",\"{$close}\",\"{$external}\",\"{$internal}\",\"{$sum}\"\n";

?>
    <?php
//header('Content-Type: text/plain; charset=utf-8');
//header('Content-type: text/plain');
//header("Content-Disposition: attachment; filename=ISS_Activity_Count_for_{$day}.csv");
      $head = "Last Name,First Name,Total Created Ticket,Total Closed Ticket,Total Note External,Total Note Internal,Total Notes\n";
	$body = $head.$body;
	$from = "Imperium Touchpoint <touchpoint@imperium.ph>";
        $subject = "ISS Ticket Activity Count {$day}";

	$host = "ssl://imperium.mail.pairserver.com";
        $port = "465";
        $to = "neil@imperium.ph";
        $username = "outbound@imperium.ph";
        $password = "imperiummail";

        $headers = array ('From' => $from,
          'To' => $to,
          'Subject' => $subject,
            'Content-type' => 'text/csv; charset=utf-8',
            'Content-disposition' => 'attachment; filename="ISS_Activity_Count_for_'.$day.'.csv"',
            'MIME-Version' => 1.0);
        $smtp = Mail::factory('smtp',
          array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password,));

       $mail = $smtp->send($to, $headers, $body);
       echo  "Sending mail to: $to". "<br>";
       if (PEAR::isError($mail)) {
         echo ("<p>" . $mail->getMessage() . "</p>");
        } else {
         echo ("<p>Message successfully sent!</p>");
        }

    ?>



