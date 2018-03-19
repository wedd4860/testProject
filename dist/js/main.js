+function ($) {
	var Setting = {
	}

	var Selector = {
		click:$('.treeview'),
		open:$('.treeview-menu'),
	}
	Selector['click'].on({
		click:function(){
			var _this=$(this);
			if(_this.find(Selector['open']).hasClass('on')==true){
				_this.removeClass('menu-open');
				_this.find(Selector['open']).removeClass('on');
			}else{
				_this.addClass('menu-open');
				_this.find(Selector['open']).addClass('on');
			}
		}
	})

}(jQuery)

+function ($) {

}(jQuery)