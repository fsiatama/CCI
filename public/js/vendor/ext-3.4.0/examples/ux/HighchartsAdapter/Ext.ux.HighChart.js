Ext.ux.HighChart=Ext.extend(Ext.BoxComponent,{defaultSerieType:null,resizable:true,updateDelay:0,loadMask:false,animShift:false,addSeries:function(series,append){if(append==undefined||append==null)
append=true;var n=new Array(),c=new Array(),cls,serieObject;for(var i=0;i<series.length;i++){var serie=series[i];if(!serie.serieCls){if(serie.type!=null||this.defaultSerieType!=null){cls=Ext.ux.HighChart.Series.get(serie.type!=null?serie.type:this.defaultSerieType)}else{cls=Ext.ux.HighChart.Serie;}
serieObject=new Ext.ux.HighChart.Serie(serie)}else{serieObject=serie;}
c.push(serieObject.config);n.push(serieObject);}
if(this.chart){if(!append){this.removeAllSeries();this.series=n;this.chartConfig.series=c;}else{this.chartConfig.series=this.chartConfig.series?this.chartConfig.series.concat(c):c;this.series=this.series?this.series.concat(n):n;}
for(var i=0;i<c.length;i++){this.chart.addSeries(c[i],true);}
this.refresh();}else{if(append){this.chartConfig.series=this.chartConfig.series?this.chartConfig.series.concat(c):c;this.series=this.series?this.series.concat(n):n;}else{this.chartConfig.series=c;this.series=n;}}},removeSerie:function(id,redraw){redraw=redraw||true;if(this.chart){this.chart.series[id].remove(redraw);this.chartConfig.series.splice(id,1);}
this.series.splice(id,1);},removeAllSeries:function(){var sc=this.series.length;for(var i=0;i<sc;i++){this.removeSerie(0);}},setTitle:function(title){if(this.chartConfig.title)
this.chartConfig.title.text=title;else
this.chartConfig.title={text:title}
if(this.chart&&this.chart.container)
this.draw();},setSubTitle:function(title){if(this.chartConfig.subtitle)
this.chartConfig.subtitle.text=title;else
this.chartConfig.subtitle={text:title}
if(this.chart&&this.chart.container)
this.draw();},initComponent:function(){if(this.store)
this.store=Ext.StoreMgr.lookup(this.store);Ext.ux.HighChart.superclass.initComponent.call(this);},initEvents:function(){if(this.loadMask){this.loadMask=new Ext.LoadMask(this.el,Ext.apply({store:this.store},this.loadMask));}},afterRender:function(){if(this.store)
this.bindStore(this.store,true);Ext.ux.HighChart.superclass.afterRender.call(this);this.bindComponent(true);Ext.applyIf(this.chartConfig.chart,{renderTo:this.el.dom});Ext.applyIf(this.chartConfig,{xAxis:[{}]});if(this.xField&&this.store){this.updatexAxisData();}
if(this.series){this.addSeries(this.series,false);}else
this.series=[];this.initEvents();this.update(500);},onMove:function(){},draw:function(){if(this.chart&&this.rendered){if(this.resizable){for(var i=0;i<this.series.length;i++){this.series[i].visible=this.chart.series[i].visible;}
this.chart.destroy();delete this.chart;this.chart=new Highcharts.Chart(this.chartConfig);}}else if(this.rendered){var initAnim=true;for(var s=0;s<this.series.length;s++){var ctype=this.series[s].type;if(this.chartConfig.plotOptions){var c_anim=(this.chartConfig.plotOptions[ctype]!==undefined&&this.chartConfig.plotOptions[ctype].animation===false)?false:true;if(!c_anim){initAnim=false;break;}}}
if(initAnim)
initAnim=(this.chartConfig.plotOptions&&this.chartConfig.plotOptions.series!==undefined&&this.chartConfig.plotOptions.series.animation===false)?false:true;if(this.store&&initAnim){this.store.load({callback:function(records,option,success){var items=records;for(var i=0;i<this.chartConfig.series.length;i++){this.chartConfig.series[i].data=[];var dataIndex=null;var xField=null;if(this.series[i].dataIndex){dataIndex=this.chartConfig.series[i].dataIndex;xField=this.xField;}else if(this.series[i].yField){dataIndex=this.series[i].yField;xField=this.xField;}else if(this.series[i].type=='pie'&&this.series[i].dataField){dataIndex=this.series[i].dataField;xField=this.series[i].categorieField;}
if(!dataIndex||!xField)
continue;for(var j=0;j<items.length;j++){var x_val=items[j].data[xField];var y_val=items[j].data[dataIndex];this.chartConfig.series[i].data.push([x_val,y_val]);}}
this.chart=new Highcharts.Chart(this.chartConfig);if(this.xField)
this.updatexAxisData();},scope:this});return;}else{this.chart=new Highcharts.Chart(this.chartConfig);}}

for(i=0;i<this.series.length;i++){if(!this.series[i].visible)
this.chart.series[i].hide();}
this.refresh();},onContainerResize:function(){this.draw();},updatexAxisData:function(){var data=[],items=this.store.data.items;if(this.xField&&this.store){for(var i=0;i<items.length;i++){data.push(items[i].data[this.xField])}
if(this.chart)
this.chart.xAxis[0].setCategories(data,true);else
this.chartConfig.xAxis[0].categories=data;}},bindComponent:function(bind){var getWindow=function(parent){if(parent.ownerCt)
return getWindow(parent.ownerCt)
else
return parent;}
var w=getWindow(this);if(bind){w.on('move',this.onMove,this);if(this.ownerCt)
this.ownerCt.on('render',this.update,this);}
else{if(this.ownerCt)
this.ownerCt.un('render',this.update,this);w.un('move',this.onMove,this)}},bindStore:function(store,initial){if(!initial&&this.store){if(store!==this.store&&this.store.autoDestroy){this.store.destroy();}else{this.store.un("datachanged",this.onDataChange,this);this.store.un("load",this.onLoad,this);this.store.un("add",this.onAdd,this);this.store.un("remove",this.onRemove,this);this.store.un("update",this.onUpdate,this);this.store.un("clear",this.onClear,this);}}
if(store){store=Ext.StoreMgr.lookup(store);store.on({scope:this,load:this.onLoad,datachanged:this.onDataChange,add:this.onAdd,remove:this.onRemove,update:this.onUpdate,clear:this.onClear});}
this.store=store;if(store&&!initial){this.refresh();}},refresh:function(){if(this.store&&this.chart){var data=new Array(),seriesCount=this.chart.series.length,i;for(i=0;i<seriesCount;i++)
data.push(new Array());var items=this.store.data.items;var xFieldData=[];for(var x=0;x<items.length;x++){var record=items[x];if(this.xField){xFieldData.push(record.data[this.xField]);}
for(i=0;i<seriesCount;i++){var serie=this.series[i],point;if((serie.type=='pie'&&serie.useTotals)){if(x==0)
serie.clear();point=serie.getData(record,x);}else{point=serie.getData(record,x);data[i].push(point);}}}
var updateAnim=(this.chartConfig.chart.animation===false)?false:(this.chartConfig.chart.animation===undefined?true:this.chartConfig.chart.animation);for(i=0;i<seriesCount;i++){if(this.series[i].useTotals){this.chart.series[i].setData(this.series[i].getTotals())}else if(this.chart.series[i].data.length&&updateAnim&&!this.animShift){var chartSz=this.chart.series[i].data.length;var storeSz=data[i].length;if(chartSz>storeSz){for(x=storeSz-1;x<chartSz;x++){var lastIdx=this.chart.series[i].data.length-1;this.chart.series[i].data[lastIdx].remove(false,updateAnim);}}else if(chartSz<storeSz){for(x=chartSz;x<storeSz;x++)
this.chart.series[i].addPoint([0,0],false,false,updateAnim);}
for(x=0;x<storeSz;x++){this.chart.series[i].data[x].update(this.series[i].getData(items[x]),true,updateAnim);}}else if(this.chart.series[i].data.length&&updateAnim&&this.animShift&&this.series[i].type!='pie'){var ldi=this.chart.series[i].data.length-1;var lastChartData=this.chart.series[i].data[ldi].x;for(var x=items.length-1;x>=0;x--){var x_val=data[i][x].data[this.xField];if(x_val==lastChartData){if(x==items.length-1){return;}
if(x>=0){for(var n=x+1,cnt=0;n<items.length;n++,cnt++){var new_x=data[i][n].data[this.xField];var new_y=data[i][n].y;this.chart.series[i].addPoint([new_x,new_y],false,true,updateAnim);}}
break;}}
if(x<0){this.chart.series[i].setData(data[i],(i==(seriesCount-1)));}}
else{this.chart.series[i].setData(data[i],(i==(seriesCount-1)));}}
if(this.xField){this.updatexAxisData();}}},refreshRow:function(record){var index=this.store.indexOf(record);if(this.chart){for(var i=0;i<this.chart.series.length;i++){var serie=this.chart.series[i];var point=this.series[i].getData(record,index);if(this.series[i].type=='pie'&&this.series[i].useTotals){this.series[i].update(record);this.chart.series[i].setData(this.series[i].getTotals());}else
serie.data[index].update(point);}
if(this.xField){this.updatexAxisData();}}},update:function(delay){var cdelay=delay||this.updateDelay;if(!this.updateTask){this.updateTask=new Ext.util.DelayedTask(this.draw,this);}
this.updateTask.delay(cdelay);},onDataChange:function(){},onClear:function(){this.refresh();},onUpdate:function(ds,record){this.refreshRow(record);},onAdd:function(ds,records,index){var redraw=false,xFieldData=[];for(var i=0;i<records.length;i++){var record=records[i];if(i==records.length-1)redraw=true;if(this.xField){xFieldData.push(record.data[this.xField]);}
for(var x=0;x<this.chart.series.length;x++){var serie=this.chart.series[x],s=this.series[x];var point=s.getData(record,index+i);if(!(s.type=='pie'&&s.useTotals)){serie.addPoint(point,redraw);}}}
if(this.xField){this.chart.xAxis[0].setCategories(xFieldData,true);}},onResize:function(){Ext.ux.HighChart.superclass.onResize.call(this);this.update();},onRemove:function(ds,record,index,isUpdate){for(var i=0;i<this.series.length;i++){var s=this.series[i];if(s.type=='pie'&&s.useTotals){s.removeData(record,index);this.chart.series[i].setData(s.getTotals())}else{this.chart.series[i].data[index].remove(true)}}
Ext.each(this.chart.series,function(serie){serie.data[index].remove(true);})
if(this.xField){this.updatexAxisData();}},onLoad:function(){this.refresh();},destroy:function(){delete this.series;if(this.chart){this.chart.destroy();delete this.chart;}
this.bindStore(null);this.bindComponent(null);Ext.ux.HighChart.superclass.destroy.call(this);}});Ext.reg('highchart',Ext.ux.HighChart);Ext.ux.HighChart.Series=function(){var items=new Array(),values=new Array();return{reg:function(id,cls){items.push(cls);values.push(id);},get:function(id){return items[values.indexOf(id)];}}}();Ext.ux.HighChart.Serie=function(config){config.type=this.type;if(!config.data){config.data=[];}
Ext.apply(this,config);this.config=config;}
Ext.ux.HighChart.Serie.prototype={type:null,xField:null,yField:null,visible:true,clear:Ext.emptyFn,getData:function(record,index){var yField=this.yField||this.dataIndex,xField=this.xField,point={data:record.data,y:record.data[yField]};if(xField)point.x=record.data[xField];return point;},serieCls:true};Ext.ux.HighChart.SplineSerie=Ext.extend(Ext.ux.HighChart.Serie,{type:'spline'});Ext.ux.HighChart.Series.reg('spline',Ext.ux.HighChart.SplineSerie);Ext.ux.HighChart.ColumnSerie=Ext.extend(Ext.ux.HighChart.Serie,{type:'column'});Ext.ux.HighChart.Series.reg('column',Ext.ux.HighChart.ColumnSerie);Ext.ux.HighChart.BarSerie=Ext.extend(Ext.ux.HighChart.Serie,{type:'bar'});Ext.ux.HighChart.Series.reg('bar',Ext.ux.HighChart.BarSerie);Ext.ux.HighChart.LineSerie=Ext.extend(Ext.ux.HighChart.Serie,{type:'line'});Ext.ux.HighChart.Series.reg('line',Ext.ux.HighChart.LineSerie);Ext.ux.HighChart.AreaSerie=Ext.extend(Ext.ux.HighChart.Serie,{type:'area'});Ext.ux.HighChart.Series.reg('area',Ext.ux.HighChart.AreaSerie);Ext.ux.HighChart.AreaSplineSerie=Ext.extend(Ext.ux.HighChart.Serie,{type:'areaspline'});Ext.ux.HighChart.Series.reg('areaspline',Ext.ux.HighChart.AreaSplineSerie);Ext.ux.HighChart.ScatterSerie=Ext.extend(Ext.ux.HighChart.Serie,{type:'scatter'});Ext.ux.HighChart.Series.reg('scatter',Ext.ux.HighChart.ScatterSerie);Ext.ux.HighChart.PieSerie=Ext.extend(Ext.ux.HighChart.Serie,{type:'pie',categorieField:null,dataField:null,useTotals:false,columns:[],constructor:function(config){Ext.ux.HighChart.PieSerie.superclass.constructor.apply(this,arguments);if(this.useTotals){this.columnData={};var length=this.columns.length;for(var i=0;i<length;i++){this.columnData[this.columns[i]]=100/length;}}},addData:function(record){for(var i=0;i<this.columns.length;i++){var c=this.columns[i];this.columnData[c]=this.columnData[c]+record.data[c];}},update:function(record){for(var i=0;i<this.columns.length;i++){var c=this.columns[i];if(record.modified[c])
this.columnData[c]=this.columnData[c]+record.data[c]-record.modified[c];}},removeData:function(record,index){for(var i=0;i<this.columns.length;i++){var c=this.columns[i];this.columnData[c]=this.columnData[c]-record.data[c];}},clear:function(){for(var i=0;i<this.columns.length;i++){var c=this.columns[i];this.columnData[c]=0;}},getData:function(record,index){if(this.useTotals){this.addData(record);return[];}
return[record.data[this.categorieField],record.data[this.dataField]];},getTotals:function(){var a=new Array();for(var i=0;i<this.columns.length;i++){var c=this.columns[i];a.push([c,this.columnData[c]]);}
return a;}});Ext.ux.HighChart.Series.reg('pie',Ext.ux.HighChart.PieSerie);