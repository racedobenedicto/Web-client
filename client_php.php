<?php
header('Content-Type: text/html; charset=UTF-8');
function get_content($URL){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $URL);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
}
$color1="#EED6FC";
$color2="#8607CF";
$color3="#D288FD";
$r=0;
echo "<div style=\"position: absolute; top: 50%; left: 50%; width: 600 px; height: auto; margin-top: -200px; margin-left: -300px;background-color: ".$color1.";padding: 50px;\">";
echo "<div style=\"width:300px;height: 50px; background-color:".$color2.";color:#FFFFFF\" align='center'>";
	echo "<br><img src=\"http://www.institutelpalau.net/gestin2/raquel/logo.png\" width=\"20\"/><B>BLUEBERRY</B>";
echo "</div>";
$dins=0;
if(!is_null($_POST['status']))
{
	if($_POST['status']==0)
	{	
		$verify="login2?user=".$_POST["user"]."&pass=".$_POST["pass"];
		$json = json_decode(get_content("http://www.institutelpalau.net/gestin2/raquel/sql_client.php?".$verify));
	}else
	{
		$verify='verify?idStudent='.$_POST["student_id"];
		$json = json_decode(get_content("http://www.institutelpalau.net/gestin2/raquel/sql_client.php?".$verify));
	}
		if($json->state=='success')
		{
			$dins=1;
			$student_id=$json->student_id;
			echo "<div style=\"width:300px;background-color:".$color1.";\" align='center'>";
				echo "<br>";
				echo "Welcome: ".$json->name;
				echo "<form action=\"./client_php.php\" method=\"post\">
					<input type=hidden name=status value=$dins>
					<input type=hidden name=student_id value=$student_id>
					<br>
					<table><td>Query:	<input type=\"text\" name=\"query\" size=15></td>
					<td><input type=\"submit\" value=\"SEND\" ></td></table>
					
				</form> ";
			
			if(!is_null($_POST['query']))
			{
				$query=$_POST['query']."&idStudent=".$_POST['student_id'];
				$json = json_decode(get_content("http://www.institutelpalau.net/gestin2/raquel/sql_client.php?".$query));
				if($json->state=='success')
				{
					echo $_POST['query']."<br>";
					echo "<table border='1'>";
						echo "<thead>";
						echo "<tr style=\"background-color:".$color2."; color:#FFFFFF\">";
						foreach($json->fields as $f)
						{
							echo "<th>".$f."</th>";
						}
						echo "<tr>";
						echo "</thead>";
						echo "<tbody>";
							foreach($json->data as $rows)
							{
								if($r==0)
								{
									$r=1;
									$color=$color1;
								}else{
									$r=0;
									$color=$color3;
								}
								echo "<tr style=\"background-color:".$color.";\">";
									foreach($rows as $valor)
									{
										echo "<td>".$valor."</td>";
									}
								echo "</tr>";
							}
						echo "</tbody>";
					echo "</table><br>";
				}else
				{
					echo "<p style=\"color:red;\" align='center'>Incorrect Query. Try Again!</p>";
				}
			}
			echo "</div>";
			echo "<div style=\"width:300px;height: 60px; background-color:".$color2.";color:#FFFFFF\" align='center'>";
				echo "<form action=\"./client_php.php\" method=\"post\">
					<br>
					<input type=\"submit\" value=\"LOGOUT\" >
				</form> ";
			echo "</div>";
			
		}else
		{
			$textError="User o Password incorrect.<br>Try Again!";
		}
}
if($dins==0)
{
	
	echo "<form action=\"./client_php.php\" method=\"post\">
			
			<div style=\"width:300px;background-color:".$color1.";\" align='center'>
				<br><img src=\"http://www.institutelpalau.net/gestin2/raquel/image1.jpg\" width=\"200\"/>
				<h2 style=\"color:".$color2."; \";>WELCOME!</h2>
				<br>
				<table>
				<tr><td>User:</td><td>	<input type=\"text\" name=\"user\" size=15></td></tr>
				<tr><td>Password:</td><td>	<input type=\"password\" name=\"pass\" size=15></td></tr>
				</table>
				
			</div>
			<BR>
			<p style=\"color:red;\" align='center'>$textError</p>
			<br>
			<div style=\"width:300px;height: 60px; background-color:".$color2.";color:#FFFFFF\" align='center'>
				<br><input type=\"submit\" value=\"LOGIN\" >
			</div>
			<input type=hidden name=status value=$dins>
			
		</form> ";

}
	echo "</div>";
?>