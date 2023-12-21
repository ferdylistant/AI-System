var globalParams=["POST","origin","location","/api/home/","card-progress","addClass","parent","#todoList_data","html","#","popover","[data-toggle=\"popover\"]","error","Gagal memuat todo-list!","removeClass","ajax",".card","closest","#card_recent_activity","find",".section-body","Terjadi kesalahan!","fail","","Tidak ada data lagi!","append","#recentActivity","done","/api/home/recent-activity?page=","get","dateTime","normal","fas fa-3x","fa-chevron-circle-left","fa-chevron-circle-right","fa-arrow-circle-left","fa-arrow-circle-right","fa-pause-circle","fa-play-circle","animationSpeed","enter-left","enter-right","exit-left","exit-right","horizontalTimeline","#timelineNaskah","text","swing","ceil","animate","Counter","prop","each",".counter","change","val","[name=\"tb_naskah_tahapan\"]","Pilih Timeline","select2",".select2-timeline","/timeline/show","json","status","Belum ada progress!","#showTimeline","data","log","on","#container_tb_naskah","typeget","#todoList_data .nav-link.active","show.bs.tab","activeElement","delegateTarget","[name=\"timeline\"]","scrollTop","innerHeight","scrollHeight","scroll","#loadScroll","click",".delete-todo","id","/api/home/delete-todo","remove",".delete_list_","fadeOut","ready"];
function loadTodoList(k)
{
	if(k)
	{
		$[globalParams[15]]({type:globalParams[0],url:window[globalParams[2]][globalParams[1]]+ globalParams[3]+ k,beforeSend:function()
		{
			$(globalParams[7])[globalParams[6]]()[globalParams[5]](globalParams[4])
		}
		,success:function(g)
		{
			$(globalParams[9]+ k)[globalParams[8]](g);$(globalParams[11])[globalParams[10]]()
		}
		,error:function(m)
		{
			notifToast(globalParams[12],globalParams[13])
		}
		,complete:function()
		{
			$(globalParams[7])[globalParams[6]]()[globalParams[14]](globalParams[4])
		}
		})
	}

}
function loadRecentData(a)
{
	cardWrap= $(globalParams[20])[globalParams[19]](globalParams[18])[globalParams[17]](globalParams[16]);$[globalParams[15]]({url:window[globalParams[2]][globalParams[1]]+ globalParams[28]+ a,type:globalParams[29],datatype:globalParams[8],beforeSend:function()
	{
		cardWrap[globalParams[5]](globalParams[4])
	}
	})[globalParams[27]](function(n)
	{
		if(n== globalParams[23])
		{
			notifToast(globalParams[12],globalParams[24]);return
		}
		//36
		cardWrap[globalParams[14]](globalParams[4]);$(globalParams[26])[globalParams[25]](n)
	}
	)[globalParams[22]](function(p,o,q)
	{
		notifToast(globalParams[12],globalParams[21])
	}
	)
}
function timelineFunction()
{
	$(globalParams[45])[globalParams[44]]({desktopDateIntervals:200,tabletDateIntervals:150,mobileDateIntervals:120,minimalFirstDateInterval:true,dateIntervals:{"desktop":200,"tablet":150,"mobile":120,"minimal":true},dateDisplay:globalParams[30],dateOrder:globalParams[31],autoplay:false,autoplaySpeed:8,autoplayPause_onHover:false,useScrollWheel:false,useTouchSwipe:true,useKeyboardKeys:true,addRequiredFile:true,useFontAwesomeIcons:true,useNavBtns:true,useScrollBtns:true,iconBaseClass:globalParams[32],scrollLeft_iconClass:globalParams[33],scrollRight_iconClass:globalParams[34],prev_iconClass:globalParams[35],next_iconClass:globalParams[36],pause_iconClass:globalParams[37],play_iconClass:globalParams[38],animation_baseClass:globalParams[39],enter_animationClass:{"left":globalParams[40],"right":globalParams[41]},exit_animationClass:{"left":globalParams[42],"right":globalParams[43]},iconClass:{"base":globalParams[32],"scrollLeft":globalParams[33],"scrollRight":globalParams[34],"prev":globalParams[35],"next":globalParams[36],"pause":globalParams[37],"play":globalParams[38]},animationClass:{"base":globalParams[39],"enter":{"left":globalParams[40],"right":globalParams[41]},"exit":{"left":globalParams[42],"right":globalParams[43]}}})
}
$(document)[globalParams[87]](function()
{
	$(globalParams[53])[globalParams[52]](function()
	{
		$(this)[globalParams[51]](globalParams[50],0)[globalParams[49]]({Counter:$(this)[globalParams[46]]()},{duration:3000,easing:globalParams[47],step:function(c)
		{
			$(this)[globalParams[46]](Math[globalParams[48]](c))
		}
		})
	}
	);$(globalParams[56])[globalParams[55]](globalParams[23])[globalParams[54]]();$(globalParams[59])[globalParams[58]]({placeholder:globalParams[57]});$(globalParams[68])[globalParams[67]](globalParams[54],globalParams[56],function(e)
	{
		let f=$(this)[globalParams[55]]();//138
		let d=$(globalParams[20])[globalParams[19]](this)[globalParams[17]](globalParams[16]);//139
		$[globalParams[15]]({url:window[globalParams[2]][globalParams[1]]+ globalParams[60],type:globalParams[0],data:{id:f},cache:false,dataType:globalParams[61],beforeSend:function()
		{
			d[globalParams[5]](globalParams[4])
		}
		,success:function(g)
		{
			if(g[globalParams[62]]== globalParams[12])
			{
				notifToast(g[globalParams[12]],globalParams[63]);$(globalParams[64])[globalParams[8]]("<div class=\"col-12 offset-3 mt-5\">\x0D\x0A                        <div class=\"row\">\x0D\x0A                            <div class=\"col-4 offset-1\">\x0D\x0A                                <img src=\"https://cdn-icons-png.flaticon.com/512/7486/7486831.png\" width=\"100%\">\x0D\x0A                            </div>\x0D\x0A                        </div>\x0D\x0A                    </div>")
			}
			else
			{
				$(globalParams[64])[globalParams[8]](g[globalParams[65]]);timelineFunction()
			}

		}
		,error:function(j,i,h)
		{
			console[globalParams[66]](j);console[globalParams[66]](i);console[globalParams[66]](h);notifToast(globalParams[12],globalParams[21])
		}
		,complete:function()
		{
			d[globalParams[14]](globalParams[4])
		}
		})
	}
	);loadTodoList($(globalParams[70])[globalParams[65]](globalParams[69]));$(document)[globalParams[67]](globalParams[71],function(e)
	{
		let k=$(e[globalParams[73]][globalParams[72]])[globalParams[65]](globalParams[69]);//182
		loadTodoList(k)
	}
	);var a=1;//185
	let b=$(globalParams[74])[globalParams[55]]();//186
	if(b== true)
	{
		loadRecentData(a)
	}
	//187
	$(globalParams[79])[globalParams[78]](function()
	{
		if($(this)[globalParams[75]]()+ $(this)[globalParams[76]]()>= $(this)[0][globalParams[77]])
		{
			a++;loadRecentData(a)
		}

	}
	);$(globalParams[7])[globalParams[67]](globalParams[80],globalParams[81],function()
	{
		var f=$(this)[globalParams[65]](globalParams[82]);//197
		$[globalParams[15]]({type:globalParams[0],url:window[globalParams[2]][globalParams[1]]+ globalParams[83],data:({id:f}),success:function(l)
		{
			$(globalParams[85]+ f)[globalParams[86]](300,function()
			{
				$(globalParams[85]+ f)[globalParams[84]]()
			}
			)
		}
		,error:function(m)
		{
			notifToast(globalParams[12],globalParams[21])
		}
		})
	}
	)
}
)
// function loadTodoList(tab) {
//     if (tab) {
//         // console.log(tab);
//         $.ajax({
//             type: "POST",
//             url: window.location.origin + "/api/home/" + tab,
//             beforeSend: function() {
//                 $('#todoList_data').parent().addClass('card-progress')
//             },
//             success: function(result) {
//                 // console.log(tab)
//                 $('#' + tab).html(result);
//                 $('[data-toggle="popover"]').popover();
//             },
//             error: function(err) {
//                 // console.log(err)
//                 notifToast('error', 'Gagal memuat todo-list!');
//             },
//             complete: function() {
//                 $('#todoList_data').parent().removeClass('card-progress')
//             }
//         });
//     }

// }
// function loadRecentData(page) {
//     cardWrap = $(".section-body").find('#card_recent_activity').closest(".card");
//     $.ajax({
//         url: window.location.origin + "/api/home/recent-activity?page=" + page,
//         type: "get",
//         datatype: "html",
//         beforeSend: function() {
//             cardWrap.addClass('card-progress');
//         }
//     }).done(function(data) {
//         // console.log(data);
//         if (data == "") {
//             notifToast('error', 'Tidak ada data lagi!');
//             return;
//         }
//         cardWrap.removeClass('card-progress');
//         $("#recentActivity").append(data);
//     }).fail(function(jqXHR, ajaxOptions, thrownError) {
//         notifToast('error', 'Terjadi kesalahan!')
//     });
// }
// function timelineFunction() {
//     $('#timelineNaskah').horizontalTimeline({
//         // ! Deprecated in favour of the object options. //
//         desktopDateIntervals: 200, //************\\
//         tabletDateIntervals: 150, // Minimum: 120 \\
//         mobileDateIntervals: 120, //****************\\
//         minimalFirstDateInterval: true,
//         dateIntervals: {
//             "desktop": 200, //************\\
//             "tablet": 150, // Minimum: 120 \\
//             "mobile": 120, //****************\\
//             "minimal": true
//         },

//         /* End new object options */

//         dateDisplay: "dateTime", // dateTime, date, time, dayMonth, monthYear, year
//         dateOrder: "normal", // normal, reverse

//         autoplay: false,
//         autoplaySpeed: 8, // Sec
//         autoplayPause_onHover: false,

//         useScrollWheel: false,
//         useTouchSwipe: true,
//         useKeyboardKeys: true,
//         addRequiredFile: true,
//         useFontAwesomeIcons: true,
//         useNavBtns: true,
//         useScrollBtns: true,

//         // ! Deprecated in favour of the object options. //
//         iconBaseClass: "fas fa-3x", // Space separated class names
//         scrollLeft_iconClass: "fa-chevron-circle-left",
//         scrollRight_iconClass: "fa-chevron-circle-right",
//         prev_iconClass: "fa-arrow-circle-left",
//         next_iconClass: "fa-arrow-circle-right",
//         pause_iconClass: "fa-pause-circle",
//         play_iconClass: "fa-play-circle",

//         animation_baseClass: "animationSpeed", // Space separated class names
//         enter_animationClass: {
//             "left": "enter-left",
//             "right": "enter-right"
//         },
//         exit_animationClass: {
//             "left": "exit-left",
//             "right": "exit-right"
//         },
//         iconClass: {
//             "base": "fas fa-3x", // Space separated class names
//             "scrollLeft": "fa-chevron-circle-left",
//             "scrollRight": "fa-chevron-circle-right",
//             "prev": "fa-arrow-circle-left",
//             "next": "fa-arrow-circle-right",
//             "pause": "fa-pause-circle",
//             "play": "fa-play-circle"
//         },
//         animationClass: {
//             "base": "animationSpeed", // Space separated class names,
//             "enter": {
//                 "left": "enter-left",
//                 "right": "enter-right"
//             },
//             "exit": {
//                 "left": "exit-left",
//                 "right": "exit-right"
//             }
//         }

//         /* End new object options */
//     });
// }
// $(document).ready(function() {
//     $('.counter').each(function() {
//         $(this).prop('Counter', 0).animate({
//             Counter: $(this).text()
//         }, {
//             duration: 3000,
//             easing: 'swing',
//             step: function(now) {
//                 $(this).text(Math.ceil(now));
//             }
//         });
//     });
//     $('[name="tb_naskah_tahapan"]').val('').change();
//     $(".select2-timeline")
//         .select2({
//             placeholder: "Pilih Timeline",
//         });
//     $('#container_tb_naskah').on('change', '[name="tb_naskah_tahapan"]', function(e) {
//         // console.log($(this).val());
//         let id = $(this).val();
//         let cardWrap = $(".section-body").find(this).closest(".card");
//         $.ajax({
//             url: window.location.origin + "/timeline/show",
//             type: 'POST',
//             data: {
//                 id: id,
//             },
//             cache: false,
//             dataType: 'json',
//             beforeSend: function() {
//                 cardWrap.addClass('card-progress');
//             },
//             success: function(result) {
//                 // console.log(result);

//                 if (result.status == 'error') {
//                     notifToast(result.error, 'Belum ada progress!')
//                     $('#showTimeline').html(`<div class="col-12 offset-3 mt-5">
//                         <div class="row">
//                             <div class="col-4 offset-1">
//                                 <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" width="100%">
//                             </div>
//                         </div>
//                     </div>`);
//                 } else {
//                     $('#showTimeline').html(result.data);
//                     timelineFunction();
//                 }
//             },
//             error: function(xhr, status, error) {
//                 console.log(xhr);
//                 console.log(status);
//                 console.log(error);
//                 notifToast('error', 'Terjadi kesalahan!')
//             },
//             complete: function() {
//                 cardWrap.removeClass('card-progress');
//             }
//         });
//     });
//     loadTodoList($('#todoList_data .nav-link.active').data('typeget'));
//     // Each change tab form penilaian
//     $(document).on('show.bs.tab', function(e) {
//         let tab = $(e.delegateTarget.activeElement).data('typeget');
//         loadTodoList(tab);
//     });
//     var page = 1;
//     let timeline = $('[name="timeline"]').val();
//     if (timeline == true) {
//         loadRecentData(page);
//     }
//     $("#loadScroll").scroll(function() {
//         if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
//             page++;
//             loadRecentData(page);
//         }
//     });
//     $('#todoList_data').on('click', '.delete-todo', function() {
//         var id = $(this).data("id");
//         $.ajax({
//             type: "POST",
//             url: window.location.origin + "/api/home/delete-todo",
//             data: ({
//                 id: id
//             }),
//             success: function(html) {
//                 // console.log(html);
//                 $(".delete_list_" + id).fadeOut(300, function() {
//                     $(".delete_list_" + id).remove();
//                 });
//             },
//             error: function(err) {
//                 notifToast('error', 'Terjadi kesalahan!')
//             }
//         });
//     });
// });
