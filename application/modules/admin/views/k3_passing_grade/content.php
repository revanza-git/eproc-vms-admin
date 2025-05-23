<?php echo $this->session->flashdata('msgSuccess')?>
<?php echo $this->session->flashdata('msgError')?>
<div class="btnTopGroup clearfix"> 
	<!-- <a href="<?php echo site_url('admin/admin_k3_passing_grade/tambah')?>" class="btnBlue"><i class="fa fa-plus"></i>Tambah</a> -->
</div>
<div class="tableWrapper">
	<!-- <form method="POST">
		<?php echo $filter_list;?>
	</form> -->
	<div class="filterBtnWp">
		<a href="<?php echo site_url('admin/admin_k3_passing_grade/tambah')?>" class="btnBlue"><i class="fa fa-plus"></i>Tambah</a>
		<button class="editBtn lihatData filterBtn">Filter</button>
	</div>
	<div class="tableHeader">
		
	</div>
	<table class="tableData">
		<thead>
			<tr>
				<td><a href="?<?php echo $this->utility->generateLink('sort','desc')?>&sort=<?php echo ($sort['value'] == 'asc') ? 'desc' : 'asc'; ?>&by=value">Kriteria<i class="fa fa-sort-<?php echo ($sort['value'] == 'asc') ? 'desc' : 'asc'; ?>"></i></a></td>
				<td><a href="?<?php echo $this->utility->generateLink('sort','desc')?>&sort=<?php echo ($sort['start_score'] == 'asc') ? 'desc' : 'asc'; ?>&by=start_score">Skor Tertinggi<i class="fa fa-sort-<?php echo ($sort['start_score'] == 'asc') ? 'desc' : 'asc'; ?>"></i></a></td>
				<td><a href="?<?php echo $this->utility->generateLink('sort','desc')?>&sort=<?php echo ($sort['end_score'] == 'asc') ? 'desc' : 'asc'; ?>&by=end_score">Skor Terendah<i class="fa fa-sort-<?php echo ($sort['end_score'] == 'asc') ? 'desc' : 'asc'; ?>"></i></a></td>
				<td class="actionPanel">Action</td>
			</tr>
		</thead>
		<tbody>
		<?php 
		if(count($passing_grade) > 0){
			foreach($passing_grade as $value){
			?>
				<tr>
					<td><?php echo $value['value']?></td>
					<td><?php echo $value['start_score'];?></td>
					<td><?php echo $value['end_score'];?></td>
					<td class="actionBlock">
						<a href="<?php echo site_url('admin/admin_k3_passing_grade/edit/'.$value['id'])?>" class="editBtn"><i class="fa fa-cog"></i>Edit</a>
						<a style="top: -4px" href="<?php echo site_url('admin/admin_k3_passing_grade/hapus/'.$value['id'])?>" class="delBtn"><i class="fa fa-trash"></i>Hapus</a>
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
<div class="pageNumber">
	<?php echo $pagination ?>
</div>
<div class="filterWrapperOverlay"></div>
<div class="filterWrapper">
	<form method="POST">
		<?php echo $filter_list;?>
	</form>
</div>