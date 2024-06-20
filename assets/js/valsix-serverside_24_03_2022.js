var ajaxserverselectsingle = function() {
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
            table= datanewtable.DataTable({
                responsive: true
                // , searchDelay: 500
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