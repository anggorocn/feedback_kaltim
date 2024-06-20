<style>

.calendar-container {
  height: auto;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.4);
  padding: 20px 20px;
}


.calendar-week {
  display: flex;
  list-style: none;
  align-items: center;
  padding-inline-start: 0px;
}

.calendar-week-day {
  max-width: 57.1px;
  width: 100%;
  text-align: center;
  color: #525659;
}

.calendar-days {
  margin-top: 30px;
  list-style: none;
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
  gap: 5px;
  padding-inline-start: 0px;
}

.calendar-day {
  text-align: center;
  color: #525659;
  padding: 10px;
}

.calendar-month-arrow-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.calendar-month-year-container {
  padding: 10px 10px 20px 10px;
  color: #525659;
  cursor: pointer;
}

.calendar-arrow-container {
  margin-top: -5px;
}

.calendar-left-arrow,
.calendar-right-arrow {
  height: 30px;
  width: 30px;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  color: #525659;
}

.calendar-today-button {
  margin-top: -10px;
  border-radius: 10px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  color: #525659;
  padding: 5px 10px;
}

.calendar-today-button {
  height: 27px;
  margin-right: 10px;
  background-color: #ec7625;
  color: white;
}

.calendar-months,
.calendar-years {
  flex: 1;
  border-radius: 10px;
  height: 30px;
  border: none;
  cursor: pointer;
  outline: none;
  color: #525659;
  font-size: 15px;
}

.calendar-day-active {
  background-color: #ec7625;
  color: white;
  border-radius: 50%;
}

.calendar-alert {
  background-color: red;
  color: white;
  border-radius: 10%;
  cursor: pointer;
}
.calendar-notif{
    font-size: 12px;
  margin-top: -16px;
  color: white;
  border-radius: 30px;
  background-color: green;
  padding: 5px;
  position: absolute;
}
</style>

<?
include_once("functions/personal.func.php");

$this->load->model("base-data/InfoData");
$this->load->model("base-data/Feedback");

$reqPegawaiId= $this->pegawaiId;
$reqSatkerId= $this->input->get('reqSatkerId');

$reqTanggalTes= $this->input->get("reqTanggalTes");
if($reqTanggalTes==''){
    $reqTanggalTes=date("d-m-Y");
}

$tempBulanSekarang= date("m");
$tempTahunSekarang= date("Y");

$tempBulanSekarang= date("m");
$tempSystemTanggalNow= date("d-m-Y");

$index_loop= 0;
// $arrAsesor="";
// $statement= "";

$set= new Feedback();
$set->selectByParamsIdentitasAsesor(array("USER_APP_ID"=>$this->adminuserid),-1,-1);
$set->firstRow();
$reqPegawaiAdminId= $set->getField('PEGAWAI_ID');


$set= new Feedback();
$set->selectByParamsJumlahAsesorPegawai($statement, $reqPegawaiAdminId);
// echo $set->query;exit;
while($set->nextRow())
{
    // $arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
    $arrAsesor[$index_loop]["TANGGAL_TES"]= dateToPageCheck(datetimeToPage($set->getField("TANGGAL_TES"), "date"));
    $arrAsesor[$index_loop]["JUMLAH"]= $set->getField("JUMLAH");
    $tanggalexplode=explode('-',dateToPageCheck(datetimeToPage($set->getField("TANGGAL_TES"), "date")));
    $arrAsesor[$index_loop]["d"]= ltrim($tanggalexplode[0],'0');
    $arrAsesor[$index_loop]["m"]= ltrim($tanggalexplode[1],'0');
    $arrAsesor[$index_loop]["y"]= ltrim($tanggalexplode[2],'0');
    $index_loop++;
}
// print_r($arrAsesor);exit;
$jumlah_asesor= $index_loop;
// echo $tempAsesorNoSk;exit;

$set= new Feedback();
$set->selectByParamsAsesor(array(), -1,-1, " AND b.USER_APP_ID = ".$this->adminuserid);
// echo $set->query;exit;
$set->firstRow();
$tempAsesorNoSk= $set->getField("NO_SK");
$tempAsesorTipeNama= $set->getField("TIPE_NAMA");
// echo $tempAsesorNoSk;exit;

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempAsesorNoSk.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);
//$dateNow= date("d-m-Y");
// print_r($data);exit;
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Card-->
        <div class="card card-custom gutter-b" style="background: rgba(255,255,255,0.9);">
            <div class="card-body">
                <div class="row" style="margin: 0px 20px;">
                    <div class="col-md-1" style="margin-top: 15px;">
                        <img id="reqImagePeserta" style="width: 75px;border-radius:200px ;" />
                    </div>
                    <div class="col-md-11">
                        <hr style="margin-top: 0px; margin-bottom: 15px;border-top: 1px solid black;">
                        <div style="margin:10px 0px"><b>
                            <? if($data['nama']==''){?>
                                <?=$tempAsesorNama?>
                            <? } 
                            else{ ?>
                            <? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?>
                            <?}?>
                            </b>
                            <br>
                            <p style="font-size: 12px;"><?=$tempAsesorNoSk?></p>
                        </div>
                        <hr style="margin-top: 0px; margin-bottom: 10px;border-top: 1px solid black;">
                        <div class="row">
                            <div class="col-md-4" style="margin: 3px 0px;">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                Email : <? if($data['email']==''){?>
                                                <?=$tempAsesorEmail?>
                                                <? } 
                                                else{ ?>
                                                <?=$data['email']?>
                                                <?}?>
                                                    
                            </div>
                            <div class="col-md-8" style="margin: 3px 0px;">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                Telepon :<? if($data['no_hape']==''){?>
                                                    <?=$tempAsesorTelepon?>
                                                <? } 
                                                else{ ?>
                                                <?=$data['no_hape']?>
                                                <?}?>
                            </div>
                            <div class="col-md-4" style="margin: 3px 0px;"> <i class="fa fa-list-alt" aria-hidden="true"></i> Tipe : <?=$tempAsesorTipeNama?></div>
                            <div class="col-md-8" style="margin: 3px 0px;">
                            </div>
                        </div>
                        <hr style="margin-bottom: 0px; margin-top: 10px;border-top: 1px solid black;">
                    </div>
                </div>                    
            </div>
            <br>
            <div class="card-body">
                <div class="row" style="margin: 10px 20px;">
                    <div class="col-md-4">
                        <div class="calendar-container">
                          <div class="calendar-month-arrow-container">
                            <div class="calendar-month-year-container">
                              <select class="calendar-years"></select>
                              <select class="calendar-months">
                              </select>
                            </div>
                            <div class="calendar-month-year">
                            </div>
                            <div class="calendar-arrow-container">
                              <button class="calendar-today-button"></button>
                              <button class="calendar-left-arrow">
                                ← </button>
                              <button class="calendar-right-arrow"> →</button>
                            </div>
                          </div>
                          <ul class="calendar-week">
                          </ul>
                          <ul class="calendar-days">
                          </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="area-table-assesor">
                           <div id="reqTableKegiatan">
                               <table>
                                   <tr>
                                       <td>Tidak Ada</td>
                                   </tr>
                               </table>
                           </div>
                       </div>
                   </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->


<script src="lib/responsive-calendar-0.9/js/responsive-calendar.js"></script>
<link href="lib/responsive-calendar-0.9/css/responsive-calendar.css" rel="stylesheet" type="text/css" />


<script>
    $(function() {
           <?
           if($data['foto_original'] == "")
           {
            ?>
            // $("#reqImagePeserta").attr("src", "../WEB/images/no-picture.jpg");
            <?
        }
        else
        {
            ?>
            $("#reqImagePeserta").attr("src", "<?=$data['foto_original']?>");
            <?
        }
        ?>
    });
    function addLeadingZero(num) {
       if (num < 10) {
           return "0" + num;
       } else {
           return "" + num;
       }
   }

   function setModal(target, link_url)
   {
       var s_url= link_url;
       var request = $.get(s_url);

       request.done(function(msg)
       {
          if(msg == ''){}
              else
              {
                 $('#'+target).html(msg);
             }
         });
    //alert(target+'--'+link_url);
    }
    const weekArray = ["Sen", "Sel", "Rbu", "Kms", "Jmt", "Sbt", "Mng"];
    const monthArray = [
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember"
    ];
    // const current = new Date();
    const current =  '<?=$reqTanggalTes?>';
    currentSplit =  current.split('-');
    // console.log(currentSplit);

    const todaysDate = currentSplit[0];
    const currentYear = currentSplit[2];
    const currentMonth = currentSplit[1];
    // console.log(currentYear);

    // const todaysDate = current.getDate();
    // const currentYear = current.getFullYear();
    // const currentMonth = current.getMonth();

    window.onload = function () {
      const currentDate = new Date();
      generateCalendarDays(currentDate,'start');

      let calendarWeek = document.getElementsByClassName("calendar-week")[0];
      let calendarTodayButton = document.getElementsByClassName(
        "calendar-today-button"
      )[0];
      calendarTodayButton.textContent = `Today ${todaysDate}`;

      calendarTodayButton.addEventListener("click", () => {
        generateCalendarDays(currentDate);
      });

      weekArray.forEach((week) => {
        let li = document.createElement("li");
        li.textContent = week;
        li.classList.add("calendar-week-day");
        calendarWeek.appendChild(li);
      });

      const calendarMonths = document.getElementsByClassName("calendar-months")[0];
      const calendarYears = document.getElementsByClassName("calendar-years")[0];
      const monthYear = document.getElementsByClassName("calendar-month-year")[0];

      // const selectedMonth = parseInt(monthYear.getAttribute("data-month") || 0);
      // const selectedYear = parseInt(monthYear.getAttribute("data-year") || 0);
      const selectedMonth = parseInt(currentMonth)-1;
      const selectedYear = currentYear;
      monthArray.forEach((month, index) => {
        let option = document.createElement("option");
        // console.log(option);
        option.textContent = month;
        option.value = index;
        option.selected = index === selectedMonth;
        calendarMonths.appendChild(option);
      });

      // const currentYearnew = new Date().getFullYear();
      // console.log(currentYearnew);
      const startYear = 2019;
      const endYear = parseInt(selectedYear) + 3;
      let newYear = startYear;
      while (newYear <= endYear) {
        let option = document.createElement("option");
        option.textContent = newYear;
        option.value = newYear;
        if(newYear== selectedYear)
        {
        option.selected = 'selected';
        }
        else{
        option.selected = '';

        }
        calendarYears.appendChild(option);
        newYear++;
      }

      const leftArrow = document.getElementsByClassName("calendar-left-arrow")[0];

      leftArrow.addEventListener("click", () => {
        const monthYear = document.getElementsByClassName("calendar-month-year")[0];
        const month = parseInt(monthYear.getAttribute("data-month") || 0);
        const year = parseInt(monthYear.getAttribute("data-year") || 0);

        let newMonth = month === 0 ? 11 : month - 1;
        let newYear = month === 0 ? year - 1 : year;
        let newDate = new Date(newYear, newMonth, 1);
        generateCalendarDays(newDate);
      });

      const rightArrow = document.getElementsByClassName("calendar-right-arrow")[0];

      rightArrow.addEventListener("click", () => {
        const monthYear = document.getElementsByClassName("calendar-month-year")[0];
        const month = parseInt(monthYear.getAttribute("data-month") || 0);
        const year = parseInt(monthYear.getAttribute("data-year") || 0);
        let newMonth = month + 1;
        newMonth = newMonth === 12 ? 0 : newMonth;
        let newYear = newMonth === 0 ? year + 1 : year;
        let newDate = new Date(newYear, newMonth, 1);
        generateCalendarDays(newDate);
      });

      calendarMonths.addEventListener("change", function () {
        let newDate = new Date(calendarYears.value, calendarMonths.value, 1);
        generateCalendarDays(newDate);
      });

      calendarYears.addEventListener("change", function () {
        let newDate = new Date(calendarYears.value, calendarMonths.value, 1);
        generateCalendarDays(newDate);
      });
    };

    function generateCalendarDays(currentDate,mode='x') {
         if(mode=='x'){
            console.log(currentDate);
            newDate = new Date(currentDate);
            year = newDate.getFullYear();
            month = newDate.getMonth();
        }
        else{
            var current =  '<?=$reqTanggalTes?>';
            currentSplit =  current.split('-');
            year = parseInt(currentSplit[2]);
            month = parseInt(currentSplit[1])-1;
        }
      const totalDaysInMonth = getTotalDaysInAMonth(year, month);
      const firstDayOfWeek = getFirstDayOfWeek(year, month);
      let calendarDays = document.getElementsByClassName("calendar-days")[0];
      
      removeAllChildren(calendarDays);

      let firstDay = 1;
      while (firstDay <= firstDayOfWeek) {
        let li = document.createElement("li");
        li.classList.add("calendar-day");
        calendarDays.appendChild(li);
        firstDay++;
      }

      let day = 1;
      while (day <= totalDaysInMonth) {
        let li = document.createElement("li");
        li.classList.add("calendar-day");
        li.setAttribute("id", day+'-'+month+'-'+year);
        li.textContent = day;
        calendarDays.appendChild(li);
        <?for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++){?>
            if ( day === parseInt(<?=$arrAsesor[$checkbox_index]['d']?>) && month === parseInt(<?=$arrAsesor[$checkbox_index]['m']-1?>) && year === parseInt(<?=$arrAsesor[$checkbox_index]['y']?>)) {
                li.classList.add("calendar-alert");
                document.getElementById("<?=$arrAsesor[$checkbox_index]['d']?>-<?=$arrAsesor[$checkbox_index]['m']-1?>-<?=$arrAsesor[$checkbox_index]['y']?>" ).innerHTML = day+'<span class="calendar-notif"><?=$arrAsesor[$checkbox_index]['JUMLAH']?></span>';
                document.getElementById("<?=$arrAsesor[$checkbox_index]['d']?>-<?=$arrAsesor[$checkbox_index]['m']-1?>-<?=$arrAsesor[$checkbox_index]['y']?>").setAttribute("onclick","showdetil('<?=$arrAsesor[$checkbox_index]['d']?>-<?=$arrAsesor[$checkbox_index]['m']?>-<?=$arrAsesor[$checkbox_index]['y']?>')");
            }
        <?}?>
        day++;
      }

       monthYear = document.getElementsByClassName("calendar-month-year")[0];
      monthYear.setAttribute("data-month", month);
      monthYear.setAttribute("data-year", year);
       calendarMonths = document.getElementsByClassName("calendar-months")[0];
       calendarYears = document.getElementsByClassName("calendar-years")[0];
      calendarMonths.value = month;
      calendarYears.value = year;
    }

    function getTotalDaysInAMonth(year, month) {
      return new Date(year, month + 1, 0).getDate();
    }

    function getFirstDayOfWeek(year, month) {
      return new Date(year, month, 1).getDay();
    }

    function removeAllChildren(parent) {
      while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
      }
    }

    function showdetil(argument) {
        var link_url= 'app/loadUrl/main/home_detil.php?reqTanggalTes='+argument+'&reqMode=<?=$reqMode?>';
        setModal("reqTableKegiatan", link_url);
    }

    function openpopup(val,reqJadwalTesId ) {
        pageUrl= "popup/index/pegawai_formula_data?formulaid=1&reqPegawaiHard="+val+"&jadwaltesid="+reqJadwalTesId;
        window.open(pageUrl, '_blank').focus();
        // openAdd(pageUrl);
    }
</script>