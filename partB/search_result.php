<html>

<head>
<title>Result Display</title>
</head>

<?php
    $criteria = $_GET["criteria"];//all = no criteria; some = some criterias
    $winename = $_GET["winename"];
    $wineryname = $_GET["wineryname"];
    $region = $_GET["region"];
    $grapeVariety = $_GET["grapeVariety"];
    $yearFrom = $_GET["yearFrom"];
    $yearTo = $_GET["yearTo"];
    $min_num_instock = $_GET['min_num_instock'];
    $min_num_ordered = $_GET['min_num_ordered'];
    $min_cost = $_GET["min_cost"];
    $max_cost = $_GET["max_cost"];
    
    $query = 'SELECT DISTINCT wine.wine_id, wine_name, year, winery_name, region_name, cost, on_hand, SUM(qty) qty, SUM(price)
              FROM wine, winery, region, inventory, items, wine_variety
              WHERE wine.winery_id = winery.winery_id AND
                    winery.region_id = region.region_id AND
                    wine.wine_id = inventory.wine_id AND
                    wine.wine_id = items.wine_id AND
                    wine.wine_id = wine_variety.wine_id';
    
    if($criteria == 'all') 
	{// Query all data
        $query .= ' GROUP BY items.wine_id
                    ORDER BY wine_name, year
                    LIMIT 200';
    }
    //	echo 'here </br>';
    else 
	{// Query part of data
        /*
            Piece together the SQL statement
        */
        if($winename != '') 
		{
            $winename = str_replace("'", "''", $winename);
            $query .= " AND wine.wine_name LIKE '%$winename%'";
        }
        if($wineryname != '') 
		{
            $wineryname = str_replace("'", "''", $wineryname);
            $query .= " AND winery_name LIKE '%$wineryname%'";
        }
        if($region != 1) 
		{
            $query .= " AND region.region_id = $region";
        }
        if($grapeVariety != 0) 
		{
            $query .= " AND variety_id = $grapeVariety";
        }
        if(($yearFrom != 0) && ($yearTo != 0)) 
		{
            $query .= " AND year >= $yearFrom AND year <= $yearTo";
        } else if($yearFrom != 0) 
		{
            $query .= " AND year >= $yearFrom";
        } else if($yearTo != 0) 
		{
            $query .= " AND year <= $yearTo";
        }
        if($min_num_instock != 0) 
		{
            $query .= " AND on_hand >= $min_num_instock";
        }
        if($min_cost != 0) 
		{
            $query .= " AND cost >= $min_cost";
        }
        if($max_cost != 0) 
		{
            $query .= " AND cost <= $max_cost";
        }
        if($min_num_ordered != 0) 
		{
            $query .= " GROUP BY items.wine_id
                        HAVING qty >= $min_num_ordered
                        ORDER BY wine_name, year LIMIT 200";
        }
        else $query .= ' GROUP BY items.wine_id
                         ORDER BY wine_name, year LIMIT 200';
        
    //    echo $query;
    }
    
    require_once('database.php');
	if(!$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW))
	{
		echo 'Could not connect to mysql on ' . DB_HOST . '\n';
		exit;
	}
	//echo 'Connected to mysql <br />';
	
	if(!mysql_select_db(DB_NAME, $dbconn)) 
	{
		echo 'Could not user database ' . DB_NAME . '\n';
		echo mysql_error() . '\n';
		exit;
	}
	//echo 'Connected to database ' . DB_NAME . '\n';
    
    $result = mysql_query($query, $dbconn);
    if(!$result) 
	{
        echo "Wrong query string! [$query]";
        exit;
    }
?>

<body>

<div>
	<div>
		<div><I><h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Result Display</h3></I></div>
		<div><a href="searching.php" title="Back">Back to Search Page</a></div>
		<div>
			<?php
				if(!$result) echo "<div class='noResult'>No records match your search criteria.</div>";
				else 
				{
			?>
			<table>
				<tr>
					<th>Wine Name</th>
					<th>Grape Variety</th>
					<th>Year</th>
					<th>Winery</th>
					<th>Region</th>
					<th>Cost in<br/>Inventory</th>
					<th>Number of<br/>Bottles Available</th>
					<th>Total<br/>Stock Sold</th>
					<th>Total<br/>Sales Revenue</tr>
				<?php
					while($row = mysql_fetch_row($result)) 
					{
				?>
				<tr>
					<td><?php echo $row[1]; ?></td>
					<td style="line-height: 16px">
					<?php
						$query = "SELECT variety FROM wine_variety, grape_variety
								  WHERE wine_variety.wine_id = $row[0] AND
								  wine_variety.variety_id = grape_variety.variety_id
								  ORDER BY variety";
						$varieties = mysql_query($query, $dbconn);
						$str = "";
						while($variety = mysql_fetch_row($varieties)) 
						{
							$str = "$variety[0], ";
						}
					//	$str = substr($str, 0, strlen($str));
						echo substr($str, 0, strlen($str)-2);
					?>
					</td>
					<td><?php echo $row[2]; ?></td>
					<td><?php echo $row[3]; ?></td>
					<td><?php echo $row[4]; ?></td>
					<td><?php echo '$'. $row[5]; ?></td>
					<td><?php echo $row[6]; ?></td>
					<td><?php echo $row[7]; ?></td>
					<td><?php echo '$'. $row[8]; ?></td>
				</tr>
				<?php
					}
				?>
			</table>
			<?php
				}
			?>
		</div>
	</div>
</div>
</body>
<?php
    mysql_close($dbconn);
    echo error_get_last();
?>
</html>
