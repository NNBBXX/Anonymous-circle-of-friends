function initWX(){
	var thisUrl=location.href.split('#')[0];
	var code = GetQueryString('code');
	$.ajax({
		url : "js/data.php",
		type : "POST",
		dataType : "json",
		async:false,
		data:{
			url:thisUrl,
			code:code
		},
		success : function(json) {
			if(json.err==0){
				wx.config({
				    debug: false,
				    appId: json.data.appId,
				    timestamp: json.data.timeStamp,
				    nonceStr: json.data.nonceStr,
				    signature: json.data.signature,
				    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline','onMenuShareQQ','onMenuShareQZone']
				});
				nickname = json.data.nickname;
                openid = json.data.openid;
                imgurl = json.data.headimgurl;
			}
		}
		
	});
    //添加用户信息
    $.ajax({
    	type:"post",
    	url:"adduser.php",
    	dataType:'json',
    	async:false,
    	data:{"openid":openid,"nickname":nickname,"headimgurl":imgurl},
    	success:function(data){
    		book = data.book;
    		console.log(data);
    	}
    });
    
    var bo = GetQueryString('book');
    if(bo !="" && bo !=null){
    	book = bo;
    	$.ajax({
    	type:"post",
    	url:"getuser.php",
    	dataType:'json',
    	async:false,
    	data:{"book":book},
    	success:function(data){
    		nickname = data.nickname;
    		imgurl = data.imgurl;
    	}
    });
    }
    
    
    /*var ni = GetQueryString('nickname');
    if(ni !="" && ni !=null)
    nickname = ni;*/
}
function GetQueryString(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  decodeURI(r[2]); return null;
}
