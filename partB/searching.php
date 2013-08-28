<html>

<head>
<title>Search Screen of Winestore</title>
</head>

<?php
	require_once('database.php');
	if(!$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW))
	{
		echo 'Could not connect to mysql on ' . DB_HOST . '\n';
		exit;
	}
	
	//echo 'Connected to mysql <br />'; check whether connect to mysql
	
	if(!mysql_select_db(DB_NAME, $dbconn)) 
	{
		echo 'Could not user database ' . DB_NAME . '\n';
		echo mysql_error() . '\n';
		exit;
	}
	
	//echo 'Connected to database ' . DB_NAME . '\n'; //check whether connect to database
	
	/*query for get the grape varities from database*/
    $query = 'SELECT * FROM grape_variety ORDER BY variety';
    $varieties = mysql_query($query, $dbconn);
	
	/*query for get the regions from database*/
    $query = 'SELECT * FROM region ORDER BY region_name';//Get all regions
    $regions = mysql_query($query, $dbconn);
	
    /*query for get the years from database*/
    $yearArray = array();
    $query = 'SELECT DISTINCT year FROM wine ORDER BY year';
    $years = mysql_query($query, $dbconn);
    $x = 0;
    while($row = mysql_fetch_row($years))
	{
        $yearArray[$x] = $row[0];
        $x++;
    }
?>

<body>

<div>
	<!--Header Here-->
    <div>
        <div><I><h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search Screen of Winestore</h3></I></div>
    </div> 
        <div>
			<!-- GET method for search_result.php -->
            <form action="search_result.php" method="get" id="searchResult" name="searchResult">
            <input type="hidden" id="criteria" name="criteria" />
			
            <table>
				<!-- Table Row 1 -->
                <tr>
                    <td bgcolor="#D8CEF6"><strong>1.&nbsp;Wine Name</strong></td>
                    <td bgcolor="#F2EFFB">
                    <input type="text" name="winename" id="winename" class="txt" /></td>
                </tr>
				<!-- Table Row 2 -->
                <tr>
                    <td><strong>2.&nbsp;Winery Name</strong></td>
                    <td>
                    <input type="text" name="wineryname" id="wineryname" class="txt" /></td>
                </tr>
				<!-- Table Row 3 -->
                <tr>
                    <td bgcolor="#D8CEF6"><strong>3.&nbsp;Region</strong></td>
                    <td bgcolor="#F2EFFB">
                    <select name="region" id="region">
						<option value="0" selected="selected"> Select Region </option>
                        <?php
                            while($row = mysql_fetch_row($regions)) 
							{
                                echo "<option value=\"$row[0]\">$row[1]</option>\n";
                            }
                        ?>
                    </select></td>
                </tr>
				<!-- Table Row 4 -->
                <tr>
                    <td><strong>4.&nbsp;Grape Variety</strong></td>
                    <td>
                    <select name="grapeVariety" id="grapeVariety">
                        <option value="0" selected="selected"> Select Variery </option>
                        <?php
                            while($row = mysql_fetch_row($varieties)) 
							{
                                echo "<option value=\"$row[0]\">$row[1]</option>\n";
                            }
                        ?>
                    </select></td>
                </tr>
				<!-- Table Row 5 -->
                <tr>
                    <td bgcolor="#D8CEF6"><strong>5.&nbsp;Range of Years</strong></td>
                    <td bgcolor="#F2EFFB">
					From
                    <select name="yearFrom" id="yearFrom">
                        <option value="0" selected="selected"> Select Year </option>
                        <?php
                            for($i=0; $i<count($yearArray); $i++) 
							{
                                echo "<option value=\"$yearArray[$i]\">$yearArray[$i]</option>\n";
                            }
                        ?>
                    </select>
                    to
                    <select name="yearTo" id="yearTo">
                        <option value="0" selected="selected"> Select Year </option>
                        <?php
                            for($i=0; $i<count($yearArray); $i++) 
							{
                                echo "<option value=\"$yearArray[$i]\">$yearArray[$i]</option>\n";
                            }
                        ?>
                    </select>
                    </td>
                </tr>
				<!-- Table Row 6 -->
                <tr>
                    <td><strong>6.&nbsp;Min Number in Stock</strong></td>
                    <td><input type="text" name="min_num_instock" id="min_num_instock" class="number" /></td>
                </tr>
				<!-- Table Row 7 -->
                <tr>
                    <td bgcolor="#D8CEF6"><strong>7.&nbsp;Min Number Ordered</strong></td>
                    <td bgcolor="#F2EFFB"><input type="text" name="min_num_ordered" id="min_num_ordered" class="number" /></td>
                </tr>
				<!-- Table Row 8 -->
                <tr>
                    <td><strong>8.&nbsp;Cost Range</strong></td>
                    <td> (MIN)$<input type="text" name="min_cost" id="min_cost" class="number" /> (MAX)$<input type="text" name="max_cost" id="max_cost" class="number" /></td>
                </tr>
				<!-- Table Row 9 "Button" -->
                <tr>
                    <td colspan="2" align="right" bgcolor="#F8E0F7"><input type="submit" name="btnSubmit" id="btnSubmit" value="Search" />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="reset" name="btnRst" id="btnRst" value="Reset form" /></td>
                </tr>
            </table>
            </form>
        </div>       
    </div>
</body>
<?php
    mysql_close($dbconn);
    echo error_get_last();
?>
</html>
