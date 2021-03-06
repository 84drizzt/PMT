dojo.require("dojox.rpc.Rest");
dojo.require("dijit.form.DateTextBox");

var rht = new Object();

dojo.declare(
    "RhtModule",
    null,
    {
        id: 0,
    	name: "",
    	pid: "",
    	list: new Array(),
    	ignore: new Array("id", "CONTACT", "VENDOR", "DETAIL"),
        
        constructor : function( name, pid) {
        	this.name = name;
        	if(pid){	//parent id
        		this.pid = "/" + pid;
        	}
        },
        
        // get html file for module
        callModule : function(){
        	var rht = this;
        	dojo.xhrGet({
        	    url: "../template/" + this.name + ".html",
        	    timeout: 5000,
        	    load: function(response, ioArgs){
        	    	//_DEBUG_("xhr get success:" + response);
        	    	dojo.byId("container").innerHTML = response;
        	    	
        	    	rht.generateModuleList();
        	    	
        	    	rht.buildDropdown();
        	    	
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
        	rest(this.pid).then( function(response) {
        		_DEBUG_(response.status);
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
        	var rht = this;
        	var cell = "";
        	var dbView = CONFIG.dbView[rht.name];
        	_DEBUG_(dbView);
        	for (var index in dbView) {
        		var keyname = dbView[index];
        		if(keyname == 'id' || keyname.search('_id') > 0){
        			cell += '<td class="ibm-rht-hide">'+item[keyname]+'</td>';
        		}else if(keyname == 'CONTACT'){
        			cell += '<td><a onclick="rht.showContactsHandler('+item['id']+', \''+item['name']+'\')">Show Contacts</a></td>';
        		}else if(keyname == 'VENDOR'){
        			cell += '<td><a onclick="rht.showVendorHandler('+item[CONFIG.dbView[rht.name+'_VENDOR'][0]]+')">'+item[CONFIG.dbView[rht.name+'_VENDOR'][1]]+'</a></td>';
        		}else if(keyname == 'DETAIL'){
        			cell += '<td><a onclick="rht.showDetailHandler('+item['id']+')">Details</a></td>';
        		}else{
        			cell += '<td>'+item[keyname]+'</td>';
        		}
        	}
        	if (dbView.length > 0){
        		cell += '<td class="ibm-rht-action"><a onclick="rht.updateItemHandler('+item['id']+')">Edit</a>';
        		cell += '&#160;&#160;&#160;<a onclick="rht.deleteItemHandler(this,'+item['id']+')">Delete</a></td>';
        	}
        	_DEBUG_(cell);
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
        		
        		ibmweb.overlay.hide(rht.name + '_overlay');
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
        		if(! in_array(this.ignore, keyname)){
        			_DEBUG_(keyname);
        			dojo.byId(this.name + '_' + keyname).value = this.list[id][keyname];
        		}
	        }
        },
        
        // update item data in db
        updateModuleItem : function() {
        	var rht = this;
        	var rest = dojox.rpc.Rest("../index.php/" + this.name + "/");
        	
        	rest.put(this.id, dojo.formToJson(this.name + "_form")).then( function() {
        		rht.generateModuleList();
        		
        		ibmweb.overlay.hide(rht.name + '_overlay');
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
        
        buildDropdown : function (){
        	var rht = this;
        	dojo.query("#"+ this.name + "_form select[source]").forEach(function(i){
        	    rht.generateOptions(dojo.query(i).attr("source")[0], i.id);
        	});
        },
        
        generateOptions : function( source, target_id ){
        	
        	var optionCode = dojo.trim(dojo.byId(target_id).innerHTML);
        	
        	var rest = dojox.rpc.Rest("../index.php/" + source);
        	rest("").then( function(response) {
        		//generate one row for each item
        		var responseData = eval(response);
        		dojo.forEach(responseData, function(item) {
        			optionCode += '<option value="'+item.id+'">' + item.name + '</option>';
        		});
        		
        		dojo.byId(target_id).innerHTML = optionCode;
        	});
        	
        },
        
        showContactsHandler : function( id, name ) {
        	openwin("../page/contact.html?load="+this.name+"&pid="+id+"&pname="+name,800,480);
        },
        
        showVendorHandler : function( id ) {
        	dojo.xhrGet({
        	    url: "../template/detail.html",
        	    timeout: 5000,
        	    load: function(response, ioArgs){
        	    	dojo.byId("container-sub").innerHTML = response;
        	    	
        	    	var rest = dojox.rpc.Rest("../index.php/vendorcontact/get/?contactid=" + id);
                	rest("").then( function(response) {
                		var responseData = eval(response);
                		dojo.forEach(responseData, function(item, i) {
                			ibmweb.overlay.show("vendorcontact_overlay");
                			
                			var dbView = CONFIG.dbView['vendorcontact'];
                        	for (var index in dbView) {
                        		var keyname = dbView[index];
                        		dojo.byId("vendorcontact_"+keyname).innerHTML = item[keyname];
                        	}
                		});
                	});
        	    	
        	        return response; //必须返回response
        	    },
        	    error: function(response, ioArgs){
        	    	return response; //必须返回response
        	    }
        	});
        },
        
        showDetailHandler : function( id ) {
        	var rht = this;
        	
        	dojo.xhrGet({
        	    url: "../template/detail.html",
        	    timeout: 5000,
        	    load: function(response, ioArgs){
        	    	dojo.byId("container-sub").innerHTML = response;
        	    	
        	    	ibmweb.overlay.show("detail_overlay");
        	    	
        	    	var dbView = CONFIG.dbView['candidate_DETAIL'];
                	for (var index in dbView) {
                		var keyname = dbView[index];
                		dojo.byId("detail_"+keyname).innerHTML = rht.list[id][keyname];
                	}
        	    	
        	        return response; //必须返回response
        	    },
        	    error: function(response, ioArgs){
        	    	return response; //必须返回response
        	    }
        	});
        },
    }
);










