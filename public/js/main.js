/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
var _$_99e2 = ["content", "attr", "meta[name=\"csrf-token\"]", "reload", "location", "ajaxSetup", "status", "Request too many!", "ajaxError", "span", "name", "[]", "", "replace", "append", "invalid-feedback", "addClass", "#err_", "image-preview", "hasClass", "parent", "border-color", "#dc3545", "css", "is-valid", "removeClass", "is-invalid", "#ddd", ".note-editor *", "validate", "success", "Okay!", "fas fa-check-circle", "topRight", "rgba(0, 0, 0, 0.6)", "flipInX", "flipOutX", "error", "Oops!", "fas fa-times-circle", "file", "maxSize", "canvas", "createElement", "base64", "indexOf", ",", "split", ";", ":", "length", "charCodeAt", "width", "height", "drawImage", "2d", "getContext", "image/jpeg", "toDataURL", "match", "type", "Not an image", "onload", "src", "result", "target", "readAsDataURL", "POST", "origin", "/notification", "beep", "a", "children", "#containerNotf", "eq", "div", "ajax"];
$[_$_99e2[5]]({
  headers: {
    'X-CSRF-TOKEN': $(_$_99e2[2])[_$_99e2[1]](_$_99e2[0])
  },
  statusCode: {
    401: function _() {
      window[_$_99e2[4]][_$_99e2[3]]();
    }
  }
});
$(document)[_$_99e2[8]](function (_0x2ED0, _0x2F2D, _0x2EEF, _0x2F0E) {
  if (_0x2F2D[_$_99e2[6]] == 429) {
    alert(_$_99e2[7]);
  } else {
    if (_0x2F2D[_$_99e2[6]] == 401) {
      window[_$_99e2[4]][_$_99e2[3]]();
    }
  }
});
function jqueryValidation_(_0x31F6, _0x3234) {
  var _0x3215 = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  var _0x31D7 = _0x3234 === undefined ? {} : _0x3234; //19
  return $(_0x31F6)[_$_99e2[29]]({
    errorElement: _$_99e2[9],
    errorPlacement: function errorPlacement(_0x3253, _0x31F6) {
      var _0x3272 = _0x31F6[_$_99e2[1]](_$_99e2[10]); //25
      _0x3272 = _0x3272[_$_99e2[13]](_$_99e2[11], _$_99e2[12]);
      $(_$_99e2[17] + _0x3272)[_$_99e2[16]](_$_99e2[15])[_$_99e2[14]](_0x3253);
    },
    highlight: function highlight(_0x31F6) {
      if ($(_0x31F6)[_$_99e2[20]]()[_$_99e2[19]](_$_99e2[18])) {
        $(_0x31F6)[_$_99e2[20]]()[_$_99e2[23]](_$_99e2[21], _$_99e2[22]);
      } else {
        $(_0x31F6)[_$_99e2[16]](_$_99e2[26])[_$_99e2[25]](_$_99e2[24]);
      }
    },
    unhighlight: function unhighlight(_0x31F6) {
      if ($(_0x31F6)[_$_99e2[20]]()[_$_99e2[19]](_$_99e2[18])) {
        $(_0x31F6)[_$_99e2[20]]()[_$_99e2[23]](_$_99e2[21], _$_99e2[27]);
      } else {
        $(_0x31F6)[_$_99e2[25]](_$_99e2[26]);
      }
    },
    rules: _0x31D7,
    ignore: _$_99e2[28],
    messages: _0x3215
  });
}
function notifToast(_0x32CF, _0x3291) {
  var _0x32B0 = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
  if (_0x32CF == _$_99e2[30]) {
    iziToast[_$_99e2[30]]({
      title: _$_99e2[31],
      icon: _$_99e2[32],
      message: _0x3291,
      position: _$_99e2[33],
      timeout: 2000,
      overlayColor: _$_99e2[34],
      transitionIn: _$_99e2[35],
      transitionOut: _$_99e2[36],
      transitionInMobile: _$_99e2[35],
      transitionOutMobile: _$_99e2[36],
      onClosing: function onClosing() {
        if (_0x32B0) {
          location[_$_99e2[3]]();
        }
      }
    });
  } else {
    if (_0x32CF == _$_99e2[37]) {
      iziToast[_$_99e2[37]]({
        title: _$_99e2[38],
        icon: _$_99e2[39],
        message: _0x3291,
        position: _$_99e2[33],
        timeout: 2000,
        overlayColor: _$_99e2[34],
        transitionIn: _$_99e2[35],
        transitionOut: _$_99e2[36],
        transitionInMobile: _$_99e2[35],
        transitionOutMobile: _$_99e2[36]
      });
    }
  }
}
var resizeImage = function resizeImage(_0x2EEF) {
  var _0x2F8A = _0x2EEF[_$_99e2[40]]; //89
  var _0x2FC8 = _0x2EEF[_$_99e2[41]]; //90
  var _0x2FE7 = new FileReader(); //91
  var _0x2FA9 = new Image(); //92
  var _0x2F4C = document[_$_99e2[43]](_$_99e2[42]); //93
  var _0x2F6B = function _0x2F6B(_0x3044) {
    var _0x3025 = _0x3044[_$_99e2[47]](_$_99e2[46])[0][_$_99e2[45]](_$_99e2[44]) >= 0 ? atob(_0x3044[_$_99e2[47]](_$_99e2[46])[1]) : unescape(_0x3044[_$_99e2[47]](_$_99e2[46])[1]); //95
    var _0x30C0 = _0x3044[_$_99e2[47]](_$_99e2[46])[0][_$_99e2[47]](_$_99e2[49])[1][_$_99e2[47]](_$_99e2[48])[0]; //98
    var _0x30A1 = _0x3025[_$_99e2[50]]; //99
    var _0x3082 = new Uint8Array(_0x30A1); //100
    for (var _0x3063 = 0; _0x3063 < _0x30A1; _0x3063++) {
      _0x3082[_0x3063] = _0x3025[_$_99e2[51]](_0x3063);
    }
    //101
    return new Blob([_0x3082], {
      type: _0x30C0
    });
  }; //94
  var _0x3006 = function _0x3006() {
    var _0x311D = _0x2FA9[_$_99e2[52]]; //108
    var _0x30FE = _0x2FA9[_$_99e2[53]]; //109
    if (_0x311D > _0x30FE) {
      if (_0x311D > _0x2FC8) {
        _0x30FE *= _0x2FC8 / _0x311D;
        _0x311D = _0x2FC8;
      }
    } else {
      if (_0x30FE > _0x2FC8) {
        _0x311D *= _0x2FC8 / _0x30FE;
        _0x30FE = _0x2FC8;
      }
    }
    //110
    _0x2F4C[_$_99e2[52]] = _0x311D;
    _0x2F4C[_$_99e2[53]] = _0x30FE;
    _0x2F4C[_$_99e2[56]](_$_99e2[55])[_$_99e2[54]](_0x2FA9, 0, 0, _0x311D, _0x30FE);
    var _0x30DF = _0x2F4C[_$_99e2[58]](_$_99e2[57]); //124
    return _0x2F6B(_0x30DF);
  }; //107
  return new Promise(function (_0x315B, _0x313C) {
    if (!_0x2F8A[_$_99e2[60]][_$_99e2[59]](/image.*/)) {
      _0x313C(new Error(_$_99e2[61]));
      return;
    }
    //128
    _0x2FE7[_$_99e2[62]] = function (_0x317A) {
      _0x2FA9[_$_99e2[62]] = function () {
        return _0x315B(_0x3006());
      };
      _0x2FA9[_$_99e2[63]] = _0x317A[_$_99e2[65]][_$_99e2[64]];
    };
    _0x2FE7[_$_99e2[66]](_0x2F8A);
  });
}; //88
$(function () {
  $[_$_99e2[76]]({
    type: _$_99e2[67],
    url: window[_$_99e2[4]][_$_99e2[68]] + _$_99e2[69],
    data: {},
    success: function success(_0x3199) {
      if (_0x3199 != _$_99e2[12]) {
        $(_$_99e2[73])[_$_99e2[72]](_$_99e2[71])[_$_99e2[16]](_$_99e2[70]);
        $(_$_99e2[73])[_$_99e2[72]](_$_99e2[75])[_$_99e2[72]]()[_$_99e2[74]](1)[_$_99e2[14]](_0x3199);
      }
    },
    error: function error(_0x31B8) {
      $(_$_99e2[73])[_$_99e2[72]](_$_99e2[75])[_$_99e2[72]]()[_$_99e2[74]](1)[_$_99e2[14]](_0x31B8);
    }
  });
});
// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     },
//     statusCode: {
//         401: function() {
//             window.location.reload();
//         }
//     }
// });
// $(document).ajaxError(function (event, xhr, settings, thrownError) {
//     if (xhr.status == 429) {
//         // handle the error case
//         alert('Request too many!')
//     } else if (xhr.status == 401) {
//         window.location.reload();
//     }
// });
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

// let resizeImage = function (settings) {
//     let file = settings.file;
//     let maxSize = settings.maxSize;
//     let reader = new FileReader();
//     let image = new Image();
//     let canvas = document.createElement('canvas');
//     let dataURItoBlob = function (dataURI) {
//         let bytes = dataURI.split(',')[0].indexOf('base64') >= 0 ?
//             atob(dataURI.split(',')[1]) :
//             unescape(dataURI.split(',')[1]);
//         let mime = dataURI.split(',')[0].split(':')[1].split(';')[0];
//         let max = bytes.length;
//         let ia = new Uint8Array(max);
//         for (let i = 0; i < max; i++)
//             ia[i] = bytes.charCodeAt(i);
//         return new Blob([ia], {
//             type: mime
//         });
//     };
//     let resize = function () {
//         let width = image.width;
//         let height = image.height;
//         if (width > height) {
//             if (width > maxSize) {
//                 height *= maxSize / width;
//                 width = maxSize;
//             }
//         } else {
//             if (height > maxSize) {
//                 width *= maxSize / height;
//                 height = maxSize;
//             }
//         }
//         canvas.width = width;
//         canvas.height = height;
//         canvas.getContext('2d').drawImage(image, 0, 0, width, height);
//         let dataUrl = canvas.toDataURL('image/jpeg');
//         return dataURItoBlob(dataUrl);
//     };
//     return new Promise(function (ok, no) {
//         if (!file.type.match(/image.*/)) {
//             no(new Error("Not an image"));
//             return;
//         }
//         reader.onload = function (readerEvent) {
//             image.onload = function () {
//                 return ok(resize());
//             };
//             image.src = readerEvent.target.result;
//         };
//         reader.readAsDataURL(file);
//     });
// };

// $(function () {
//     $.ajax({
//         type: "POST",
//         url: window.location.origin + "/notification",
//         data: {},
//         success: function (result) {
//             if (result != '') {
//                 $('#containerNotf').children('a').addClass('beep');
//                 $('#containerNotf').children('div').children().eq(1).append(result);
//             }
//         },
//         error: function (err) {
//             $('#containerNotf').children('div').children().eq(1).append(err);
//             // console.log(err)
//         }
//     })
// });
/******/ })()
;