<?
$this->load->model("base-validasi/Agama");

$set= new Agama();
$arragama= [];
$set->selectByParams();
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("AGAMA_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arragama, $arrdata);
}
// print_r($arragama);exit;
?>
<!-- http://192.168.88.100/lamongan/simpeg/main/satker_pilihan_modif.php?reqId=
http://192.168.88.100/lamongan/simpeg/main/pegawai_edit.php?reqId=7484 -->
<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Riwayat</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Riwayat Pangkat / Golongan</a>
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
                    <h3 class="card-label">Anak</h3>
                </div>
            </div>

            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">

	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Agama *</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" id="ktagamaid" name="reqAgamaId">
	        					<option value=""></option>
	        					<?
	        					$reqAgamaId= "1";
	        					foreach($arragama as $item) 
	        					{
	        						$selectvalid= $item["id"];
	        						$selectvaltext= $item["text"];
	        					?>
	        					<option value="<?=$selectvalid?>" <? if($reqAgamaId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
	        					<?
	        					}
	        					?>
	        				</select>
	        			</div>

	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Email *</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" name="reqEmail" placeholder="Enter your email" value="a@a.a" />
	        			</div>

	        		</div>

	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Lahir</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" id="kttanggallahir" name="reqTanggalLahir" readonly="readonly" placeholder="Select date" value="02-05-2021" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>

	        		</div>

	        		<div class="form-group row">

	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Satuan Kerja</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2" id="ktsatuankerja" name="reqSatuanKerjaNama">
	        					<option label="Label">asdasdasd</option>
	        				</select>
	        				<span class="form-text text-muted"><label id="satkerdetil"></label></span>
	        			</div>

	        		</div>


	        	</div>

	        	<div class="card-footer">
	        		<div class="row">
	        			<div class="col-lg-9">
	        				<!-- <button type="button" class="btn btn-primary font-weight-bold mr-2" id="loginButton">Simpan</button> -->
	        				<button type="submit" id="ktloginformsubmitbutton" class="btn btn-primary font-weight-bold mr-2">Simpan</button>
	        			</div>
	        		</div>
	        	</div>

	        </form>

        </div>

    </div>
</div>

<script type="text/javascript">
	$('#ktagamaid').select2({
		placeholder: "Select a state"
	});

	function formatRepo(repo) {
        if (repo.loading) return repo.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
	            "<div class='select2-result-repository__meta'>" +
	            	"<div class='select2-result-repository__title'>" + repo.description + "</div>" +
	            "</div>" +
            "</div>";
        return markup;
    }

    function formatRepoSelection(repo) {
    	$("#satkerdetil").text(repo.description);
        // return repo.description || repo.text;
        return repo.text;
    }

	$("#ktsatuankerja").select2({
        placeholder: "Pilih satuan kerja",
        allowClear: true,
        ajax: {
            url: "json-validasi/combo_json/autocompletesatuankerja",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                    , page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items
                    , pagination: {
                        more: (params.page * 30) < data.total_count && data.items != ""
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });

	arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttanggallahir').datepicker({
		todayHighlight: true
		, autoclose: true
		, orientation: "bottom left"
		, clearBtn: true
		, format: 'dd-mm-yyyy'
		, templates: arrows
	});

	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	jQuery(document).ready(function() {
		var form = KTUtil.getById('ktloginform');
		var formSubmitUrl = "json-validasi/combo_json/tesform";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');

		if (!form) {
			return;
		}

		FormValidation
		    .formValidation(
		        form,
		        {
		        	fields: {
						reqEmail: {
							validators: {
								notEmpty: {
									message: 'Email is required'
								},
								emailAddress: {
									message: 'The value is not a valid email address'
								}
							}
						},
						reqAgamaId: {
							validators: {
								notEmpty: {
									message: 'Please select an option'
								}
							}
						},
						reqTanggalLahir: {
							validators: {
								notEmpty: {
									message: 'Please select an option'
								}
							}
						},
						reqSatuanKerjaNama: {
							validators: {
								notEmpty: {
									message: 'Please select an option'
								}
							}
						},
					},
		            plugins: {
						trigger: new FormValidation.plugins.Trigger(),
						submitButton: new FormValidation.plugins.SubmitButton(),
						bootstrap: new FormValidation.plugins.Bootstrap()
		            }
		        }
		    )
		    .on('core.form.valid', function() {
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

				var formData = new FormData(document.querySelector('form'));

				$.ajax({
					url: formSubmitUrl,
					data: formData,
					processData: false,
					contentType: false,
					type: 'POST',
			        dataType: 'json',
			        success: function (response) {
			        	// console.log(response); return false;
			        	// Swal.fire("Good job!", "You clicked the button!", "success");
			        	Swal.fire({
			                text: response.message,
			                icon: "success",
			                buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light-primary"
							}
			            }).then(function() {
			            	document.location.href = "app/index/tesform";
						});
			        },
			        error: function(xhr, status, error) {
			        	var err = JSON.parse(xhr.responseText);
			        	Swal.fire("Error", err.message, "error");
			        },
			        complete: function () {
			        	KTUtil.btnRelease(formSubmitButton);
			        }
			    });
		    })
			.on('core.form.invalid', function() {
				Swal.fire({
					text: "Sorry, looks like there are some errors detected, please try again.",
					icon: "error",
					buttonsStyling: false,
					confirmButtonText: "Ok, got it!",
					customClass: {
						confirmButton: "btn font-weight-bold btn-light-primary"
					}
				}).then(function() {
					KTUtil.scrollTop();
				});
		    });
	});
</script>