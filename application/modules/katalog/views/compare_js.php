<script src="<?php echo base_url('assets/js/highchart/js/highcharts.js')?>"></script>
<script>
    $(function () {

        $('.filterBtn').on('click',function(e){
            var superparent = $(this).closest('.compareHolder');
            $('.compareWrapper',superparent).toggleClass('active');
        });

        var data_kurs = <?php echo $kurs;?>
       $('#container-chart').highcharts({
            plotOptions: {
                series: {
                    connectNulls: true
                }
            },
            title: {
            
                text: 'Riwayat Harga <?php 
                $x=1;
                $sisipan = "";
                foreach($item as $valueitem){ 
                    echo $valueitem["nama"]; 
                    if($x < (count($item) - 1) ){
                        $sisipan = ", "; echo $sisipan;                        
                    }elseif($x < count($item)){
                        $sisipan = " dan "; echo $sisipan;
                    }
                    
                $x++; 
                }?>',
                x: -20 //center
            },
            xAxis: {
                categories: [<?php echo implode(',', $years);?>]
            },
            yAxis: {
                labels: {
                    formatter: function () {
                        if (this.value.toFixed(0) >= 1000000000000) {
                            return this.value.toFixed(0) / 1000000000000 + 'T';
                        } else if (this.value.toFixed(0) >= 1000000000) {
                            return this.value.toFixed(0) / 1000000000 + 'M';
                        } else if (this.value.toFixed(0) >= 1000000) {
                            return this.value.toFixed(0) / 1000000 + 'Jt';
                        } else if(this.value.toFixed(0) >= 1000){
                            return this.value.toFixed(0) / 1000 + 'Rb';
                        }else if(this.value.toFixed(0)<0){
                            return '';
                        }else{
                                return this.value.toFixed(0);
                        }
                    }
                },
                title: {
                    text: ''
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function (point) {
                    var serieI = this.series.index;
                    return this.series.name+' : '+data_kurs[serieI]+ ' '+this.point.y;
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [<?php 
            $h=0; 
            foreach($item as $_item){
                if($h!=0){
                            echo ",";
                        }
            ?>
            {
                name: '<?php echo $_item["nama"]; ?>',
                data: [
                <?php 
                    
                    $i = 0;
                    foreach($years as $year){
                        if($i!=0){
                            echo ",";
                        }
                        
                        $prices = ($price[$_item['id']][$year])?$price[$_item['id']][$year]:'null';
                            echo $prices;
                        $i++;
                    }
                ?>
                ]
            }
            
            <?php 
            $h++;
            }
             ?>

            ]
        });
    });
       
      
    
</script>
