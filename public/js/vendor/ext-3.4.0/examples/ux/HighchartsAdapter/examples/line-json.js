Ext.onReady(function(){
    // incrementer var for chart line ids
    var globalInc = 1;

    /**
     * return a random Int in range
     * @param   {int}   low     Low range
     * @param   {int}   high    High range
     * @return  {int}
     */
    var randRange = function (low,high) {
        return Math.floor((high-(low-1))*Math.random())+low;
    };//eo randRange

    /**
     * generate a random set of time based
     * data for plotting on chart
     */
    var genData = function(start,cnt,setup) {
        start = start || (new Date()).getTime() - 500;
        setup = setup || true;
        cnt = cnt || 11;
        var data = [], time = start, i;
        for (i = (cnt * -1); i < 0; i++) {
            var x = (setup) ? (time + (i * 1000)):(time + i);
            data.push({
                x: x,
                y: Math.random()
            });
        }
        return data;
    };//eo genData

    /**
     * Add a line to a chart. Either random or specific group
     */
    var addLine = function(grp) {
        grp = grp || null;
        var store = Ext.getCmp('thechart').store;
        var x = randRange(0,1);
        var add = {
            id: ((grp == null) ? 'test'+x: grp)+'_randomdata'+globalInc,
            name: 'Random data - '+globalInc,
            data: genData()
        };
        var rec = new store.record(add);
        store.add([rec]);
        store.commitChanges();
        globalInc++;
    };//eo addLine

    var graphWin = new Ext.Window({
        //renderTo: 'container',
        id: 'graphWin'
        ,title: 'Resizeable Chart Window'
        ,resizeable: true
        ,width: 800
        ,height: 450
        ,tbar: [
            {
                xtype: 'button'
                ,text: 'Add line'
                ,handler: function() {
                    var currGroup = Ext.getCmp('thechart').currGroup;
                    // add a line to visible chart
                    addLine(currGroup);
                }
            }
            ,{
                xtype: 'button'
                ,text: 'Remove line[0]'
                ,handler: function() {
                    var store = Ext.getCmp('thechart').store;
                    // Count in the filtered store
                    if (store.getCount() > 0) {
                        store.removeAt(0);
                        // commit so onremove event is fired to update chart
                        store.commitChanges();
                    }
                }
            }
            ,{
                xtype: 'button'
                ,text: 'Add data to random line'
                ,handler: function() {
                    var store = Ext.getCmp('thechart').store;
                    var cnt = store.getCount();
                    if (cnt > 0) {
                        // get a random line from visible chart
                        var idx = randRange(0,cnt-1);
                        // get a record/line from the visible chart
                        var rec = store.getAt(idx);
                        var old_data = rec.get('data');
                        // generate new data to be appended to old data
                        var add_data = genData(old_data[old_data.length-1].x + 5000, 20, false);
                        //add new data to old data and update record in store
                        rec.set('data',old_data.concat(add_data));
                        // commit changes so onupdate handler can update chart
                        rec.commit();
                    }
                }
            }
        ],
        items: [
            new Ext.ux.HighchartPanelJson({
                titleCollapse: true
                ,layout:'fit'
                ,border: true
                ,id: 'thechart'
                /**
                 * When groups are defined, the toggle buttons will be added to
                 * the chart toolbar area. Each group supports the following
                 * config options.
                 *      itemId: 'unique group id'
                 *      ,isDefault: true/false   (if not used for any group, 1st
                 *                                item will be set as default)
                 *      ,text: 'the text of the toggle button'
                 *      ,iconCls: 'icon css class'
                 *      ,tooltip: 'tooltip text for button'
                 *
                 * 'itemId' is used for identifying group members by prefix.
                 * EG. we have group name 'test0', lines you want as members
                 * of group 'test0' must have id prefixed with 'test0_'
                 */
                ,groups: [
                    {
                        itemId: 'test0'
                        ,text: 'test group 0'
                        ,isDefault: true
                    }
                    ,{
                        itemId: 'test1'
                        ,text: 'test group 1'
                        ,isDefault: false
                    }
                ]
                ,chartConfig: {
                    chart: {
                        id: 'thechart'
                        ,defaultSeriesType: 'line'
                        ,margin: [50, 150, 60, 80]
                    }
                    ,title: {
                        text: 'Random Temperature Data'
                        ,style: {
                            margin: '10px 100px 0 0' // center it
                        }
                    }
                    ,xAxis: {
                        type: 'datetime'
                        ,tickPixelInterval: 150
                        ,title: {
                            text: 'Month'
                        }
                    }
                    ,yAxis: {
                        title: {
                            text: 'Temperature (C)'
                        }
                        ,plotLines: [
                            {
                                value: 0
                                ,width: 1
                                ,color: '#808080'
                            }
                        ]
                        ,min: 0
                    }
                    ,tooltip: {
                        formatter: function() {
                            return '<b>'+ this.series.name +'</b><br/>'+
                                this.x +': '+ this.y +'°C';
                        }
                    }
                    ,legend: {
                        layout: 'vertical',
                        style: {
                            left: 'auto',
                            bottom: 'auto',
                            right: '10px',
                            top: '100px'
                        }
                    }
                }
            })
        ]
    });
    graphWin.show();

    // Add some random data to the graph
    (function(){
        for (var cnt=-10;cnt<0;cnt++) {
            addLine(null);
        }
    }).defer(10,this);
});