/*
 * jquery-1.11.1.min.js based
 */ 

//브라우저 체크하는 글로별 변수
var browser = (function() {
    var s = navigator.userAgent.toLowerCase();
    var match = /(webkit)[ \/](\w.]+)/.exec(s) ||
                /(opera)(?:.*version)?[ \/](\w.]+)/.exec(s) ||
                /(msie) ([\w.]+)/.exec(s) ||
               !/compatible/.test(s) && /(mozilla)(?:.*? rv:([\w.]+))?/.exec(s) ||
               [];
    return { name: match[1] || "", version: match[2] || "0" };
}());

/*
 * 함수의 이름이 바뀌거나 이동할 경우에는 업버전이 아닌 파일명이 바뀐 상태로 가야한다.
 * getAgent의 리턴 부분 다시 붙여쓰지 않기. Jsmin 처리할때.
 */

//document.domain = 'www.xxxx.com';

var parentObj = '';

var utilsAjax = {
	
	// .act(url, mode, arg, returnFunc);
	action: function() {
		this.setArg(arguments);
		if (arguments[3] != null && typeof(arguments[3]) == 'function') {
			this.call(arguments[3]);			
		} else {
			this.call(this.setData);	
		}
	}

	, setArg: function(arg){
		var argArray = new Array();
		this.postData = '';
		for (var i=0; i < arg.length; i++) {
			if (i==0) {
				this.url = arg[0];
			} else if (i == 1) {
				this.postData = 'mode=' + encodeURIComponent(arg[1]);
			} else if (i == 2) {
				this.postData += '&' + arg[2];
			}
		}
	}

	, setData: function(oriReq) {
		var oJson = jQuery.parseJSON(oriReq);
		
		if(oJson){
			if (oJson.data !== undefined) {
				var d = oJson.data;
				for (var i in d) {
					if ($('#'+i+'')) {
						$('#'+i+'').html(d[i]);
					}
				}
			}
			
			var oDiv = $('#blankD');
			if(!oDiv.get(0)) {
				oDiv = document.createElement('div');
				oDiv.setAttribute('id', 'blankD');
				document.body.appendChild(oDiv);
				oDiv = null;
				var oDiv = $('#blankD');
			}		
			
			if (oJson.outputScript && oJson.outputScript != '') {
				
				//jQuery도 map 메소드가 존재 하기 때문에 프로토타입의 extractScripts 따와서 함수로 수정했음.
				//스크립트 구문별로 배열에담은 뒤 리턴.
				function extractScripts(arg) {
				    var matchAll = new RegExp('<script[^>]*>([\\S\\s]*?)<\/script>', 'img');
				    var matchOne = new RegExp('<script[^>]*>([\\S\\s]*?)<\/script>', 'im');
					return jQuery.map(arg.match(matchAll) || [], function (arg) { 
							return (arg.match(matchOne) || ['', ''])[1]; 
					});
				}
				
				//map메소드를 이용해서 스크립트 구문 실행.
				jQuery.map(extractScripts(oJson.outputScript), function(script){
					return eval(script);
				});
			}
		}
	}

	, call: function(returnFunction){
		var oAjax = $.ajax({
				url : this.url,			
				type: 'POST',
				data: this.postData,
				success: returnFunction
			});
		delete postData;
		delete oAjax;
	}
}

var utilsFiles = {
	getFileName : function(str) {

		if (str != null) {
			var ln = str.lastIndexOf("/"); //확장자 끝부분을 찾는다.
			if (ln == -1) {
				return null;
			} else {
				var fileEx = str.substring(ln+1, str.length); //마지막 결로부터 확장자 바로전까지 끝는다.
				if (fileEx == '') {
					return null;
				} else {
					return fileEx;					
				}
			}
		}
		return null;
	}

	, getDir : function(str) {

		if (str != null) {
			var ln = str.lastIndexOf("/"); //확장자 끝부분을 찾는다.
			if (ln == -1) {
				return null;
			} else {
				var fileEx = str.substring(0, ln); //마지막 결로부터 확장자 바로전까지 끝는다.
				if (fileEx == '') {
					return null;
				} else {
					return fileEx;					
				}
			}
		}
		return null;
	}
	
	, getFileExtention : function(str, caseSensitive) {
		if (str != null) {
			var ln = str.lastIndexOf("\."); //확장자 끝부분을 찾는다.
			if (ln == -1) {
				return null;
			} else {
				var fileEx = str.substring(ln+1, str.length); //마지막 결로부터 확장자 바로전까지 끝는다.
				if (fileEx == '') {
					return null;
				} else {
					if (caseSensitive == 'upper') 	return fileEx.toUpperCase();			
					else 							return fileEx.toLowerCase();					
				}
			}
		}
		return null;
	}
}

var formCheck = {

	// OK
	// --------------------------------------------
	// text 항목 체크
	// obj : object or element id
	// msg : message
	// focus : return focus
	// return : true, false, 오브젝트가 null 인 경우 false
	// --------------------------------------------
	isNull : function(element, msg, focus) {
		var obj = wbs.getObj(element);
		
		if(obj.get(0) == null || obj.get(0).value.trim() == "") {
			if(msg) { alert(" " + msg + " ")};
			if (obj.get(0) && focus) { obj.get(0).focus(); }
			return true;
		}
		return false;

	}

	// 전화번호만 입력받기
	, isPhoneNo : function(obj) {
		var temp_value = obj.get(0).value.toString();
		temp_value = temp_value.replace(/[^-0-9]/g,'');
		// temp_value = temp_value.replace(/(0(?:2|[0-9]{2}))([0-9]+)([0-9]{4}$)/,"$1-$2-$3");
		obj.value = temp_value;
	}


	// 영어만 입력받기  (대소문자)
	// 나머지 글자 무시 onkeyup 이벤트 용
	// type : 'small', 'big', 'all'
	, isAlphabet : function(obj, type){
	    var temp_value = obj.get(0).value.toString();
	    var regexp = '';
	    var repexp = '';
	    switch(type){
	        case 'small':regexp = /[^a-z]/g;break;
	        case 'big':regexp = /[^A-Z]/g;break;
	        case 'all':regexp = /[^a-z]/i;break;
	        default :regexp = /[^a-z]/i;break;
	    }
	    temp_value = temp_value.replace(regexp, repexp);
	    obj.value = temp_value;
	}


	// 영어만 입력받기  (대소문자)
	// 나머지 글자 무시 
	// 공백이 들어가면 사용하면 안됨.
	, isAlphabet2 : function(obj, msg, del) {
			var str = obj.get(0).value;
			if(str.length == 0)
				return false;

			str = str.toUpperCase();
			for(var i=0; i < str.length; i++) {
				if(!('A' <= str.charAt(i) && str.charAt(i) <= 'Z')) {
					if(msg) { alert(msg); }
					if(del) { obj.get(0).value = ''; }
					obj.get(0).focus();
					return false;
				}
			}
			return true;
	}


	/*
	 * 숫자와 영문만 입력되게 할때, 부가적인 addChar는 더하고 싶은 char를 넣는다.
	 * 문자열에 숫자 영문 체크.
	 * type : number, english, englishNumber
	 */
	, isTextCheck : function (element, type, addChar){

		var tObj = wbs.getObj(element);

	    var temp_value = tObj.get(0).value.toString();

		var regex;
		if (!addChar) { addChar = ''; }

		if (type == 'number') 				regex = new RegExp('^[0-9'+ addChar +']*$', 'g');
		else if (type == 'english') 		regex = new RegExp('^[a-zA-Z'+ addChar +']*$', 'g');
		else if (type == 'englishNumber') 	regex = new RegExp('^[a-zA-Z0-9'+ addChar +']*$', 'g');

		return regex.test(temp_value);
		
	}


	// 이메일 체크
	, isEmail : function(obj, msg, focus) {

		var t = escape(obj.get(0).value.toString());
		if (!t.match(/^[^@]+@[^@.]+\.[^@]+[^@.]$/i)) {
			if (msg) { alert(msg); }
			if (focus) { obj.get(0).focus(); }
			return false;
		}
		return true;

	}
	
	// 날짜 체크
	, isDate : function(yy, mm, dd) {
		var str = /[0-9]{4}[\-][0-9]{2}[\-][0-9]{2}$/;
		
		if (str.test(yy + "-" + mm + "-" + dd)) {
			
			var est_Y = Number(yy);
			var est_M = Number(mm);
			var est_D = Number(dd);
			var sDate = new Date(est_Y, (est_M-1), est_D);

			if( est_M - 1 != sDate.getMonth() ) {
				return false;
			} else {
				return true;	
			}			
			
		} else {
			return false;
		}
	}
	
	/*************************************************************************
	   함수명 : isFloat
	   기  능 : 입력값이 실수인지를 체크
	   인  수 : input - 입력값
	   리턴값 :
	**************************************************************************/
	, isFloat : function isFloat(str) {
	    var numstr = "0123456789.-";
	    var dotstr = ".";
	    var thischar;
	    var count = 0;
	    var countdot = 0;
	    var violation = 0;
	
	    for ( var i=0; i < str.length; i++ ) {
	        thischar = str.substring(i, i+1 );
	
	        if ( numstr.indexOf( thischar ) != -1 )
	            count++;
	
	        if ( dotstr.indexOf( thischar ) != -1 )
	            countdot++;
	
	        if(i==0 && thischar == '.') {
	            violation++;
	        }
	
	        if(i!=0 && thischar == '-') {
	            violation++;
	        }
	    }
	
	    if ( count == str.length && countdot <= 1 && violation == 0)
	        return(true);
	    else
	        return( false );
	}

	/*
	 * element : '선택자' or '해당객체 : this'
	 */
    , isValid:function(element){

        var isValid = true;
        var $element = $(element);
        var id = $element.attr('id');
        var name = $element.attr('name');
        var value = $element.val();

        // <input> uses type attribute as written in tag
        // <textarea> has intrinsic type of 'textarea'
        // <select> has intrinsic type of 'select-one' or 'select-multiple'
        var type = $element[0].type.toLowerCase();

        switch(type){
            case 'text':
            case 'textarea':
            case 'password':
            case 'hidden':
                if ( value.length == 0 || value.replace(/\s/g,'').length == 0 ){ isValid = false; }
                break;
            case 'select-one':
            case 'select-multiple':
                if( !value ){ isValid = false; }
                break;
            case 'checkbox':
            case 'radio':
                if( $('input[name="' + name + '"]:checked').length == 0 ){ isValid = false; };
                break;
        } // close switch()

        return isValid;

    } // close validateElement.isValid()
}

var wbs = {
	// 오브젝트 가져오기.
	getObj : function (element) {
		var obj = null;
		
		if (typeof(element) == 'string') obj = $(element);
		else if (typeof(element) == 'object') obj = element;
		
		return obj;
	}
}