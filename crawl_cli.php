<?php
$starttime=microtime(true);

require_once('config.php');
require_once('lang.php');
require_once('ts3_lib/TeamSpeak3.php');

try
{
	$ts3_ServerInstance = TeamSpeak3::factory("serverquery://".$cfg["user"].":".$cfg["pass"]."@".$cfg["host"].":".$cfg["query"]."/");
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$cfg["user"].":".$cfg["pass"]."@".$cfg["host"].":".$cfg["query"]."/?server_port=".$cfg["voice"]);
	
	require_once('mysql_connect.php');
	
	try
	{
		$ts3_VirtualServer->selfUpdate(array('client_nickname'=>$queryname));
	}
	catch(Exception $e)
	{
		try
		{
			$ts3_VirtualServer->selfUpdate(array('client_nickname'=>$queryname2));
		}
		catch(Exception $e)
		{
			echo $lang['error'].$e->getCode().': '.$e->getMessage();
		}
	}

	$todaydate=time();
	$icontime=$todaydate-$warntime;

	$tschanarr=$ts3_VirtualServer->channelList();

	foreach($tschanarr as $channel)
	{
		$tscid[]=$channel['cid'];
	}

	if($deleteicons==1)
	{
		echo $lang['hldelicon'].PHP_EOL;
		$count=0;
		foreach($tschanarr as $channel)
		{
			$channelid=$channel['cid'];
			$checkicon=$ts3_VirtualServer->channelPermList($channelid,$permsid=FALSE);
			foreach($checkicon as $rows)
			{
				if($rows["permvalue"]=="301694691")
				{
					$count=$count+1;
					$ts3_VirtualServer->channelPermRemove($channelid, 142);
				}
			}
		}
		if($count>0)
		{
			echo $count.$lang['icondel'].PHP_EOL.PHP_EOL;
		}
		else
		{
			echo $lang['iconnodel'].PHP_EOL.PHP_EOL;
		}
	}

	echo $lang['hlcrawl'].PHP_EOL.PHP_EOL;
	foreach($tschanarr as $channel)
	{
		$channelid=$channel['cid'];
		$channelname=$channel['channel_name'];
		$channelname=htmlspecialchars($channelname, ENT_QUOTES);
		$userinchannel=$channel['total_clients'];
		$channelpath=$channel->getPathway();
		$channelpath=htmlspecialchars($channelpath, ENT_QUOTES);

		if(!in_array($channelid, $nodelete) && substr_count(strtolower($tschanarr[$channelid]['channel_name']),"spacer") == 0) {
            echo str_pad($channelname, 30 ).' ('.$channelid.') : ';
            
            $cidexists=$mysqlcon->query("SELECT * FROM $table_channel WHERE cid='$channelid'");
            $cidexists=$cidexists->num_rows;

            if($cidexists>0)
            {
                if($userinchannel>0)
                {
                    echo sprintf($lang['cidup'],$userinchannel).PHP_EOL;
                    $mysqlcon->query("UPDATE $table_channel SET lastuse='$todaydate',path='$channelpath' WHERE cid='$channelid'");
                    if($seticon==1)
                    {
                        $checkicon=$ts3_VirtualServer->channelPermList($channelid,$permsid=FALSE);
                        foreach($checkicon as $rows)
                        {
                            if($rows["permvalue"]=="301694691")
                            {
                                $ts3_VirtualServer->channelPermRemove($channelid, 142);
                            }
                        }
                    }
                }
                else
                {
                    $lastusetime=$mysqlcon->query("SELECT lastuse FROM $table_channel WHERE cid='$channelid'");
                    $lastusetime=$lastusetime->fetch_row();
                    $mysqlcon->query("UPDATE $table_channel SET path='$channelpath' WHERE cid='$channelid'");
                    echo $lang['cidnoup'].PHP_EOL;
                    if($seticon==1 && !in_array($channelid, $nodelete) && $lastusetime[0]<$icontime && substr_count(strtolower($tschanarr[$channelid]['channel_name']),"spacer")==0)
                    {
                        $children=$channel->getChildren();
                        if($children=="")
                        {
                            echo $lang['seticon'].PHP_EOL;
                            $ts3_VirtualServer->channelPermAssign($channelid, 142, 301694691);
                        }
                    }
                }	
            }
            else
            {
                echo $lang['record'].PHP_EOL;
                $mysqlcon->query("INSERT INTO $table_channel (cid, lastuse, path) VALUES ('$channelid','$todaydate','$channelpath')");
            }
        }
	}
	echo "\n\n".$lang['hlcleandb'].PHP_EOL.PHP_EOL;
	$count=1;
	$cidexists=$mysqlcon->query("SELECT * FROM $table_channel");
	while($row=$cidexists->fetch_row())
	{
		if(!in_array($row[0], $tscid))
		{
			echo $lang['cid'].$row[0].' : '.$row[2].' - '.$lang['cleandb']."n";
			$count=$count+1;
			if(!$mysqlcon->query("DELETE FROM $table_channel WHERE cid=$row[0]"))
			{
				printf("Errormessage: %s".PHP_EOL, $mysqlcon->error);
			}
		}
	}
	if($count==1)
	{
		echo $lang['nodel2'].PHP_EOL;
	}
        echo "\n\n";

}

catch(Exception $e)
{
	echo "\n\n".$lang['error'].$e->getCode().': '.$e->getMessage().PHP_EOL.PHP_EOL;
}
$buildtime=microtime(true)-$starttime;
echo sprintf($lang['sitegen'],$buildtime).PHP_EOL;
?>
