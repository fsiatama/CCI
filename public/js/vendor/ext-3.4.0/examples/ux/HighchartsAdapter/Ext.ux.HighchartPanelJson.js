/**
* See http://www.extjs.com/forum/showthread.php?t=93669
* 
* The following is an extension of the great Ext.ux.HighchartPanel extension by @buz
* 
* Extension include:
*   Extension to Ext.data.JsonStore to add:
*       predefined fields
*       redefined record definition for adding records to store
*       method findExactBF for finding a record in a filtered store
*       method getCountFull to get count of all records in filtered store
*   Ext.ux.HighChartPanel renamed to Ext.ux.HighchartPanelJson
*       Either pass in a pre-configured store, which will be extended with
*       the required event handlers for adding/removing/updating chart
*   
*   When defining the chart, the groups config object can be added, which
*   will allow for swapping of views of the chart based on groups. When using groups
*   each record added must have the group itemId as the prefix for the initial
*   part of the record id
*
*   EG for group "test0", each record to be displayed in that group should have Id
*   starting in "test0_" like "test0_rec1"
*   
*/
Ext.ns('Ext.ux');

/**
 *  this store adds preconfigured field definition and record type
 *  for use when adding records to store
  */
Ext.ux.ChartStore = Ext.extend(Ext.data.JsonStore, {
    constructor: function(config){

        config = config || {};
        // add fields definition
        config.fields = [
            {name: 'id', type: 'string'}
            ,{name: 'name', type: 'string'}
            ,{name: 'color', type: 'string'}
            ,{name: 'selected', type: 'boolean'}
            ,{name: 'visible', type: 'boolean'}
            ,{name: 'type', type: 'string'}
            ,{name: 'options'} // config object
            ,{name: 'xAxis'} // config object
            ,{name: 'yAxis'} // config object
            ,{name: 'data'} // array
        ];
        // add new record definition
        config.record = new Ext.data.Record.create(['id','name','color','selected','visible','type','options','xAxis','yAxis','data']);
        
        /**
         *  Find method which bypasses filtering
         *  Normal store find methods only search filtered data
         */
        config.findExactBF = function(property, value){
            var ret = null;
            if (this.snapshot) {
                ret = this.snapshot.findIndexBy(function(rec,i){
                        return rec.get(property) === value;
                    }, this);
                if (this.snapshot.items && this.snapshot.items.length > ret) {
                    return this.snapshot.items[ret];
                } else {
                    return ret;
                }
            } else {
                ret = this.data.findIndexBy(function(rec){
                        return rec.get(property) === value;
                    }, this);
                return this.getAt(ret);
            }
        };
        
        /**
         * Get a full count of records, bypassing filter
         */
        config.getCountFull = function() {
            return (this.snapshot) ? this.snapshot.length:this.data.length;
        };
        
        Ext.ux.ChartStore.superclass.constructor.call(this, config);
    }
});//eo Ext.ux.ChartStore



Ext.ux.HighchartPanelJson = Ext.extend(Ext.Panel, {

    //width should be greater then height, 2x works well
    //width: this.width || 0
    //,height: this.height || 0
    /**
     * redraw on resize
     * When using large data sets, it is better to set this to false.
     * If true the Chart will be recreated.
     */
    redrawOnResize: true
    /**
     * Chart object
     */
    ,initComponent: function() {
        // render the chart
        this.on('afterlayout', this.renderChart, this);
        // mask the chart (unmask is done with a defer inside renderChart)
        //this.on('afterlayout', this.showLoading, this);
        // add toogle buttons
        //this.on('afterlayout', this.addToggleButtons, this);
        // set up holder for current group
        if (!this.currGroup) {
            this.currGroup = null;
        }
        // add tbar array if not defined
        if (!this.tbar){
            this.tbar = [];
        }
        // add groups array if not defined
        if (!this.groups){
            this.groups = [];
        }
        // add store if not defined
        if (!this.store) {
            this.store = new Ext.ux.ChartStore({});
        }
        this.store.on('remove',
            /**
             * Remove the series from the chart if it exists
             */
            function(s,r,i) {
                // remove series from graph when removed from store
                if (undefined !== this.chart && this.chart.get(r.get('id')) != null) {
                    this.chart.get(r.get('id')).remove();
                }
            }
            ,this
        );
        this.store.on('add',
            /**
             * Add the series to the chart if it does not exist
             */
            function(s,rows,o) {
                var i, l;
                if (undefined != this.chart) {
                    for (i=0,l=rows.length;i<l;i++) {
                        var r = rows[i], add_line = false, id = r.get('id');
                        if (this.currGroup != null) {
                            if (this.currGroup == id.substring(0,id.indexOf('_'))) {
                                add_line = true;
                            }
                        } else {
                            add_line = true;
                        }
                        // Only add line if group is current or no current group defined
                        if (add_line && this.chart.get(r.get('id')) == null) {
                            var rObj = {};
                            if (r.get('id') != '') {
                                rObj.id = r.get('id');
                            } else {
                                rObj.id = '';
                            }
                            if (r.get('name') != '') {
                                rObj.name = r.get('name');
                            } else {
                                rObj.name = '';
                            }
                            if (r.get('data').length > 0) {
                                rObj.data = r.get('data');
                            } else {
                                rObj = [];
                            }
                            if (r.get('color') != '') {
                                rObj.color = r.get('color');
                            }
                            if (typeof r.get('selected') == 'boolean') {
                                rObj.selected = r.get('selected');
                            }
                            if (typeof r.get('visible') == 'boolean') {
                                rObj.visible = r.get('visible');
                            }
                            if (r.get('type') != '') {
                                rObj.type = r.get('type');
                            }
                            if (typeof r.get('options') == 'object') {
                                rObj.options = r.get('options');
                            }
                            if (typeof r.get('xAxis') == 'object') {
                                rObj.xAxis = r.get('xAxis');
                            }
                            if (typeof r.get('yAxis') == 'object') {
                                rObj.yAxis = r.get('yAxis');
                            }
                            this.chart.addSeries(rObj);
                        }
                    }
                }
            }
            ,this
        );
        this.store.on('clear',
            function(store,recs){
                for (var i=0;i<recs.length;i++) {
                    if (undefined !== this.chart && this.chart.get(recs[i].get('id')) != null) {
                        this.chart.get(recs[i].get('id')).remove();
                    }
                }
            },this
        );
        this.store.on('datachanged',
            function(store) {
                // filter applied, so remove all old lines, and add those in store
                //this.showLoading();
                var rem_cnt = 0, rem_ids = [], add_cnt = 0;
                if (this.chart && this.chart.series && this.chart.series.length > 0) {
                    for (var i=0;i<this.chart.series.length;i++) {
                        // gather existing series into array. Cannot remove
                        // directly as index will change on removal
                        var s = this.chart.series[i];
                        rem_ids.push('s.options.id',s.options.id);
                        // update the store with any changes made to the lines
                        // use custom method which searches 'snapshot' for record
                        // as the store find methods will not find filtered records
                        var rec = store.findExactBF('id',s.options.id);
                        if (s.color !== undefined && s.color != '') {
                            rec.data['color'] = s.color;
                        }
                        if (s.visible  !== undefined) {
                            rec.data['visible'] = (s.visible) ? true:false;
                        }
                        rec.commit();
                    }
                    for (i=0;i<rem_ids.length;i++) {
                        var line = this.chart.get(rem_ids[i]);
                        if (line != null) {
                            line.remove();
                        }
                        rem_cnt++;
                    }
                }
                // now add in all lines from filtered store
                var series = this.getAllSeries();
                if (this.chart) {
                    for (i=0;i<series.length;i++) {
                        this.chart.addSeries(series[i],false);
                        add_cnt++;
                    }
                    this.chart.redraw();
                }
            }
            ,this
        );
        this.store.on('update',
            function(store,record,operation){
                // only update chart on commit
                if (operation == Ext.data.Record.COMMIT && this.chart) {
                    // update series if it exists
                    var series = this.chart.get(record.get('id'));
                    if (series != null) {
                        // update the series Dataset and redraw
                        series.setData(record.get('data'),true);
                    }
                }
            }
            ,this
        );
        /**
         * Load series from store into array for chart constructor
         **/
        this.getAllSeries = function(filter) {
            filter = filter || false;
            var args = {
                series: [],
                currGroup: this.currGroup
            }
            if (this.store.getCountFull() > 0) {
                this.store.each(function(r){
                    var rObj = {};
                    var rec_id = r.get('id');
                    // either unfiltered total list, of filtered to current group
                    if (!filter || (filter && this.currGroup != null && this.currGroup == rec_id.substring(0,rec_id.indexOf('_')))) {
                        //console.log('r',r);
                        if (r.get('id') != '') {
                            rObj.id = r.get('id');
                        } else {
                            rObj.id = '';
                        }
                        if (r.get('name') != '') {
                            rObj.name = r.get('name');
                        } else {
                            rObj.name = '';
                        }
                        if (r.get('data').length > 0) {
                            rObj.data = r.get('data');
                        } else {
                            rObj = [];
                        }
                        if (r.get('color') != '') {
                            rObj.color = r.get('color');
                        }
                        if (typeof r.get('visible') == 'boolean') {
                            rObj.visible = r.get('visible');
                        }
                        this.series.push(rObj);
                    }
                },args);
            }
            //console.log('All series',series);
            return args.series;
        };

        this.showLoading = function() {
            this.getEl().mask('Building graph...','x-mask-loading');
            return null;
        };
        this.hideLoading = function() {
            this.getEl().unmask();
            return null;
        };

        this.getContainerSize = function(parent) {
            //console.log(parent);
            var sz = parent.getSize();
            if (sz.width > 0 && sz.height > 0) {
                return {width:parent.getInnerWidth(),height:parent.getInnerHeight(),box:parent.getBox(),margins:parent.getEl().getMargins()};
            } else {
                return this.getContainerSize(parent.ownerCt)
            }
        };
        Ext.ux.HighchartPanelJson.superclass.initComponent.call(this);
    }// eo initComponent
    ,showLoading: function() {
        this.getEl().mask('Building graph...','x-mask-loading');
        return null;
    }//eo showLoading
    ,hideLoading: function() {
        this.getEl().unmask();
        return null;
    }//eo hideLoading
    ,addToggleButtons: function() {
        if (this.groups.length > 0) {
            // add toggle buttons to the panel header
            var toggleGroupId = Ext.id();
            var tbar = this.getTopToolbar();
            var possDflt = null;
            for (var i =0; i < this.groups.length; i++) {
                if (i==0) {
                    possDflt = this.groups[i].itemId;
                }
                if (typeof this.groups[i] == 'object' && this.groups[i].itemId && tbar.getComponent(this.groups[i].itemId) == null) {
                    if (this.groups[i].isDefault && this.currGroup == null) {
                        this.currGroup = this.groups[i].itemId;
                        this.store.fireEvent('datachanged');
                    }
                    var button = {
                        xtype: 'button'
                        ,text: this.groups[i].text
                        ,iconCls: this.groups[i].iconCls
                        ,tooltip: this.groups[i].tooltip
                        ,itemId: this.groups[i].itemId
                        ,enableToggle: true
                        ,toggleGroup: toggleGroupId
                        ,pressed: ((this.groups[i].isDefault) ? true:false)
                    };
                    button.toggleHandler = function(butt, isPressed) {
                        // @TODO must be a much better way of getting access to these objects...
                        if (this.ownerCt.ownerCt.store.getCountFull() > 0 && isPressed) {
                            this.ownerCt.ownerCt.currGroup = butt.itemId;
                            // filter store by group
                            this.ownerCt.ownerCt.store.filterBy(function(rec,id){
                                var rec_id = rec.get('id');
                                if(butt.itemId != null && butt.itemId == rec_id.substring(0,rec_id.indexOf('_'))) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }, this);
                        }
                    }
                    tbar.add(button);
                }
                else if (typeof this.groups[i] == 'string' && (this.groups[i] == '->' || this.groups[i] == '-')) {
                    // allow for padding and spacers as well
                    tbar.add(this.groups[i]);
                }
            }
            if (this.currGroup == null && possDflt != null) {
                this.currGroup = possDflt;
                tbar.getComponent(possDflt).pressed = true;
                this.store.fireEvent('datachanged');
            }
            tbar.doLayout();
        }
    }
    /**
     * Renders the chart
     */
    ,renderChart: function() {
        var currConfig = this.chartConfig;

        Ext.apply(currConfig.chart, {
            renderTo: this.body.dom
        });

        var parent = this.ownerCt;

        /**
         * Add series which exist in store to the Chart Config Series
         */
        currConfig.series = this.getAllSeries(true);

        /**
         * Set the width and height of the chart based on the parent container
         */
        if (!this.width || !this.height) {
            var owner_dimensions = this.getContainerSize(this.ownerCt);
            if (owner_dimensions) {
                this.setWidth(owner_dimensions.width-(owner_dimensions.margins.left+owner_dimensions.margins.right));
                var tb = this.getTopToolbar(), tbheight = 30;
                if (tb) {
                    tbheight = tb.getHeight();
                }
                var fb = this.getBottomToolbar(), fbheight = 30;
                if (fb) {
                    fbheight = fb.getHeight();
                }
                this.setHeight(owner_dimensions.height-tbheight-fbheight-(owner_dimensions.margins.top+owner_dimensions.margins.bottom));
            }
        }

        /**
         * Check if the chart is created within a different component.
         * If it is within a window, then it will wait for the onResize call
         * after the layout is finished. This is because the window will resize
         * the inner component.
         */
        if (!parent) {
            this.chart = new Highcharts.Chart(currConfig);
            // The chart is with a different component, so we need to take care
        } else {
            if (this.ownerCt.getXType() == 'window') {
                //Direct Owner is a window
                this.chart = new Highcharts.Chart(currConfig);

                var getWindow = function(parent){
                    if (parent.getXType() == 'window') {
                        return parent;
                    } else {
                        return getWindow(parent.ownerCt)
                    }
                }
                getWindow(parent).on('move', function(){
                    this.chart.updatePosition();
                }, this);
                getWindow(parent).on('resize', function(c, w, h){
                    if (this.redrawOnResize)
                        this.redrawChart();
                    this.chart.updatePosition();
                }, this);
            }
            else if (typeof this.chart == 'object'){
                if (this.redrawOnResize)
                    this.redrawChart();
                this.chart.updatePosition();
            } else {
                this.chart = new Highcharts.Chart(currConfig);
                this.ownerCt.on('resize',function(c, w, h){
                    if (this.redrawOnResize)
                        this.redrawChart();
                    this.chart.updatePosition();

                },this);
            }
            this.un('afterlayout', this.renderChart, this);
        }
        if (!this.width) {
            this.width = 600;
        }
        if (!this.width) {
            this.width = 300;
        }
        // Add the group toggle buttons
        this.addToggleButtons();
    }// eo renderChart


    ,redrawChart: function(){
        if (this.redrawOnResize) {
            // get the real dimensions of parent in nested fit layouts
            // and set absolute dimensions of chart
            var owner_dimensions = this.getContainerSize(this.ownerCt);
            if (owner_dimensions) {
                this.setWidth(owner_dimensions.width-(owner_dimensions.margins.left+owner_dimensions.margins.right));
                var tb = this.getTopToolbar(), tbheight = 0;
                if (tb) {
                    tbheight = tb.getHeight();
                }
                var fb = this.getBottomToolbar(), fbheight = -27;
                if (fb) {
                    fbheight = fb.getHeight();
                }
                this.setHeight(owner_dimensions.height-tbheight-fbheight-(owner_dimensions.margins.top+owner_dimensions.margins.bottom));
            }
        }
        // Clear previous chart.
        this.chart.remove();
        delete this.chart;

        // Clear the old series
        delete this.chartConfig.series

        // Set the new series.
        this.chartConfig.series = this.getAllSeries(true);

        // Recreate chart
        this.chart = new Highcharts.Chart(this.chartConfig);
    }
    ,removeChart: function() {
        if (this.chart) {
            this.chart.remove();
            this.chart = null;
        }
    }
    ,destroy: function() {
        if(this.chart) {
            this.chart.remove();
            delete this.chart;
        }
        Ext.ux.HighchartPanelJson.superclass.destroy.call(this);
    }
});

Highcharts.Chart.prototype.remove = function () {

    /**
     * Clear certain attributes from the element
     * @param {Object} d
     */
    function purge(d) {
        var a = d.attributes, i, l, n;
        if (a) {
            l = a.length-1;
            for (i = l; i >= 0; i -= 1) {
                n = a[i].name;
                //if (typeof d[n] !== ‘object’) {
                if (n == 'coords') {
                    //d.parentNode.removeChild(d);
                    d[n] = '0,0,0,0';
                } else if (typeof d[n] != 'object') {
                    d[n] = null;
                }
            }
        }
        a = d.childNodes;
        if (a) {
            l = a.length;
            for (i = 0; i < l; i += 1) {
                purge(d.childNodes[i]);

            }
        }

    }

    // get the container element
    var container = this.imagemap.parentNode;

    // purge potential leaking attributes
    purge(container);

    // remove the HTML
    container.innerHTML = '';
};

Ext.reg('highchartpaneljson', Ext.ux.HighchartPanelJson);