var _$_d50b=["span","name","attr","[]","","replace","append","invalid-feedback","addClass","#err_","image-preview","hasClass","parent","border-color","#dc3545","css","is-valid","removeClass","is-invalid","#ddd",".note-editor *","validate","success","Okay!","fas fa-check-circle","topRight","rgba(0, 0, 0, 0.6)","flipInX","flipOutX","reload","error","Oops!","fas fa-times-circle","reset","trigger","change","val","[name=\"email\"]","#password","querySelector","#eye","click","fa-eye-slash","toggle","classList","type","getAttribute","password","text","setAttribute","addEventListener","#loginSubmitForm","submit","find","[name=\"password\"]","valid","btn-progress","disabled","prop","button[type=\"submit\"]","on","preventDefault","get","#modalForgotPassword","POST","origin","location","/forgot-password","modal-progress","status","message","#formResetPasswordModal","errors","responseJSON","forEach","entries","Terjadi kesalahan!","ajax","ready"];
function jqueryValidation_(p,r,q= {})
{
	let o=r=== undefined?{}:r;//1
	return $(p)[_$_d50b[21]]({errorElement:_$_d50b[0],errorPlacement:function(s,p)
	{
		let t=p[_$_d50b[2]](_$_d50b[1]);//7
		t= t[_$_d50b[5]](_$_d50b[3],_$_d50b[4]);$(_$_d50b[9]+ t)[_$_d50b[8]](_$_d50b[7])[_$_d50b[6]](s)
	}
	,highlight:function(p)
	{
		if($(p)[_$_d50b[12]]()[_$_d50b[11]](_$_d50b[10]))
		{
			$(p)[_$_d50b[12]]()[_$_d50b[15]](_$_d50b[13],_$_d50b[14])
		}
		else
		{
			$(p)[_$_d50b[8]](_$_d50b[18])[_$_d50b[17]](_$_d50b[16])
		}

	}
	,unhighlight:function(p)
	{
		if($(p)[_$_d50b[12]]()[_$_d50b[11]](_$_d50b[10]))
		{
			$(p)[_$_d50b[12]]()[_$_d50b[15]](_$_d50b[13],_$_d50b[19])
		}
		else
		{
			$(p)[_$_d50b[17]](_$_d50b[18])
		}

	}
	,rules:o,ignore:_$_d50b[20],messages:q})
}
function notifToast(w,u,v= false)
{
	if(w== _$_d50b[22])
	{
		iziToast[_$_d50b[22]]({title:_$_d50b[23],icon:_$_d50b[24],message:u,position:_$_d50b[25],timeout:2000,overlayColor:_$_d50b[26],transitionIn:_$_d50b[27],transitionOut:_$_d50b[28],transitionInMobile:_$_d50b[27],transitionOutMobile:_$_d50b[28],onClosing:function()
		{
			if(v)
			{
				location[_$_d50b[29]]()
			}

		}
		})
	}
	else
	{
		if(w== _$_d50b[30])
		{
			iziToast[_$_d50b[30]]({title:_$_d50b[31],icon:_$_d50b[32],message:u,position:_$_d50b[25],timeout:2000,overlayColor:_$_d50b[26],transitionIn:_$_d50b[27],transitionOut:_$_d50b[28],transitionInMobile:_$_d50b[27],transitionOutMobile:_$_d50b[28]})
		}

	}

}
function resetFrom(x)
{
	x[_$_d50b[34]](_$_d50b[33]);$(_$_d50b[37])[_$_d50b[36]](_$_d50b[4])[_$_d50b[34]](_$_d50b[35])
}
$(document)[_$_d50b[78]](function()
{
	const c=document[_$_d50b[39]](_$_d50b[38]);//73
	const a=document[_$_d50b[39]](_$_d50b[40]);//74
	a[_$_d50b[50]](_$_d50b[41],function()
	{
		this[_$_d50b[44]][_$_d50b[43]](_$_d50b[42]);const d=c[_$_d50b[46]](_$_d50b[45])=== _$_d50b[47]?_$_d50b[48]:_$_d50b[47];//77
		c[_$_d50b[49]](_$_d50b[45],d)
	}
	);let b=jqueryValidation_(_$_d50b[51],{email:{required:true,email:true},password:{required:true}});//80
	$(_$_d50b[51])[_$_d50b[60]](_$_d50b[52],function(e)
	{
		let f=$(this)[_$_d50b[53]](_$_d50b[37])[_$_d50b[36]]();//91
		let g=$(this)[_$_d50b[53]](_$_d50b[54])[_$_d50b[36]]();//92
		if($(this)[_$_d50b[55]]())
		{
			if(f&& g)
			{
				$(_$_d50b[59])[_$_d50b[58]](_$_d50b[57],true)[_$_d50b[8]](_$_d50b[56])
			}

		}

	}
	);$(_$_d50b[71])[_$_d50b[60]](_$_d50b[52],function(e)
	{
		e[_$_d50b[61]]();let h=$(this)[_$_d50b[62]](0);//102
		let i=$(_$_d50b[63]);//103
		$[_$_d50b[77]]({type:_$_d50b[64],url:window[_$_d50b[66]][_$_d50b[65]]+ _$_d50b[67],data: new FormData(h),processData:false,contentType:false,beforeSend:function()
		{
			i[_$_d50b[8]](_$_d50b[68])
		}
		,success:function(j)
		{
			notifToast(j[_$_d50b[69]],j[_$_d50b[70]]);if(j[_$_d50b[69]]== _$_d50b[22])
			{
				resetFrom($(_$_d50b[71]))
			}

		}
		,error:function(k)
		{
			rs= k[_$_d50b[73]][_$_d50b[72]];if(rs!== undefined)
			{
				k= {};Object[_$_d50b[75]](rs)[_$_d50b[74]]((l)=>
				{
					let [m,n]=l;//124
					k[m]= n
				}
				)
			}
			//121
			notifToast(_$_d50b[30],_$_d50b[76])
		}
		,complete:function()
		{
			i[_$_d50b[17]](_$_d50b[68])
		}
		})
	}
	)
}
)
// function jqueryValidation_(element, rules, messages = {}) {
//     let _rules = rules === undefined ? {} : rules;
//     // var x = 0;
//     return $(element).validate({
//         errorElement: 'span',
//         errorPlacement: function (error, element) {
//             // console.log(element)
//             let name = element.attr('name');
//             name = name.replace('[]', '');
//             $('#err_' + name).addClass('invalid-feedback').append(error)
//             // x++;
//             // $('#err_' + name+x).addClass('invalid-feedback').append(error)
//         },
//         highlight: function (element) {
//             if ($(element).parent().hasClass('image-preview')) {
//                 $(element).parent().css('border-color', '#dc3545')
//             } else {
//                 $(element).addClass('is-invalid').removeClass('is-valid');
//             }

//         },
//         unhighlight: function (element) {
//             if ($(element).parent().hasClass('image-preview')) {
//                 $(element).parent().css('border-color', '#ddd')
//             } else {
//                 $(element).removeClass('is-invalid');
//             }

//         },
//         rules: _rules,
//         ignore: ".note-editor *",
//         messages: messages
//     })
// }
// function notifToast(stts, msg, reload = false) {
//     if (stts == 'success') {
//         iziToast.success({
//             title: 'Okay!',
//             icon: 'fas fa-check-circle',
//             message: msg,
//             position: 'topRight',
//             timeout: 2000,
//             overlayColor: 'rgba(0, 0, 0, 0.6)',
//             transitionIn: 'flipInX',
//             transitionOut: 'flipOutX',
//             transitionInMobile: 'flipInX',
//             transitionOutMobile: 'flipOutX',
//             onClosing: function () {
//                 if (reload) {
//                     location.reload();
//                 }
//             }
//         });
//     } else if (stts == 'error') {
//         iziToast.error({
//             title: 'Oops!',
//             icon: 'fas fa-times-circle',
//             message: msg,
//             position: 'topRight',
//             timeout: 2000,
//             overlayColor: 'rgba(0, 0, 0, 0.6)',
//             transitionIn: 'flipInX',
//             transitionOut: 'flipOutX',
//             transitionInMobile: 'flipInX',
//             transitionOutMobile: 'flipOutX',
//         });
//     }
// }
// function resetFrom(form) {
//     form.trigger("reset");
//     $('[name="email"]').val("").trigger("change");
// }
// $(document).ready(function () {
//     const passwordInput = document.querySelector("#password")
//     const eye = document.querySelector("#eye")
//     eye.addEventListener("click", function () {
//         this.classList.toggle("fa-eye-slash")
//         const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
//         passwordInput.setAttribute("type", type)
//     });
//     let loginForm = jqueryValidation_('#loginSubmitForm', {
//         email: {
//             required: true,
//             email: true
//         },
//         password: {
//             required: true,
//         },
//     });
//     $('#loginSubmitForm').on('submit', function (e) {
//         // e.preventDefault();
//         let email = $(this).find('[name="email"]').val();
//         let pass = $(this).find('[name="password"]').val();
//         if ($(this).valid()) {
//             if (email && pass) {
//                 $('button[type="submit"]').prop('disabled', true).addClass('btn-progress');
//             }
//         }
//     });
//     $('#formResetPasswordModal').on('submit', function (e) {
//         e.preventDefault();

//         let el = $(this).get(0);
//         let form_ = $('#modalForgotPassword');
//         $.ajax({
//             type: "POST",
//             url: window.location.origin + "/forgot-password",
//             data: new FormData(el),
//             processData: false,
//             contentType: false,
//             beforeSend: function () {
//                 form_.addClass("modal-progress");
//             },
//             success: function (result) {
//                 notifToast(result.status, result.message);
//                 if (result.status == "success") {
//                     resetFrom($('#formResetPasswordModal'));
//                 }
//             },
//             error: function (err) {
//                 rs = err.responseJSON.errors;
//                 if (rs !== undefined) {
//                     err = {};
//                     Object.entries(rs).forEach((entry) => {
//                         let [key, value] = entry;
//                         err[key] = value;
//                     });
//                     // err.showErrors(err);
//                 }
//                 notifToast("error", "Terjadi kesalahan!");
//             },
//             complete: function () {
//                 form_.removeClass("modal-progress");
//             },
//         });
//     });
// });
