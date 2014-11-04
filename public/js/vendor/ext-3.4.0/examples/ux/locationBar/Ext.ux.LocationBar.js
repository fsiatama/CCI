Ext.ux.LocationBar=Ext.extend(Ext.Toolbar,{maxItems:15,emptyText:'No node selected.',noReload:false,selectHandler:null,reloadHandler:null,locationItems:[],folderIconCls:'x-locationbar-folder-icon',backwardIconCls:'x-locationbar-back-icon',forwardIconCls:'x-locationbar-forward-icon',reloadIconCls:'x-locationbar-reload-icon',tree:null,historyItemNodes:{},historyItems:[],currentItem:false,historyNext:false,initComponent:function()
{if(this.tree)
{this.tree.getSelectionModel().addListener('selectionchange',function(sm,node)
{this.setNode(node);},this)}
Ext.ux.LocationBar.superclass.initComponent.call(this);},autoCreate:{cls:'x-toolbar x-small-editor x-locationbar',html:'<table cellspacing="0"><tr></tr></table>'},onRender:function(ct,position)
{Ext.ux.LocationBar.superclass.onRender.call(this,ct,position);this.repaint();},onClick:function(node)
{if(this.selectHandler)
{this.selectHandler(node);}else
{if(node.parentNode)
{node.ensureVisible();}
node.select();}},onReload:function(node)
{if(this.reloadHandler)
{this.reloadHandler(node);}else if(node.reload)
{node.reload();}},clear:function()
{this.locationItems=[];this.repaint;},setNode:function(node){var path=[];var pNode=node;if(pNode!=null){var i;do{var conf={text:pNode.attributes.text,node:pNode,handler:this.onClick.createDelegate(this,[pNode],false)};if(pNode.childNodes.length){var childs=[];for(i=0;i<pNode.childNodes.length;i++){childs[i]={text:pNode.childNodes[i].attributes.text,node:pNode.childNodes[i],iconCls:this.folderIconCls,handler:this.onClick.createDelegate(this,[pNode.childNodes[i]],false)};}
conf.xtype='tbsplit';conf.menu=childs;}
conf.fullPath=pNode.getPath('text').substr(1);path.unshift(conf);}while(pNode.parentNode&&(pNode=pNode.parentNode)&&pNode.id!='root')
this.locationItems=[];for(i=0;i<path.length;i++){this.addPathItemRaw(path[i]);}
this.currentItem=path[path.length-1];this.addHistoryItemRaw(this.currentItem);}
this.repaint();},addHistoryItemRaw:function(item)
{if(this.historyItems.indexOf(item.text)!=-1)
{this.historyItems.remove(item.text);delete this.historyItemNodes[item.text];}
this.historyItems.push(item.text);this.historyItemNodes[item.text]=item;},addPathItemRaw:function(item)
{if(this.maxItems&&this.locationItems.length>this.maxItems)
{this.locationItems.pop();}
this.locationItems.push(item);},repaint:function()
{if(this.items&&this.items.length)
{var _doLayout=true;this.items.each(function(item)
{this.items.remove(item);item.destroy();},this.items);}else
{var _doLayout=false;}
this.add({cls:'x-btn-icon',iconCls:this.backwardIconCls,handler:function()
{this.historyNext=this.historyItems.pop();var itemKey=this.historyItems.pop();var item=this.historyItemNodes[itemKey];this.onClick(item.node);},scope:this,disabled:this.historyItems.length>1?false:true});this.add({cls:'x-btn-icon',iconCls:this.forwardIconCls,handler:function()
{var node=this.historyNext.node;this.historyNext=false;this.onClick(node);},scope:this,disabled:true});this.add(' ','-',' ');if(this.locationItems.length)
{this.add({cls:'x-btn-icon',iconCls:this.folderIconCls,ctCls:'x-locationbar-location x-locationbar-location-first',disabled:true});var text;for(var i=0;i<this.locationItems.length;i++)
{var locationItem=this.locationItems[i];var item={};if(typeof locationItem=='object')
{item=locationItem;}else
{item.text=locationItem;}
if(!item.text)
{item.text='n/a';}
item.handler=this.onClick.createDelegate(this,[locationItem.node],false);item.ctCls='x-locationbar-location';this.add(item);}
this.addItem({xtype:'tbfill'});menu=[];for(var i=this.historyItems.length-2;i>=0;i--)
{menu.push({text:this.historyItemNodes[this.historyItems[i]].fullPath,iconCls:this.folderIconCls,node:this.historyItemNodes[this.historyItems[i]].node,handler:function(item)
{this.onClick(item.node);},scope:this});}
this.add({cls:'x-btn-icon',ctCls:'x-locationbar-location x-locationbar-location-last',menuAlign:'tr-br?',menu:menu});if(!this.noReload)
{this.add(' ');this.add({cls:'x-btn-icon',iconCls:this.reloadIconCls,handler:function()
{this.onReload(this.currentItem.node);},scope:this});}
this.add(' ');}else
{this.add({cls:'x-btn-icon',iconCls:this.folderIconCls,ctCls:'x-locationbar-location x-locationbar-location-first',disabled:true});if(this.emptyText)
{this.add({xtype:'lbtext',text:this.emptyText});}
this.addItem(new Ext.ux.LocationBar.Fill());this.add({cls:'x-btn-icon',ctCls:'x-locationbar-location x-locationbar-location-last',menuAlign:'tr-br?',disabled:true});this.add(' ');this.add({cls:'x-btn-icon',iconCls:this.reloadIconCls,disabled:true});this.add(' ');}
if(_doLayout===true)
{this.doLayout();}}});Ext.reg('locationbar',Ext.ux.LocationBar);Ext.ux.Fill=Ext.extend(Ext.Toolbar.Spacer,{render:function(td)
{td.style.width='100%';Ext.fly(td).addClass('x-locationbar-location');Ext.ux.Fill.superclass.render.call(this,td);}});Ext.reg('tbfill',Ext.ux.Fill);Ext.ux.LocationBar.Fill=Ext.extend(Ext.Toolbar.Fill,{render:function(td)
{td.className='x-locationbar-location';var data=document.createTextNode('\u00a0');this.el.appendChild(data);Ext.ux.LocationBar.Fill.superclass.render.call(this,td);}});Ext.reg('lbfill',Ext.ux.LocationBar.Fill);Ext.ux.LocationBar.TextItem=Ext.extend(Ext.Toolbar.TextItem,{render:function(td)
{td.className='x-locationbar-location';Ext.ux.LocationBar.Fill.superclass.render.call(this,td);}});Ext.reg('lbtext',Ext.ux.LocationBar.TextItem);