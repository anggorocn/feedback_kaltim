function setundefined(val)
{
    if(typeof val == "undefined" || val == null)
        val= "";
    return val;
}

function getObjectKeyIndex(obj, keyToFind) {
    var i = 0, key;

    for (key in obj) {
        if (key == keyToFind) {
            return i;
        }

        i++;
    }

    return null;
}

var ajaxserverselectsingle = function() {
    var initdynamistable = function(valtableid, valjsonurl, valarrdata) {
        infocolumnsdef= [];
        infocolumns= [];
        infotargets= [];
        valarrdata.forEach(function (item, index) {
            infofield= item["field"];
            infodisplay= item["display"];

            infocolumnsdef.push(infofield);

            setdisplay= true;
            if(infodisplay == "1")
            {
                infotargets.push(index);
                setdisplay= false;
            }

            var infodetil= {};
            infodetil.data= infofield;
            infodetil.visible= setdisplay;

            if(infofield == "AKSI")
            {
                infodetil.render=
                function(data, type, row) {
                    valrow= encodeURIComponent(JSON.stringify(row));

                    infodatavalidasi= setundefined(row['INFO_VALIDASI']);
                    infobutton= infobuttonlihat= infobuttonubah= infobuttonbatal= '';
                    // console.log(infodatavalidasi);

                    if(infodatavalidasi == "")
                    {
                        infobuttonubah= '<a data-val="'+valrow+'" data-toggle="tooltip" data-placement="top" data-original-title="Edit" data-aksi="edit" title="Edit" class="btn btn-xs dt-btn-index btn-primary" style="color:white;">Edit</a>';
                        infobuttonbatal= '<a data-val="'+valrow+'" data-toggle="tooltip" data-placement="top" data-original-title="Batal" data-aksi="hapus" title="Batal" class="btn btn-xs dt-btn-index btn-danger" style="color:white;">Batal</a>';
                    }
                    else if(infodatavalidasi == "-1"){}
                    else
                    {
                        infobuttonlihat= '<a data-val="'+valrow+'" data-toggle="tooltip" data-placement="top" data-original-title="Lihat" data-aksi="lihat" title="Lihat" class="btn btn-xs dt-btn-index btn-info" style="color:white;">Lihat</a>';
                    }

                    infobutton= infobuttonlihat+' '+infobuttonubah+' '+infobuttonbatal;
                    return infobutton;
                };
            }
            else if(infofield == "INFO_STATUS")
            {
                // updatestatus
                infodetil.render=
                function(data, type, row) {
                    infodatavalidasi= setundefined(row['INFO_VALIDASI']);
                    // console.log(infodatavalidasi);
                    return '<span class="updatestatus'+infodatavalidasi+'">' + data + '</span>';
                };
            }


            infocolumns.push(infodetil);
        });
        // console.log(infotargets);
        // console.log(infocolumns);

        var valorderdefault= valarrdata.length - 2;

        datanewtable= $('#'+valtableid);

        datanewtable.DataTable({
            responsive: true
            // , searchDelay: 500
            , iDisplayLength: 25
            , lengthMenu: [[10, 25, 500, -1], [10, 25, 500, "All"]]
            , processing: true
            , serverSide: true
            , order: [[ valorderdefault, "desc" ]]
            , columnDefs: [
                { className: 'never', targets: infotargets }
            ]
            , ajax: 
            {
                url: valjsonurl
                , type: 'POST'
                , data: {columnsDef: infocolumnsdef},
            }
            , columns: infocolumns
            , "fnDrawCallback": function( oSettings ) {
                $('#'+infotableid+'_filter input').unbind();
                $('#'+infotableid+'_filter input').bind('keyup', function(e) {
                    if(e.keyCode == 13) {
                        carijenis= "1";
                        calltriggercari();
                    }
                });
            }
            , "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // console.log(aData);
                // console.log(getObjectKeyIndex(aData, 'AKSI'));
                // console.log(iDisplayIndex);
                // console.log(nRow);
                // console.log(aData[getObjectKeyIndex(aData, 'AKSI')]);

                // if(aData['AKSI'] == null)
                // {
                //     $($(nRow).children()).attr('class', 'new-bg-danger');
                // }

                /*var valueStyle= loopIndex= "";
                valueStyle= nRow % 2;
                loopIndex= 6;
                
                if( aData[7] == '1')
                {
                    $($(nRow).children()).attr('class', 'hukumanstyle');
                }
                else if( aData[7] == '2')
                {
                    $($(nRow).children()).attr('class', 'hukumanpernahstyle');
                }*/
            }

        });
    };

    return {
        init: function(valtableid, valjsonurl, valarrdata) {
            initdynamistable(valtableid, valjsonurl, valarrdata);
        },
    };

}();