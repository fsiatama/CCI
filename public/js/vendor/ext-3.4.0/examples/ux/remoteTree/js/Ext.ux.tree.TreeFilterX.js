Ext.ns('Ext.ux.tree');Ext.ux.tree.TreeFilterX=Ext.extend(Ext.tree.TreeFilter,{expandOnFilter:true,filter:function(value,attr,startNode){if(false!==this.expandOnFilter){startNode=startNode||this.tree.root;var animate=this.tree.animate;this.tree.animate=false;startNode.expand(true,false,function(){Ext.ux.tree.TreeFilterX.superclass.filter.call(this,value,attr,startNode);}.createDelegate(this));this.tree.animate=animate;}
else{Ext.ux.tree.TreeFilterX.superclass.filter.apply(this,arguments);}},filterBy:function(fn,scope,startNode){startNode=startNode||this.tree.root;if(this.autoClear){this.clear();}
var af=this.filtered,rv=this.reverse;var f=function(n){if(n===startNode){return true;}
if(af[n.id]){return false;}
var m=fn.call(scope||n,n);if(!m||rv){af[n.id]=n;n.ui.hide();return true;}
else{n.ui.show();var p=n.parentNode;while(p&&p!==this.root){p.ui.show();p=p.parentNode;}
return true;}
return true;};startNode.cascade(f);if(this.remove){for(var id in af){if(typeof id!="function"){var n=af[id];if(n&&n.parentNode){n.parentNode.removeChild(n);}}}}}});