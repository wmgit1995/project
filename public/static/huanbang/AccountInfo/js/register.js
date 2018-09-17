var myScroll;

function loaded() {
	myScroll = new IScroll('#all-wrapper',{
         mouseWheel: false,
         probeType: 2,
         bindToWrapper: true,
         click: true,
         taps:true
	});

}
// 验证