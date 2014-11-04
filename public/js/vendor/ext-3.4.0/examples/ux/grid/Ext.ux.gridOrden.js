Ext.ns('Ext.ux');

Ext.ux.gridOrden = Ext.extend(Ext.util.Observable, {
	 position: 'bottom'
	// private
	,field: null
	,items: null
	,direction: 'DESC'
	,init: function(grid){
		this.grid = grid;
		//console.log(this.grid.store.getSortState());
		this.field = this.grid.store.getSortState().field;
		this.direction = this.grid.store.getSortState().direction;
		
		
		var cm = this.grid.colModel;
		this.items=new Array();
		this.items.push({'text':'Default', 'value':'id', 'checked':true});
		Ext.iterate(cm.columns, function(col, index) {
			if((col.sortable)){
				this.items.push({'text':col.header, 'value':col.dataIndex, 'checked':this.field == col.dataIndex ? true : false});
			}
		}, this);
		
		grid.onRender = grid.onRender.createSequence(this.onRender, this);
		//console.log(items);
		
	}
	,onRender: function(){
		var tb = 'bottom' === this.position ? this.grid.bottomToolbar : this.grid.topToolbar;
		tb.addSeparator();
		tb.add({
			 xtype: 'cycle'
			,showText: true
			,prependText: '<b>Ordenar por:</b>  '
			,items:this.items
			,scope: this
			,changeHandler:function(btn, item){
				this.field = item.value;
				this.grid.store.setDefaultSort(this.field, this.direction);
				this.grid.store.reload();
			}
		});
		
		tb.add({
			 xtype: 'cycle'
			,showText: true
			,prependText: '<b>De forma:</b>  '
			,items: [{
				 text: 'Descendente'
				,value: 'DESC'
				,checked: this.direction == 'DESC' ? true : false
			},{
				 text: 'Ascendente'
				,value: 'ASC'
				,checked: this.direction == 'ASC' ? true : false
			}]
			,scope: this
			,changeHandler:function(btn, item){
				this.direction = item.value;
				this.grid.store.setDefaultSort(this.field, this.direction);
				this.grid.store.reload();
			}
		});
	}
});