//Load JS example
ScriptMgr.loadJs({
    scripts : 'load1.js',
    debug : false,
    callback : function(){
        console.log('loaded');
    }
});

// Load CSS example
ScriptMgr.loadCss({
     scripts : ['path/to/file1.css', 'path/to/file2.css']
});

// Window load example
Ext.onReady(function(){
    var panel = new Ext.Panel({
        renderTo : document.body,
        title : 'My Panel',
        html: 'Hello World!',
        width : '100%',
        height : 400,
        tbar : [{
            // A toolbar button with open window handler
            text : 'Open Window',
            scope : this,
            handler : function(){
                ScriptMgr.loadJs({
                    scripts : 'LargeWindowComp.js',
                    callback : function(){
                        // The window file is surely loaded now. We can create the window instance
                        var win = new LargeWindowComp({
                            width : 400,
                            height : 300,
                            title : 'I am loaded now'
                        });

                        win.show();
                    }
                });
            }
        }]
    });
});