<?php 
$total_columns = count($grid_data[0]);
$total_rows    = count($grid_data);
?>

Grid <?php echo $total_columns; ?>
<div id="grid">
<?php
foreach( $grid_data[0] as $field => $value )
{
?>
<span class="field_header"><?php echo $field ?></span>
<?php 
}
?>

<?php 
for( $r = 0; $r < $total_rows; $r++ )
{
?>
<p>
	<?php 
	foreach( $grid_data[$r] as $field => $value )
	{
	?>
		<span class="field"><?php echo $value ?></span>
	<?php 
	}
	?>
</p>
<?php 
}
echo '<pre>'; print_r( $grid_data); echo '</pre>';
?>