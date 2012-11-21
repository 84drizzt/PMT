dojo.require("dojox.rpc.Rest");

dojo.addOnLoad( function () {
	var rht = new RhtModule("user");
	rht.callModule();
});


dojo.declare(
    "RhtModule",
    null,
    {
        name: "",
        info: { name : "",age:""},
        staticValue:{count:0},
        
        constructor : function( name ) {
        	this.name = name;
        	
        	dojo.connect( dojo.connect(dojo.byId("test"),"onmouseover",function(evt){)
        },
        
        bind
        
        callModule : function(){
        	var rht = this;
        	dojo.xhrGet({
        	    url: "../template/" + this.name + ".html",
        	    timeout: 5000,
        	    load: function(response, ioArgs){
        	    	//_DEBUG_("xhr get success:" + response);
        	    	dojo.byId("container").innerHTML = response;
        	    	
        	    	rht.generateModuleList();
        	    	
        	        return response; //必须返回response
        	    },
        	    error: function(response, ioArgs){
        	    	_DEBUG_("xhr get failed:" + response);
        	    	return response; //必须返回response
        	    }
        	})
        },
        
        generateModuleList : function() {
        	var rht = this;
        	var rest = dojox.rpc.Rest("../index.php/" + this.name);
        	rest("").then( function(response) {
        		var responseData = eval(response);
        		 dojo.forEach(responseData, function(item) {
        			 _DEBUG_(item);
        			 rht.generateModuleRow(item);
        		 });
        	});
        },
        
        generateModuleRow : function( item ) {
        	var cell = "";
        	for (var index in CONFIG.dbView[this.name]) {
        		var keyname = CONFIG.dbView[this.name][index];
        		if(keyname == 'id'){
        			cell += '<td class="ibm-rht-hide">'+item[keyname]+'</td>';
        		}else{
        			cell += '<td>'+item[keyname]+'</td>';
        		}
        	}
        	if (cell.length > 0){
        		cell += '<td class="ibm-rht-action"><a onclick="editModuleItem(\''+this.name+'\', '+item['id']+')">Edit</a> ';
        		cell += '<a onclick="deleteModuleItem('+item['id']+')">Delete</a></td>';
        	}
        	dojo.place("<tr>" + cell + "</tr>", dojo.byId("tbody_" + this.name));
        },
        
        createModuleItem : function() {
        	var rest = dojox.rpc.Rest("../index.php/" + this.name);
        	
        	rest.post("", dojo.formToJson("form_" + this.name)).then( function() {
        		this.generateModuleList();
        	});
        },
        
        editModuleItem : function( id ) {
        	ibmweb.overlay.show('overlay_'+this.name);
        	
        	var rest = dojox.rpc.Rest("../index.php/" + this.name);
        	
        	rest("/" + id).then( function(response) {
        		var responseData = eval(response);
        		 dojo.forEach(responseData, function(item) {
        			 this.generateModuleRow(item);
        		 }, this);
        	});
        }

    }
);










