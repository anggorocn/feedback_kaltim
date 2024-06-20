<?php
$reqPegawaiId= $this->pegawaiId;
$reqIdOrganisasi = $this->input->get("reqIdOrganisasi"); 
$arrdata= array(
    // array("label"=>"Jenis Jabatan", "field"=> "JENIS_JABATAN", "display"=>"",  "width"=>"")
     // array("label"=>"Kategori Jabatan", "field"=> "KATEGORI_JABATAN", "display"=>"",  "width"=>"")
     array("label"=>"Nama Unor", "field"=> "NAMA_UNOR", "display"=>"",  "width"=>"")
    , array("label"=>"Nama Jabatan", "field"=> "NAMA", "display"=>"",  "width"=>"")
    , array("label"=>"Eselon", "field"=> "NAMA_ESELON", "display"=>"",  "width"=>"")
    , array("label"=>"Bup", "field"=> "BUP", "display"=>"",  "width"=>"")
    , array("label"=>"Kelas Jabatan", "field"=> "KELAS_JABATAN", "display"=>"",  "width"=>"")
    // untuk dua ini kunci, data akhir id, data sebelum akhir untuk order
    , array("label"=>"validasihapusid", "field"=> "TEMP_VALIDASI_HAPUS_ID", "display"=>"1", "width"=>"")
    , array("label"=>"validasiid", "field"=> "TEMP_VALIDASI_ID", "display"=>"1", "width"=>"")
    , array("label"=>"sorderdefault", "field"=> "SORDERDEFAULT", "display"=>"1", "width"=>"")
    , array("label"=>"fieldid", "field"=> "JABATAN_STRUKTURAL_NEW_ID", "display"=>"1", "width"=>"")
);
?>


<div class="d-flex flex-column-fluid">
    <div class="container">

        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Tugas Tambahan</h3>
                </div>
            </div>

            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                <ul class="navi flex-column navi-hover py-2">
                    <li class="navi-item">
                        <a id="btnUbahData" class="navi-link">
                            <span class="navi-icon"><i class="la la-edit"></i></span>
                            <span class="navi-text">Ubah</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <?php
                            foreach($arrdata as $valkey => $valitem) 
                            {
                                echo "<th>".$valitem["label"]."</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

<a href="#" id="triggercari" style="display:none" title="triggercari">triggercari</a>
<script type="text/javascript">
var datanewtable;
var infotableid= "kt_datatable";
var carijenis= "";
var arrdata= <?php echo json_encode($arrdata); ?>;
var indexfieldid= arrdata.length - 1;
var indexvalidasiid= arrdata.length - 3;
// console.log(arrdata);
var indexvalidasihapusid= arrdata.length - 4;
var indexnama= arrdata.length - 8;
var valinfoid = '';
var valinfovalidasiid = '';
var valnamajabatan = '';
var valinfovalidasihapusid = '';

jQuery(document).ready(function() {
    var jsonurl= "json-validasi/personal_json/jsonpegawaijabatantambahan";
    ajaxserverselectsingle.init(infotableid, jsonurl, arrdata);

    var infoid= [];
    $('#'+infotableid+' tbody').on( 'click', 'tr', function () {
        // untuk pilih satu data, kalau untuk multi comman saja
        $('#'+infotableid+' tbody tr').removeClass('selected');

        var el= $(this);
        el.addClass('selected');

        var dataselected= datanewtable.DataTable().row(this).data();
        // console.log(dataselected);
        // console.log(Object.keys(dataselected).length);
        fieldinfoid= arrdata[indexfieldid]["field"]
        fieldvalidasiid= arrdata[indexvalidasiid]["field"]
        fieldvalidasihapusid= arrdata[indexvalidasihapusid]["field"]
        fieldnamajabatan= arrdata[indexnama]["field"]
        
        if (dataselected)
        {
            valinfoid= dataselected[fieldinfoid];
            valinfovalidasiid= dataselected[fieldvalidasiid];
            valinfovalidasihapusid= dataselected[fieldvalidasihapusid];
            valnamajabatan= dataselected[fieldnamajabatan];
            // console.log(valnamajabatan);
        }

        if (valinfovalidasiid == null)
        {
            valinfovalidasiid = '';
        }
    });

  
    $('#'+infotableid+' tbody').on( 'dblclick', 'tr', function () {
      $("#btnUbahData").click();    
    });

    $("#btnUbahData").on("click", function () {
        top.addJabatan(valinfoid, valnamajabatan);
        top.closePopup();
    });

    $("#buttoncaridetil").on("click", function () {
        carijenis= "2";
        calltriggercari();
    });

    $("#triggercari").on("click", function () {

        if(carijenis == "1")
        {
            pencarian= $('#'+infotableid+'_filter input').val();
            datanewtable.DataTable().search( pencarian ).draw();
        }
        else
        {
            
        }
    });

});

function calltriggercari()
{
    $(document).ready( function () {
      $("#triggercari").click();      
    });
}

</script>