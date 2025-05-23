<div class="formDashboard">
	<h1 class="formHeader">Edit Izin Usaha</h1>
	<form method="POST" enctype="multipart/form-data">
		<table>
			<?php if($type!='siup'&&$type!='siujk'){ ?>
			<tr class="input-form">
				<td><label><?php if($type=='sbu'){
						?>Anggota Asosasi<?php
					}else{
						?>Lembaga Penerbit<?php 
					}?></label></td>
				<td>
					<input type="text" name="authorize_by" value="<?php echo ($this->form->get_temp_data('authorize_by'))?$this->form->get_temp_data('authorize_by'):$authorize_by;?>">
					<?php echo form_error('authorize_by'); ?>
				</td>
			</tr>
			<?php }
 ?>
			<tr class="input-form">
				<td><label>Nomor*</label></td>
				<td>
					<input type="text" name="no" value="<?php echo ($this->form->get_temp_data('no'))?$this->form->get_temp_data('no'):$no;?>">
					<?php echo form_error('no'); ?>
				</td>
			</tr>
			<?php if($type=='siujk'){?>
			<tr class="input-form">
				<?php 
					$options = array(
					              1=>1,
								  2=>2,
								  3=>3,
								  4=>4,
								  5=>5
					            );

				?>
				<td><label>Grade*</label></td>
				<td>
					<?php echo form_dropdown('grade', $options, $grade);?>
					<?php echo form_error('grade'); ?>
				</td>
			</tr>
			<?php }
     ?>
			<tr class="input-form">
				<td><label>Tanggal</label></td>
				<td>
					<?php echo $this->form->calendar(array('name'=>'issue_date','value'=>($this->form->get_temp_data('issue_date'))?$this->form->get_temp_data('issue_date'):$issue_date), false);?>
					<?php echo form_error('issue_date'); ?>
				</td>
			</tr>
			<?php if($type!='asosiasi'&&$type!='sbu'){?>
			<tr class="input-form">
				<td><label>Kualifikasi</label></td>
				<td>
					<label class="lbform">
						<?php echo form_radio(array('name'=>'qualification'),'kecil',set_radio('qualification','kecil')||((($this->form->get_temp_data('qualification'))?$this->form->get_temp_data('qualification'):$qualification)=='kecil'))?>Kecil
					</label>
					<label class="lbform">
						<?php echo form_radio(array('name'=>'qualification'),'menengah',set_radio('qualification','menengah')||((($this->form->get_temp_data('qualification'))?$this->form->get_temp_data('qualification'):$qualification)=='menengah'))?>Menengah
					</label>
					<label class="lbform">
						<?php echo form_radio(array('name'=>'qualification'),'besar',set_radio('qualification','besar')||((($this->form->get_temp_data('qualification'))?$this->form->get_temp_data('qualification'):$qualification)=='besar'))?>Besar
					</label>
					<?php echo form_error('qualification'); ?>
				</td>
			</tr>
			<?php }
     ?>
			<tr class="input-form">
				<td><label>Masa Berlaku*</label></td>
				<td>
					<?php echo $this->form->lifetime_calendar(array('name'=>'expire_date','value'=> ($this->form->get_temp_data('expire_date'))?$this->form->get_temp_data('expire_date'):$expire_date,'text_str'=>'Selama Perusahaan Berdiri'), false);?>
					<?php echo form_error('expire_date'); ?>
				</td>
			</tr>
			<tr class="input-form">
				<td><label>Lampiran*</label></td>
				<td>
					<?php echo $this->form->file(array('name'=>'izin_file','value'=>($this->form->get_temp_data('izin_file'))?$this->form->get_temp_data('izin_file'):$izin_file));?>
					<?php echo form_error('izin_file'); ?>
				</td>
			</tr>
		</table>
		<div class="buttonRegBox clearfix">
			<input type="submit" value="Simpan" class="btnBlue" name="Update">
		</div>
	</form>
</div>
