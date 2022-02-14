<div class="logo">
    <div class="logoInner">
        <a href="<?php echo site_url()?>"><img src="<?php echo base_url('assets/images/logo-nr.png');?>"></a>
    </div>
</div>
<div class="backButton">
    <div class="search-bar">
        <div class="search-barInner">
            <input type="text" name="search" class="input" placeholder="Search..">
            <span class="icon">
                <i class="fa fa-search"></i>
            </span>
            <div class="result" style="z-index: 1;">
                <div class="result-list-wrapper">
                    <div class="scroll-wp" style="/*z-index: -1;">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar-item account has-dropdown">

        <img src="<?php echo base_url('assets/images/man-avatar.png')?>" alt="" height="45px">
        
        <p> <?php echo $name?> </p>

        <span class="icon spin"><i class="fa fa-angle-down"></i></span>

        <div class="navbar-dropdown is-dropdown">


            <a href="<?php echo site_url('main/logout')?>" class="navbar-item">

                <span class="icon"><i class="fa fa-power-off"></i></span>

                Logout

            </a>

        </div>

    </div>
    
</div>

<script>
    $(function() {
        $('[name="search"]').on('keyup',function() {
           val = $(this).val();
           
           $.ajax({
                url : '<?php echo site_url('admin/search_bar') ?>/'+val,
                method : 'post',
                beforeSend : function() {
                    // alert('Mohon Ditunggu');
                },
                success : function(xhr) {
                    // alert(xhr);
                    $('.scroll-wp').empty();

                     if ($('[name="search"]').val() == '') {
                        $('.search-bar .result').removeClass('active');
                     } else {
                        $('.search-bar .result').addClass('active');
                     }

                     res = '';

                     xhr_ = JSON.parse(xhr);

                     $.each(xhr_,function(k,v) {
                         res += '<div class="scroll info" data-id="'+v.id+'"><div class="result-content"><div class="result-content-name">Perusahaan</div><span>:</span><div class="result-content-name">'+v.name+'</div></div></div>';
                     });

                     $('.scroll-wp').append(res);

                     $('.scroll.info').on('click',function() {
                        id = $(this).data('id');
                        // alert(id)
                        window.location.href = '<?php echo site_url('approval/administrasi') ?>/'+id; 
                     });
                }
           }); 
        });


    })
</script>