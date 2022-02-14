<h1 class="formHeader">Edit CSMS</h1>
<div class="formDashboard">
	<form method="POST" enctype="multipart/form-data">
		<table>
			<tr class="input-form lampiran_csms">
				<td><label>Nilai CSMS</label></td>
				<td>
					<input type="text" name="score" value="<?php echo (isset($score)?$score:$this->form->get_temp_data('score'))?>">
					<?php echo form_error('score'); ?>
				</td>
			</tr>
		</table>
		
		<div class="buttonRegBox clearfix">
			<input type="submit" value="Simpan" class="btnBlue" name="next">
		</div>
	</form>
</div>