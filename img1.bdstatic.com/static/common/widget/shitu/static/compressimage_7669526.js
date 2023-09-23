define("common:widget/shitu/static/compressImage",function(t,e,a){function r(){!function(t){"use strict";var e=t.HTMLCanvasElement&&t.HTMLCanvasElement.prototype,r=t.Blob&&function(){try{return Boolean(new Blob)}catch(t){return!1}}(),n=r&&t.Uint8Array&&function(){try{return 100===new Blob([new Uint8Array(100)]).size}catch(t){return!1}}(),i=t.BlobBuilder||t.WebKitBlobBuilder||t.MozBlobBuilder||t.MSBlobBuilder,o=/^data:((.*?)(;charset=.*?)?)(;base64)?,/,u=(r||i)&&t.atob&&t.ArrayBuffer&&t.Uint8Array&&function(t){var e,a,u,l,f,c,s,d;if(e=t.match(o),!e)throw new Error("invalid data URI");for(mediaType=e[2]?e[1]:"text/plain"+(e[3]||";charset=US-ASCII"),a=!!e[4],u=t.slice(e[0].length),l=a?atob(u):decodeURIComponent(u),f=new ArrayBuffer(l.length),c=new Uint8Array(f),s=0;s<l.length;s+=1)c[s]=l.charCodeAt(s);return r?new Blob([n?c:f],{type:mediaType}):(d=new i,d.append(f),d.getBlob(mediaType))};t.HTMLCanvasElement&&!e.toBlob&&(e.mozGetAsFile?e.toBlob=function(t,a,r){t(r&&e.toDataURL&&u?u(this.toDataURL(a,r)):this.mozGetAsFile("blob",a))}:e.toDataURL&&u&&(e.toBlob=function(t,e,a){t(u(this.toDataURL(e,a)))})),"function"==typeof define&&define.amd?define("dataURLtoBlob",function(){return u}):"object"==typeof a&&a.exports?a.exports=u:t.dataURLtoBlob=u}(window)}function n(t,e,a){for(var r="",n=e;e+a>n;n++)r+=String.fromCharCode(t.getUint8(n));return r}function i(t,e){if("Exif"!==n(t,e,4))return!1;var a=e+6,r=!1;if(18761===t.getUint16(a))r=!1;else{if(19789!==t.getUint16(a))return!1;r=!0}if(42!==t.getUint16(a+2,!r))return!1;var i=t.getUint32(a+4,!r);if(8>i)return!1;for(var o=a+i,u=t.getUint16(o,!r),l=void 0,f=0;u>f;f++){l=o+2+12*f;var c=t.getUint16(l,!r);if(274===c){{var s=t.getUint16(l+2,!r),d=t.getUint32(l+4,!r);t.getUint32(l+8,!r)+a}if(3===s&&1===d)return t.getUint16(l+8,!r)}}return!1}function o(t){var e=new DataView(t);if(255!==e.getUint8(0)||216!==e.getUint8(1))return!1;for(var a=2,r=t.byteLength,n=void 0;r>a;){if(255!==e.getUint8(a))return!1;if(n=e.getUint8(a+1),225===n)return i(e,a+4);a+=2+e.getUint16(a+2)}return!1}function u(t,e){var a=new FileReader;a.onload=function(t){var a=o(t.target.result);e(a)},a.readAsArrayBuffer(t)}function l(t,e,a){e=e||{};for(var r in e)e.hasOwnProperty(r)&&(!a||e[r])&&(t[r]=e[r]);return t}function f(t,e){var a=window.URL||window.webkitURL,r=a.createObjectURL(t),n=new Image;n.onload=function(){e(n)},n.src=r}function c(t,e,a,n){r();var i=t.naturalWidth||t.width,o=t.naturalHeight||t.height;e&&"5678".indexOf(e)>-1&&(i=t.naturalHeight||t.height,o=t.naturalWidth||t.width);var u=document.createElement("canvas"),l=o,f=i,c=a.maxLen,s=a.resolution,d=a.isSquare;if(d){var g=i/o,h=c||Math.sqrt(s);g>1?(i=h,o=h/g):(o=h,i=h*g),l=f=h}else{var g=c?(i>o?i:o)/c:i*o/s;g>1?(!c&&(g=Math.sqrt(g)),i/=g,o/=g,f=i,l=o):g=1}var m=(f-i)/2,v=(l-o)/2;u.width=f,u.height=l;var w=u.getContext("2d");switch(w.clearRect(0,0,f,l),w.save(),e){case 3:w.rotate(180*Math.PI/180),w.drawImage(t,-i-m,-o-v,i,o);break;case 6:w.rotate(90*Math.PI/180),w.drawImage(t,v,-i-m,o,i);break;case 8:w.rotate(270*Math.PI/180),w.drawImage(t,-o-v,m,o,i);break;case 2:w.translate(i,0),w.scale(-1,1),w.drawImage(t,m,v,i,o);break;case 4:w.translate(i,0),w.scale(-1,1),w.rotate(180*Math.PI/180),w.drawImage(t,-i-m,-o-v,i,o);break;case 5:w.translate(i,0),w.scale(-1,1),w.rotate(90*Math.PI/180),w.drawImage(t,v,-i-m,o,i);break;case 7:w.translate(i,0),w.scale(-1,1),w.rotate(270*Math.PI/180),w.drawImage(t,-o-v,m,o,i);break;default:w.drawImage(t,m,v,i,o)}w.restore();var U={dxPercent:m/f,dyPercent:v/l};u.toBlob(function(t){n(t,f,l,U)},a.fileType,a.quality)}function s(t,e,a){var r=t||{},n=e||function(){},i=r.type||"";if(!/^image\//.test(i))return n(t,0);var o={isSquare:!1,compressType:0,resolution:1e6,quality:null,fileType:i||"image/jpeg",limitSize:r.size};o=l(o,a),f(r,function(e){if(e){var a=e.naturalWidth||e.width,i=e.naturalHeight||e.height;if(o.maxLen&&(a>i?a:i)<=o.maxLen||a*i<=o.resolution)return void n(t,0);u(r,function(a){a=a||1,a?c(e,a,o,function(e,a,r,i){var u=e.size<=o.limitSize;if(!u)return void n(t,-1);var l=e;if(1===o.compressType){var f=new FileReader;f.onload=function(t){var e=t.target.result;0===e.indexOf("data:image")&&(e=e.split(",")[1]),l=encodeURIComponent(e),n(l,1,i)},f.readAsDataURL(e)}else n(l,1,i)}):n(t,0)})}else n(t,0)})}return s});