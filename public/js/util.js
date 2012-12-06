dojo.require("dojox.rpc.Rest");

var rht = new Object();

dojo.addOnLoad( function () {
	rht = new RhtModule("user");
	rht.callModule();
});


function loadModule( name ) {
	rht = new RhtModule(name);
	rht.callModule();
}

dojo.declare(
    "RhtModule",
    null,
    {
        id: 0,
    	name: "",
    	list: new Array(),
        
        constructor : function( name ) {
        	this.name = name;
        },
        
        // get html file for module
        callModule : function(){
        	var rht = this;
        	dojo.xhrGet({
        	    url: "../template/" + this.name + ".html",
        	    timeout: 5000,
        	    load: function(response, ioArgs){
        	    	_DEBUG_("xhr get success:" + response);
        	    	dojo.byId("container").innerHTML = response;
        	    	
        	    	rht.generateModuleList();
        	    	
        	        return response; //必须返回response
        	    },
        	    error: function(response, ioArgs){
        	    	_DEBUG_("xhr get failed:" + response);
        	    	return response; //必须返回response
        	    }
        	});
        },
        
        // generate data list
        generateModuleList : function() {
        	var rht = this;
        	var rest = dojox.rpc.Rest("../index.php/" + this.name);
        	rest("").then( function(response) {
        		//clean table content first;
        		dojo.query("#" + rht.name + "_tbody").empty();
        		
        		//generate one row for each item
        		var responseData = eval(response);
        		dojo.forEach(responseData, function(item, i) {
        			//cache the item by index id
        			rht.list[item.id] = item;
        			rht.generateModuleRow(item);
        		});
        	});
        },
        
        // generate row for data item
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
        		cell += '<td class="ibm-rht-action"><a onclick="rht.updateItemHandler('+item['id']+')">Edit</a>';
        		cell += '&#160;&#160;&#160;<a onclick="rht.deleteItemHandler(this,'+item['id']+')">Delete</a></td>';
        	}
        	
        	dojo.place("<tr>" + cell + "</tr>", dojo.byId(this.name + "_tbody"));
        },
        
        // handle create item
        createItemhandler : function() {
        	ibmweb.overlay.show(this.name + '_overlay');
        	dojo.byId(this.name + "_create").style.display = "inline";
        	dojo.byId(this.name + "_update").style.display = "none";
        },
        
        // create item into db
        createModuleItem : function() {
        	var rht = this;
        	var rest = dojox.rpc.Rest("../index.php/" + this.name);
        	
        	rest.post("", dojo.formToJson(this.name + "_form")).then( function() {
        		rht.generateModuleList();
        		
        		ibmweb.overlay.hide('user_overlay');
        	});
        },
        
        // handle update item
        updateItemHandler : function( id ) {
        	ibmweb.overlay.show(this.name + "_overlay");
        	dojo.byId(this.name + "_create").style.display = "none";
        	dojo.byId(this.name + "_update").style.display = "inline";
        	
        	this.id = id;
        	
        	for (var index in CONFIG.dbView[this.name]) {
	        	var keyname = CONFIG.dbView[this.name][index];
        		if(keyname != 'id'){
        			dojo.byId(this.name + '_' + keyname).value = this.list[id][keyname];
        		}
	        }
        },
        
        // update item data in db
        updateModuleItem : function() {
        	var rest = dojox.rpc.Rest("../index.php/" + this.name + "/");
        	
        	rest.put(this.id, dojo.formToJson(this.name + "_form")).then( function() {
        		rht.generateModuleList();
        		
        		ibmweb.overlay.hide('user_overlay');
        	});
        },
        
        
        deleteItemHandler : function( evt, id ) {
        	this.id = id;
        	evt.style.display = "none";
        	
        	var yesNo = '<a class="yesNo" onclick="rht.noToDeleteItem(this)"><b>No..</b></a> '; 
        	yesNo += '<a class="yesNo" onclick="rht.yesToDeleteItem(this,'+id+')"><b>Yes!</b></a> ';        	
        	dojo.query(evt).after(yesNo);
        },
        
        yesToDeleteItem : function( evt, id ) {
        	//dojo.query(evt).parents("tr").forEach(dojo.destroy);
        	
        	rht.deleteModuleItem(id);
        },
        noToDeleteItem : function( evt ) {
        	dojo.query(evt).prev().style("display", "inline");
        	dojo.query(".yesNo").forEach(dojo.destroy);
        },
        
        deleteModuleItem : function( id ) {
        	var rht = this;
        	var rest = dojox.rpc.Rest("../index.php/" + this.name);
        	
        	//rest.delete({"id": id}).then( function() {
        	rest['delete']({"id": id}).then( function() {
        		rht.generateModuleList();
        	});
        },

    }
);










