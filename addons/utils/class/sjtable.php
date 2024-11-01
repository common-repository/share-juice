<?php
class SJTableFormatter
{
	private $table_meta ;
	private $table_class,$table_id;
	private $table_data;
	public
	function __construct($table_meta,$table_data)
	{

		$this->table_meta = $table_meta;
		$this->table_data = $table_data;
	}

	public
	function render_table()
	{
		$col_names_array = array();
		?>
		<table>
		<thead>
			<tr>
				<?php
				foreach($this->table_meta as $column)
				{
					?>
					<th>
						<?php echo $column['header_label'];
						$col_names_array[] = $column['col_name'];
						?>
					</th >
					<?php
				} ?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($this->table_data as $row){
				?>
				<tr>
					<?php

					foreach($row as $key=>$value)
					{
						if(!in_array($key,$col_names_array))
						 continue;
						?>
						<td>
							<?php echo esc_html($value) ?>
						</td>
						<?php
					} ?>
				</tr>
				<?php
			} ?>
		</tbody>
		<tfoot>

		</tfoot>
		</table>
		<?php
	}

}
?>