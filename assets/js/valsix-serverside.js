
var ajaxserverselectsingle = function() {
    var cekreturn = function(checkvaldata) {
        valreturn= true;
        if(checkvaldata == "1")
            valreturn= false;

        return valreturn;
    };
    var initdynamistable = function(valtableid, valjsonurl, valarrdata, valgroup) {
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
            infocolumns.push(infodetil);
        });
        infogroupfield= valarrdata[0]["field"];
        // console.log(valarrdata[0]["field"]);
        // console.log(infocolumns);

        if(typeof datainfopage == "undefined")
        {
            datainfopage= 10;
        }
        // console.log(infopage);

        if(typeof dataTablewarna == "undefined")
        {
            dataTablewarna= "";
        }

        if(typeof tempTanggalTmt == "undefined")
        {
            tempTanggalTmt= "";
        }

        if(typeof aktifwarna == "undefined")
        {
            aktifwarna= "";
        }

        if(typeof datainfoscrollx == "undefined")
        {
            infoscrollx= cekreturn("1");
        }
        else
        {
            infoscrollx= cekreturn(datainfoscrollx);
        }

        if(typeof infoscrolly == "undefined")
        {
            infoscrolly= "";
        }

        // --===================
        if(typeof datainforesponsive == "undefined")
        {
            datainforesponsive= "";
        }
        inforesponsive= cekreturn(datainforesponsive);

        if(typeof datainfostatesave == "undefined")
        {
            // datainfostatesave= "1";
            datainfostatesave= "";
        }
        infostatesave= cekreturn(datainfostatesave);

        if(typeof carijenis == "undefined" || carijenis == "")
        {
            carijenis= "1";
        }

        if(typeof datainfoscrollx == "undefined")
        {
            datainfoscrollx= "";
        }
        infoscrollx= cekreturn(datainfoscrollx);

        if(typeof datapagelength == "undefined")
        {
            pagelength= 25;
        }
        else
        {
            pagelength= datapagelength;
        }
        pagelength= parseFloat(pagelength);

        if(typeof datalengthmenu == "undefined")
        {
            lengthmenu= [[25, 50, -1],[25, 50, 'All'],];
        }
        else
        {
            lengthmenu= datalengthmenu;
        }

        // infoscrolly= cekreturn(datainfoscrolly);
        // console.log(infoscrollx);

        var valorderdefault= valarrdata.length - 2;
        var table; var groupColumn = valorderdefault;
        var collapsedGroups = {};
        datanewtable= $('#'+valtableid);

        if(valgroup == "1")
        {
            table= datanewtable.DataTable({
                responsive: true
                // , searchDelay: 500
                , processing: true
                , serverSide: true
                , rowGroup: {
                    dataSrc: infogroupfield,
                    startRender: function ( rows, group ) {
                        var collapsed = !!collapsedGroups[group];
                        rows.nodes().each(function (r) {
                            r.style.display = collapsed ? 'none' : '';
                        });
         
                        return $('<tr/>')
                            .append('<td colspan="'+valarrdata.length+'">' + group + '</td>')
                            // .append('<td colspan="'+valarrdata.length+'">' + group + ' (' + rows.count() + ')</td>')
                            .attr('data-name', group)
                            .toggleClass('collapsed', collapsed);
                      },
                }
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
                    
                    // $($(nRow).children()).attr('class', 'warnatandamerah');
                }

            });

            $('#'+valtableid+' tbody').on('click', 'tr.dtrg-start', function() {
                var name = $(this).data('name');
                collapsedGroups[name] = !collapsedGroups[name];
                table.draw(false);
            });
        }
        else
        {
            // console.log(infotargets);
            // console.log(infocolumnsdef);
            // console.log(inforesponsive);

            table= datanewtable.DataTable({
                responsive: inforesponsive
                // , searchDelay: 500
                , processing: true
                , serverSide: true
                , "scrollY": infoscrolly+"vh"
                , "scrollX": infoscrollx
                , pageLength: datainfopage
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
                    var valueStyle= loopIndex= maxLoop= "";
                    maxLoop= 11;
                    valueStyle= nRow % 2;
                    loopIndex= 6;
                    
                    if(dataTablewarna != ""){
                        if( tempTanggalTmt == aData[dataTablewarna])
                        {

                            $($(nRow).children()).attr('class', 'alertstyle');
                        }
                    }

                    if(aktifwarna != "")
                    {
                        if(valueStyle == "1") 
                        {
                           $($(nRow).children()).attr('class', 'oddwarna');
                        }
                        else
                        {
                            $($(nRow).children()).attr('class', 'evenWarna');
                        }


                        if( aData[aktifwarna] == '1')
                        {
                            $($(nRow).children()).attr('class', 'usulanWarna');
                        }
                        else if( aData[aktifwarna] == '2')
                        {
                            $($(nRow).children()).attr('class', 'prosesWarna');
                        }
                        else if( aData[aktifwarna] == '3')
                        {
                            $($(nRow).children()).attr('class', 'selesaiWarna');
                        }
                        else if( aData[aktifwarna] == '4')
                        {
                            $($(nRow).children()).attr('class', 'tidakWarna');
                        }
                    }
                }

            });
        }
    };

    return {
        init: function(valtableid, valjsonurl, valarrdata, valgroup) {
            if(typeof valgroup==='undefined' || valgroup===null || valgroup == "") 
            {
                valgroup= "";
            }

            initdynamistable(valtableid, valjsonurl, valarrdata, valgroup);
        },
    };

}();