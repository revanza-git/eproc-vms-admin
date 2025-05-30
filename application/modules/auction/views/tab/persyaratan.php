<div id="edit" class="tab procView">
	<?php echo $this->utility->tabNav($tabNav,'persyaratan');?>

	<div class="tableWrapper" style="margin-bottom: 20px">
	<?php echo $this->session->flashdata('msgSuccess')?>
	<?php if(($this->session->userdata('admin')['id_role']==6|7) !== 0){ ?>
	<div class="btnTopGroup clearfix">
		<form method="POST" enctype="multipart/form-data">
			<h2>Persyaratan</h2>
			<table style="width:100%">
				<tr class="input-form">
					<td>
						<textarea id="wysiwyg" style="width:100%; height:350px;" name="description"><?php echo $description;?></textarea>
					</td>
				</tr>
			</table>
			
			<div class="buttonRegBox clearfix">
				<input type="submit" value="Simpan Perubahan" class="btnBlue" name="update">
			</div>
		</form>
	</div>
	</div>
	<?php }
 ?>
</div>


<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js');?>"></script>
<script src="<?php echo base_url('assets/js/tinymce/tinymce.min.js');?>"></script>
<script>
	tinymce.init({
			selector:'textarea',
            fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
			toolbar:  ["undo redo | bold italic | link image | alignleft aligncenter alignright | fontselect | fontsizeselect"],
        style_formats: [
            {title: 'Open Sans', inline: 'span', styles: { 'font-family':'Open Sans'}},
            {title: 'Arial', inline: 'span', styles: { 'font-family':'arial'}},
            {title: 'Book Antiqua', inline: 'span', styles: { 'font-family':'book antiqua'}},
            {title: 'Comic Sans MS', inline: 'span', styles: { 'font-family':'comic sans ms,sans-serif'}},
            {title: 'Courier New', inline: 'span', styles: { 'font-family':'courier new,courier'}},
            {title: 'Georgia', inline: 'span', styles: { 'font-family':'georgia,palatino'}},
            {title: 'Helvetica', inline: 'span', styles: { 'font-family':'helvetica'}},
            {title: 'Impact', inline: 'span', styles: { 'font-family':'impact,chicago'}},
            {title: 'Symbol', inline: 'span', styles: { 'font-family':'symbol'}},
            {title: 'Tahoma', inline: 'span', styles: { 'font-family':'tahoma'}},
            {title: 'Terminal', inline: 'span', styles: { 'font-family':'terminal,monaco'}},
            {title: 'Times New Roman', inline: 'span', styles: { 'font-family':'times new roman,times'}},
            {title: 'Verdana', inline: 'span', styles: { 'font-family':'Verdana'}}
        ],
		}
	);
</script>
