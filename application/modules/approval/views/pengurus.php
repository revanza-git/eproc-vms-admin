<?php echo $this->session->flashdata('msgSuccess')?>
<?php echo $this->data_process->generate_progress('pengurus',$id_data)?>
<form method="POST">
	<div class="tableWrapper">
	
		<table class="tableData">
			<thead>
				<tr>
					<td>Nama</td>
					<td>Nomor Identitas (KTP/Passport/KITAS)</td>
					<td>Masa Berlaku</td>
					<td>Jabatan</td>
					<td>Masa Berlaku Jabatan</td>
					<td>No. Akta Pengangkatan</td>
					<td>Lampiran</td>
					<td><i class="fa fa-exclamation-triangle" style="color:#f39c12"></i></td>
					<td><i class="fa fa-check" style="color:#27ae60"></i></td>
					<td><i class="fa fa-times" style="color: #c1392b"></i></td>
				</tr>
			</thead>
			<tbody>
			<?php 
			if(count($pengurus_list) > 0){
				foreach($pengurus_list as $penguru_list){
				?>
					<tr>
						<td><?php echo $penguru_list['name'];?></td>
						<td><?php echo $penguru_list['no_identitas'];?></td>
						<td>
							<?php echo ($penguru_list['expire_date']=='lifetime')?'Seumur Hidup': ((strtotime($penguru_list['expire_date']) > 0) ? default_date($penguru_list['expire_date']) : "-");?>
						</td>
						<td><?php echo $penguru_list['position'];?></td>
						<td><?php echo $penguru_list['position_expire'];?></td>
						<td><?php echo $penguru_list['akta_no'];?></td>
						<td><a href="<?php echo BASE_LINK_EXTERNAL.('lampiran/pengurus_file/'.$penguru_list['pengurus_file']);?>" target="_blank"><i class="fa fa-download"></i></a></td>
						<td><input type="checkbox" name="pengurus[<?php echo $penguru_list['id']?>][mandatory]" value="1" <?php echo $this->data_process->set_mandatory($penguru_list['data_status']);?>></td>
						<td class="actionBlock">
							<input type="radio" name="pengurus[<?php echo $penguru_list['id']?>][status]" value="1" <?php echo $this->data_process->set_yes_no(1,$penguru_list['data_status']);?>>
						</td>
						<td class="actionBlock">
							<input type="radio" name="pengurus[<?php echo $penguru_list['id']?>][status]" value="0" <?php echo $this->data_process->set_yes_no(0,$penguru_list['data_status']);?>>
						</td>
					</tr>
				<?php 
				}
			}else{?>
				<tr>
					<td colspan="11" class="noData">Data tidak ada</td>
				</tr>
			<?php }
			?>
			</tbody>
		</table>
		
	</div>

<?php
		$admin = $this->session->userdata('admin');
		if ($admin['id_role'] == 1 || $admin['id_role'] == 10 || $admin['id_role'] == 3) {
		?>
<div class="buttonRegBox clearfix">
	<input type="submit" value="Simpan" class="btnBlue" name="simpan">
</div>
<?php }
  ?>
</form>
