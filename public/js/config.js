var DEBUGMODE = true;
function _DEBUG_(content){
	if(DEBUGMODE){
		console.log(content);
	}
}

var CONFIG = Object();
CONFIG.dbView = new Array();
CONFIG.dbView['user'] = ['id','name','email','role','tieline','cellphone'];