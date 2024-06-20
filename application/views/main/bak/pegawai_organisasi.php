<?php
$reqPegawaiId= $this->pegawaiId;
$reqIdOrganisasi = $this->input->get("reqIdOrganisasi"); 
$arrdata= array(
    array("label"=>"Nama Organisasi", "field"=> "NAMA", "display"=>"",  "width"=>"")
    , array("label"=>"Jabatan", "field"=> "JABATAN", "display"=>"",  "width"=>"")
    , array("label"=>"Awal", "field"=> "TANGGAL_AWAL", "display"=>"",  "width"=>"")
    , array("label"=>"Akhir", "field"=> "TANGGAL_AKHIR", "display"=>"",  "width"=>"")
    , array("label"=>"Pimpinan", "field"=> "PIMPINAN", "display"=>"",  "width"=>"")
    , array("label"=>"Tempat", "field"=> "TEMPAT", "display"=>"",  "width"=>"")
    // untuk dua ini kunci, data akhir id, data sebelum akhir untuk order
    , array("label"=>"validasihapusid", "field"=> "TEMP_VALIDASI_HAPUS_ID", "display"=>"1", "width"=>"")
    , array("label"=>"validasiid", "field"=> "TEMP_VALIDASI_ID", "display"=>"1", "width"=>"")
    , array("label"=>"sorderdefault", "field"=> "SORDERDEFAULT", "display"=>"1", "width"=>"")
    , array("label"=>"fieldid", "field"=> "ORGANISASI_RIWAYAT_ID", "display"=>"1", "width"=>"")
);
?>
<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted">Data Riwayat</a>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted">Organisasi</a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container">

        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Organisasi</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline mr-2">
                        <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="svg-icon svg-icon-md"></span>Aksi
                        </button>

                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <ul class="navi flex-column navi-hover py-2">
                                <li class="navi-item">
                                    <a id="btnAdd" class="navi-link">
                                        <span class="navi-icon"><i class="la la-plus"></i></span>
                                        <span class="navi-text">Tambah</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a id="btnUbahData" class="navi-link">
                                        <span class="navi-icon"><i class="la la-edit"></i></span>
                                        <span class="navi-text">Ubah</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a id="btnDelete" class="navi-link">
                                        <span class="navi-icon"><i class="la la-trash"></i></span>
                                        <span class="navi-text">Hapus</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
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
var indexvalidasihapusid= arrdata.length - 4;
var valinfoid = '';
var valinfovalidasiid = '';
var valinfovalidasihapusid = '';

jQuery(document).ready(function() {
    var jsonurl= "json-validasi/personal_json/jsonpegawaiorganisasi";
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
        valinfoid= dataselected[fieldinfoid];
        valinfovalidasiid= dataselected[fieldvalidasiid];
        valinfovalidasihapusid= dataselected[fieldvalidasihapusid];
        if (valinfovalidasiid == null)
        {
            valinfovalidasiid = '';
        }
    });

    $("#btnAdd").on("click", function () {
       var url = "app/index/pegawai_organisasi_add?reqPegawaiId=<?=$reqPegawaiId?>";
       window.location.href = url;
    });

    $('#btnDelete').on('click', function (e) {
        if(valinfoid == "")
        {
            Swal.fire({
                text: "Pilih salah satu data terlebih dahulu.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                }
            });
        }
        else
        {
            urlAjax= "json-validasi/personal_json/jsonpegawaiorganisasidelete?&reqDetilId="+valinfovalidasiid;
            swal.fire({
                title: 'Apakah anda yakin untuk hapus data?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then(function(result) { 
                if (result.value) {
                    $.ajax({
                        url : urlAjax,
                        type : 'DELETE',
                        dataType:'json',
                        beforeSend: function() {
                            swal.fire({
                                title: 'Please Wait..!',
                                text: 'Is working..',
                                onOpen: function() {
                                    swal.showLoading()
                                }
                            })
                        },
                        success : function(data) { 
                            swal.fire({
                                position: 'top-right',
                                icon: "success",
                                type: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function() {
                                document.location.href = "app/index/pegawai_organisasi";
                            });
                        },
                        complete: function() {
                            swal.hideLoading();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            swal.hideLoading();
                            var err = JSON.parse(jqXHR.responseText);
                            Swal.fire("Error", err.message, "error");
                        }
                    });
                }
            });
        }
    });

    $('#'+infotableid+' tbody').on( 'dblclick', 'tr', function () {
      $("#btnUbahData").click();    
    });

    $("#btnUbahData").on("click", function () {
        if(valinfoid == "")
        {
            Swal.fire({
                text: "Pilih salah satu data terlebih dahulu.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                }
            });
        }
        else
        {
           var url = "app/index/pegawai_organisasi_add?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=edit&reqDetilId="+valinfovalidasiid+"&reqRowId="+valinfoid;
           window.location.href = url;
        }
       // console.log(valinfoid);
    });

    $("#buttoncaridetil").on("click", function () {
        carijenis= "2";
        calltriggercari();
    });

    $("#triggercari").on("click", function () {
        // kt_tree_6= $("#kt_tree_6").jstree().get_selected("id")[0];
        // console.log(kt_tree_6);return false;

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