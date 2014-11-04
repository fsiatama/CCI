/**
 * A link layout, based on Accordion Layout, to organize links.
 *
 * @author    Bruno Tavares
 * @date      08. May 2008
 * @version   1.0
 * 
 * @licence:This source is open-source. Feel free to edit, but don't forget to share
 */

Ext.namespace('Ext.ux', 'Ext.ux.layout');

/**
 * @class Ext.ux.layout.LinksLayout
 * @extends Ext.layout.Accordion
 * @param {Object} config configuration object
 * @constructor
 */
Ext.ux.layout.LinksLayout = function(config) {
   
	Ext.apply(this, config);
    Ext.ux.layout.LinksLayout.superclass.constructor.call(this);
};

Ext.extend(Ext.ux.layout.LinksLayout,Ext.layout.Accordion,{
	
	/**
	 * @cfg {Integer} activeLink Index of the item that is going to be expanded when the component render (default=-1)
	 */
	activeLink:-1
	
	/**
     * private
     * @param {Ext.Panel} linkItem Item that's going to be rendered in the accordion panel
     * @param (Integer) index Index of the item
     */
	,renderItem : function( linkItem , index ){
		
		//close all the items
		linkItem.collapsed=true;
	
		//if the link doesn’t have any items, it’s a simple link
		if (!linkItem.items){
			
			linkItem.cls='e-linkPanel-simpleLinkPanel';//add css
			
			//add the handler to the underlying Ext.element after the component is rendered
			linkItem.on('render',function(){
				
				this.el.on('click',this.initialConfig.handler || Ext.emptyFn, this, this);
			
			},linkItem);
		
			this.titleCollapse = false; 		//this items don't have
			linkItem.hideCollapseTool = true	//collapse and expand
		
		//otherwise, it's a grouping link
		}else{
			
			//add css for each sub-items
			Ext.each(linkItem.items.items,function(/*item,index,allArray*/){
				
				arguments[0].cls='e-groupLinkPanel-body';
				
				//add the handler to the underlying Ext.element after the component is rendered
				arguments[0].on('render',function(){
					
					this.el.on('click',this.initialConfig.handler || Ext.emptyFn, this, this);
				
				},arguments[0]);
				
			});
		}
		
		//If the item is the activeItem, and the item is a groupping item
		if( index == this.activeLink && linkItem.items )
			linkItem.collapsed = false;  //...expand this item
	
		
		//call super render
		Ext.ux.layout.LinksLayout.superclass.renderItem.call(this,linkItem,arguments[1],arguments[2]);
		
		//activate global attribute 'titleCollapse' again
		this.titleCollapse=true;
	}
});

Ext.Container.LAYOUTS['links'] = Ext.ux.layout.LinksLayout;