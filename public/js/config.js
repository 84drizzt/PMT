var DEBUGMODE = true;
function _DEBUG_(content){
	if(DEBUGMODE){
		console.log(content);
	}
}

var CONFIG = Object();
CONFIG.dbView = new Array();
CONFIG.dbView['user'] 			= ['id','name','email','role','tieline','cellphone'];
CONFIG.dbView['project']		= ['id','name','description','sponsor_id','sponsor_name','owner_id','owner_name'];
CONFIG.dbView['sponsor'] 		= ['id','name','owner_name','description','contact'];
CONFIG.dbView['sponsorcontact'] = ['id','name','email','description','sponsor_id'];
CONFIG.dbView['vendor'] 		= ['id','name','contact'];
CONFIG.dbView['vendorcontact']	= ['id','name','office_phone','cellphone','email','address','description','vendor_id'];
CONFIG.dbView['skill']	 		= ['id','name','description'];