<?php
  $Tabs = array(
            0 => array('name' => 'admins',
                       'title'=> 'Администратори',
                       'link' => 'adm_adms.php'),
            1 => array('name' => 'users',
                       'title'=> 'Потребители',
                       'link' => 'adm_usrs.php'),
            2 => array('name' => 'colors',
                       'title'=> 'Цветове',
                       'link' => 'adm_clrs.php'),
            3 => array('name' => 'rooms',
                       'title'=> 'Стаи',
                       'link' => 'adm_rooms.php'),
            4 => array('name' => 'exit',
                       'title'=> 'Изход',
                       'link' => 'adm_exit.php')
  );

  function PrintTabs($Active) {
    global $Tabs;
    print("<tr>\n");
    reset($Tabs);
    while ( list($Index, $Value) = each($Tabs) ) {
      print("<td height=\"20px\" width=\"".(100/sizeof($Tabs))."%\" ");
      if ( $Value['name'] == $Active ) { // active tab
        if ( $Index == 0 )
          print("class=\"tdTabActiveFirst\">");
        else print("class=\"tdTabActive\">");
        print($Value['title']."</td>\n");
      }
      else { // inactive tab
        if ( $Index == count($Tabs)- 1 )
          print("class=\"tdTabInactiveLast\">\n");
        else print("class=\"tdTabInactive\">\n");
        print("<a class=\"aTabInactive\" href=\"".$Value['link']."\">");
        print($Value['title']."</a></td>\n");
      }
    }
    print("</tr>");
  }

  function MakeQueryList($Arr, &$Query) {
    $ArrCount = count($Arr);
    if ( $ArrCount > 1 ) {
      $Query .= " in (";
      reset($Arr);
      while ( list($ArrKey, $ArrVal) = each($Arr) ) {
        if ( $ArrKey < $ArrCount - 1 ) {
          if ( isset($ArrVal) )
            $Query .= $ArrVal.", ";
        }
        else $Query .= $ArrVal.")";
      } // while
    }
    elseif ( $ArrCount == 1 )
      $Query .= "=".$Arr[0];
    else return false;
    return true;
  }
?>