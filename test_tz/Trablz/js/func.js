//(function(){
		
var _mcontainer = document.querySelector('#msnr');
var msnry = new Masonry( _mcontainer, {
	'columnWidth': '.float.col-1',
	itemSelector: '.float',
	gutter: 0,
	transitionDuration: 0,
	//"isFitWidth": true
	//"isFitWidth": 'true'
});
//msnry.bindResize();



var _tmp = document.querySelectorAll('#header-menu-buttons li a');
Array.prototype.forEach.call(_tmp,function(ell,i){
	var _i = ell.dataset['id'];
	ell.addEventListener('click',function(e){
		e.preventDefault();
		var wrap = document.getElementById('underNav'),
			targ = document.getElementById(_i);
			if (!wrap.classList.contains('uncollapsed'))
				{
					wrap.classList.add('uncollapsed');
					targ.classList.add('uncollapsed');
				}
			else if (targ.classList.contains('uncollapsed'))
			{
				targ.classList.remove('uncollapsed');
				wrap.classList.remove('uncollapsed');
			}
			else
			{
				wrap.querySelector('.uncollapsed').classList.remove('uncollapsed');
				targ.classList.add('uncollapsed');
			}
		
	})
})
document.getElementById('underNav').addEventListener('mouseleave',function(e){
	this.classList.remove('uncollapsed');
	this.querySelector('.uncollapsed').classList.remove('uncollapsed');
});


var _tp = document.querySelectorAll('#header-nav-inner li');
Array.prototype.forEach.call(_tp,function(ell,i){
	ell.addEventListener('mouseover',function(){
		if (document.querySelector('#categories ul.uncollapsed')!= null)
			document.querySelector('#categories ul.uncollapsed').classList.remove('uncollapsed');
		if (document.querySelector('#header-nav-inner li.activeL')!= null)
			document.querySelector('#header-nav-inner li.activeL').classList.remove('activeL');
		// if (document.querySelector('#header-nav-inner li.active')!= null)
		// 	document.querySelector('#header-nav-inner li.active').classList.remove('active');
		document.getElementById('underNav').classList.add('uncollapsed');
		document.getElementById('categories').classList.add('uncollapsed');
		var _q= this.dataset['trigger'];
		document.querySelector('#categories ul[data-trigger="'+_q+'"]').classList.add('uncollapsed');
		this.classList.add('activeL','active')
	})
})



//})();