function loadModule( name ) {
	rht = new RhtModule(name);
	rht.callModule();
}

function openwin(url, width, height)
{

	var clientwin=window.open(url, 'clientwin', 'width='+width+',height='+height+',resizable=yes,top=200,left=300,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes');
	if(clientwin && !clientwin.closed && !clientwin.opener) {
		clientwin.opener = this;
	}

	if(clientwin) {
		clientwin.focus();
	};
}

function getParam(name){
    var search = document.location.search;
    var pattern = new RegExp("[?&]"+name+"\=([^&]+)", "g");
    var matcher = pattern.exec(search);
    var items = null;
    if(null != matcher){
        try{
        	items = decodeURIComponent(decodeURIComponent(matcher[1]));
        }catch(e){
        	try{
        		items = decodeURIComponent(matcher[1]);
        	}catch(e){
        		items = matcher[1];
        	}
        }
    }
    return items;
}

function dijitDialogShow(dTitle, dContent, dClass) {
	dojo.require('dijit.Dialog');
	dojo.addOnLoad(function() {
		var d = new dijit.Dialog({
			title : dTitle,
			content : dContent,
			class: dClass
		});
		d.show();
	});
	return false;
}

function in_array(arr, e)
{
	var S = String.fromCharCode(2);
    var r = new RegExp(S+e+S);
    return r.test(S+arr.join(S)+S);
}

