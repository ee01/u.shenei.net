define('common:widget/shitu/run', function(require, exports, module) {

  /**
   * upload shitu data
   * User: wangqun
   * Date: 15-08-01
   *
   * Modified by rongweiwei at 2015-1-8
   */
  
  var $ = require('common:widget/ui/base/base');
  var utils = require('common:widget/ui/utils/utils');
  var Animate = require('common:widget/shitu/static/animate');
  var compressImage = require('common:widget/shitu/static/compressImage');
  var gtimeout;
  var uploadDomain = '../https@graph.baidu.com';
  
  var compressOpt = {
      // 压缩后图片的长边
      maxLen: 800,
  
      // width * height 限制
      resolution: 1000000,
  
      // 压缩图片的质量
      quality: 0.75,
  
      // 是否压缩成正方形
      isSquare: false,
  
      // 压缩后的图片数据类型，0:二进制, 1:base64
      compressType: 0
  };
  
  /**
   * 识图区域组件
   *
   * @constructor
   */
  function Shitu() {
      // 页面搜索框
      this.homeForm = document.getElementById('homeSearchForm');
      // 提示层
      this.point = document.getElementById('point');
      // 最外层div
      this.content = document.getElementById('stsug');
      // 提交按钮 百度一下
      this.submitobj = document.getElementById('sbobj');
      // form表单
      this.form = document.getElementById('form1');
      this.form2 = document.getElementById('form2');
      // 用于给后端传递识图的类型：粘贴，上传或拖拽
      this.shituvalue = document.getElementById('shitu1');
      this.shituvalue2 = document.getElementById('shitu2');
      // 本地上传file
      this.file = document.getElementById('stfile');
      // 输入框
      this.url = document.getElementById('stuurl');
      // 识图入口
      this.entry = document.getElementById('sttb');
      // 识图提示信息
      this.stTipsBox = document.getElementById('stTipsBox');
      // 识图搜索按钮
      this.shituAd=document.getElementById('shituEnter');
      // 识图关闭按钮
      this.close = document.getElementById('closest');
      // 识图取消按钮
      this.cancelButton = document.getElementById('cancelst');
      // 提示层
      this.tips = document.getElementById('stmore');
      this.dttip = document.getElementById('dttip');
      this.statetip = document.getElementById('statetip');
      this.undragtip = document.getElementById('undragtip');
      this.untip = document.getElementById('untip');
      // 高级帮助栏
      this.hpobj = document.getElementById('hp');
      this.sthp = document.getElementById('sthelp');
      this.draghp = document.getElementById('dragtip');
      this.kw = document.getElementById('kw');
      this.dragts = document.getElementById('dragtg');
      this.clickurl = '../stu.baidu.com';
      this.onuploadtg = 0;
      this.ftn = document.getElementById('stftn').value || '';
      this.isDisplay = window.tn === 'shitu-index' ? 1 : 0;
      this.isSubmit = 0;
      this.isdrag = true;
      this.chrome = /chrome\/(\d+\.\d+)/i.test(navigator.userAgent) ? + RegExp['\x241'] : undefined;
      this.safari = /Safari\/(\d+\.\d+)/i.test(navigator.userAgent) ? + RegExp['\x241'] : undefined;
      // this.isIe = /msie (\d+\.\d+)/i.test(navigator.userAgent) ? (document.documentMode || + RegExp['\x241']) : undefined;
      this.isIe = parseInt((/msie (\d+)/.exec(navigator.userAgent.toLowerCase()) || [])[1]);
      if (isNaN(this.isIe)) {
          this.isIe = parseInt((/trident\/.*; rv:(\d+)/.exec(navigator.userAgent.toLowerCase()) || [])[1]);
      }
      this.isOpera = /opera(\/| )(\d+(\.\d+)?)(.+?(version\/(\d+(\.\d+)?)))?/i.test(navigator.userAgent) ?  + ( RegExp['\x246'] || RegExp['\x242'] ) : undefined;
      this.isIE6789 = $.browser.msie && ($.browser.version <= 9);
      this.callbacks = {
          aftershow: {},
          beforehide: {}
      };
      // 是否来自识图
      this.isFromSt = utils.getQueryValue('fr') === 'shitu';
      // 识图区域是否显示
      this.isShowSt = false;
      // 动画
      this.animate = new Animate($('#mock-stsug'));
  };
  
  $.extend(Shitu.prototype, {
      init: function () {
          var me = this,
              addEvent = me.addEvent,
              content = me.content;
          // 初始化控件
          this.initDisplay();
          window.tn === 'index' && me.initTipsStatus();
          window.tn === 'shitu-index' && me.initHomeStatus();
  
          // 屏蔽不支持拖拽的浏览器
          if (this.safari && !this.chrome) {
              if (window.tn === 'index') {
                  this.dttip.style.opacity = 0;
                  if (this.statetip) {
                      this.close.style.opacity = 0;
                      this.statetip.style.opacity = 0;
                  }
              } else {
                  this.dttip.style.display = 'none';
                  if (this.statetip) {
                      this.statetip.style.display = 'none';
                      this.close.style.display = 'none';
                  }
              }
          }
  
          // 页面加载完成初始化识图相机入口
          // TODO 该语句可能有问题,并没有被调用
          addEvent(window, 'load', function (e) {
              // 加载时隐藏加载框
              me.initDisplay();
          });
  
          // 拖拽终止时保证点击隐藏图层
          addEvent(document, 'click', function (e) {
              // 识图首页
              if (window.tn === 'shitu-index') {
                  return;
              }
              // 先把识图显示状态记下来
              var isStDisplaying = !!me.isShowSt;
  
              if (me.hideContent(e, 0)) {
                  // 这里的语句貌似有问题
                  // 感觉是冗余代码
                  window.setTimeout(function () {
                      if (!me.isDisplay && !me.isSubmit) {
                          me.closest();
                      }
                  }, 10);
  
                  if (isStDisplaying) {
                      // 关闭后显示提示信息
                      me.afterCloseSt();
                  }
              }
          });
  
          // 拖拽终止时保证隐藏上传图层
          addEvent(document, 'mousemove', function (e) {
              if (me.hideContent(e, 1)) {
                  window.setTimeout(function () {
                      if (!me.isDisplay && !me.isSubmit && me.isdrag) {
                          me.closest();
                      }
                  }, 10);
              }
          });
  
          // 绑定入口相机按钮点击事件
          me.entry && addEvent(me.entry, 'click', function (e) {
              me.preventDefault(e);
              // sug框的隐藏需要，由于sug框每个页面写了一个或多个，所以决定改这儿
              // me.stopPropagation(e);
  
              // 首页下点击隐藏tip
              if (window.tn === 'index') {
                  me.showStTips(false);
              }
  
              // 获取识图入口icon的位置
              var entry = $(me.entry);
              var entryPos = entry.position();
              entryPos.left = entryPos.left + entry.width() / 2;
              entryPos.top = entryPos.top + entry.height() / 2;
  
              me.animate.showAnimation(entryPos, function () {
                  me.isDisplay = 1;
                  me.displayst(true);
              });
          });
  
          // 显示help帮助
          me.sthp && addEvent(me.sthp, 'mouseover', function (e) {
              me.preventDefault(e);
              me.stopPropagation(e);
              me.tips.style.display = 'block';
          });
  
          // 隐藏help帮助
          me.sthp && addEvent(me.sthp, 'mouseout', function (e) {
              gtimeout = setTimeout(function () {
                  me.tips.style.display = 'none';
              }, 500);
          });
  
          // 显示识图tip
          me.tips && addEvent(me.tips, 'mouseover', function (e) {
              e = window.event || e;
              if (me.fixedMouse(e, me.tips)) {
                  if (gtimeout) clearTimeout(gtimeout);
              }
          });
  
          // 隐藏识图tip
          me.tips && addEvent(me.tips, 'mouseout', function (e) {
              e = window.event || e;
              if (me.fixedMouse(e, me.tips)) {
                  me.tips.style.display = 'none';
              }
          });
  
          /**
           * 绑定图片上传file变化，获取相关上传图片信息
           */
          addEvent(me.file, 'change', function (e) {
              // require.async('common:widget/ui/nsclick/nsclick.js',function (nsclick) {
              //     var logStr = 'p=index&event_type=shitu.search.up&pos=upload';
              //     nsclick.stat(logStr);
              // });
              if (me.file.files.length > 0) {
                  me.getValue();
              }
              me.file.value = '';
          });
  
          me.entry && addEvent(me.entry, 'mouseover', function (e) {
              if (me.closeStTipsTimer) {
                  clearTimeout(me.closeStTipsTimer);
                  me.closeStTipsTimer = null;
              }
              me.showStTips(true);
          });
  
          me.entry && addEvent(me.entry, 'mouseout', function (e) {
              me.showStTips(false);
          });
          /**
           * 绑定识图提交按钮点击事件
           */
          addEvent(me.submitobj, 'click', function (e) {
              var siteReg = /^(\s)*(http|https):\/\//;
              var val = me.url.value;
              var notsub = !siteReg.test(val) || !me.checkImgType(val);
              e = window.event || e;
  
              if(val != ''){
                  if (window.tn === 'result' && p) {
                      p(null, 1111103, {
                          'pos': 'paste',
                          'fm': 'searchresult'
                      });
                  } else if (window.tn === 'detail' && statistic) {
                      statistic.send('5.1011103', {
                          pos: 'drag',
                          fm: 'searchdetail'
                      });
                  }
              }
  
              if (notsub) {
                  // alert('您的文件格式不正确或图片网址过长。支持jpg、gif、png、jpeg、bmp格式图片及250个字符内的图片网址。');
                  if ($('.stutips').length === 1) {
                      $('.stutips').addClass('warn');
                      setTimeout(function () {
                          $('.stutips').removeClass('warn');
                      }, 2000);
                  }
                  $('.stuwr').addClass('warn');
                  setTimeout(function () {
                      $('.stuwr').removeClass('warn');
                  }, 2000);
                  this.form.reset();
              }else{
                  // speed
                  $.cookie('uploadTime', new Date().getTime(), {path: 'default.htm'});
                  me.shituvalue.value = 'paste';
                  me.submitForm(true, me.form, 'urlSearch');
                  me.point.style.display = 'block';
              }
              me.preventDefault(e);
          });
  
          // 绑定关闭按钮的点击事件
          me.close && addEvent(me.close, 'click', function (e) {
              me.stopPropagation(e);
              me.isIe ? window.document.execCommand('Stop'): window.stop();
  
              // 重置表单
              me.form.reset();
              me.form2.reset();
  
              // 关闭识图区域
              var closeFrom = 'btn';
              me.closest(closeFrom);
  
              // 关闭后显示提示信息
              me.afterCloseSt();
          });
  
          if (me.isOpera || !window.FileReader) {
              return;
          }
  
          addEvent(me.url, 'paste', function (e) {
              var clipboardData = e.clipboardData||window.clipboardData;
              var items = clipboardData.items;
              var types = clipboardData.types;
              if(items && items.length && types && types.length){
                  for (var k in items) {
                      if (items[k].kind === 'file') {
                          return me.shituUploadByHtml5(items[k].getAsFile());
                      }
                  }
              }
          });
  
          // 监听图片或文件拖拽事件
          addEvent(document, 'dragenter', function (e) {
              me.point.style.display = 'none';
              me.displayst();
              if (me.safari && !me.chrome) {
  
              } else {
                  me.draghp.style.display = '';
              }
          });
          addEvent(document, 'dragover', function (e) {
              me.point.style.display = 'none';
              me.displayst();
              if (me.safari && !me.chrome) {
  
              } else {
                  me.draghp.style.display = '';
              }
          });
          addEvent(content, 'dragenter', function (e) {
              me.preventDefault(e);
              me.stopPropagation(e);
          });
          addEvent(content, 'dragover', function (e) {
              me.preventDefault(e);
              me.stopPropagation(e);
          });
  
          // 绑定拖拽事件上传图片，拖拽结束开始识图
          addEvent(content, 'drop', function (e) {
              me.preventDefault(e);
              me.stopPropagation(e);
              if (me.safari && !me.chrome) {
                  me.close.style.display = 'block';
                  me.undragtip.style.display = 'block';
                  me.isdrag = false;
                  return;
              }
              // speed
              $.cookie('uploadTime', new Date().getTime(), {path: 'default.htm'});
  
              me.isSubmit=1;
  
              if (window.tn === 'result' && p) {
                  p(null, 1111101, {
                      'pos': 'drag',
                      'fm': 'searchresult'
                  });
              } else if (window.tn === 'detail' && statistic) {
                  statistic.send('5.1011101',{pos:'drag',fm:'searchdetail'});
              }
              if (e.dataTransfer.files.length) {
                  try {
                      me.shituUploadByHtml5(e.dataTransfer.files[0]);
                  } catch(c) {
                      me.hideLoading();
                      return;
                  }
              } else if (e.dataTransfer.types.indexOf && -1 != e.dataTransfer.types.indexOf('text/html') || e.dataTransfer.types.contains && -1 != e.dataTransfer.types.contains('text/html')) {
                  var div = document.createElement('div');
                  div.innerHTML = e.dataTransfer.getData('text/html');
                  var imgs = div.getElementsByTagName('img');
                  if (imgs && imgs[0] && imgs[0].src) {
                      //url拖拽上传，添加drag=1
                      var input = document.createElement('input');
                      input.name = 'drag';
                      input.value= '1';
                      input.type = 'hidden';
                      me.form.appendChild(input);
                      me.shituvalue.value = 'drag';
  
                      if (0 == imgs[0].src.indexOf('data:')) {
                          me.url.value = imgs[0].getAttribute('data-imgurl') || imgs[0].src;
                          me.submitForm(true, me.form, 'urlSearch');
                      } else {
                          me.url.value = imgs[0].src;
                          me.submitForm(true, me.form, 'urlSearch');
                      }
                  }else return;
              } else if (e.dataTransfer.types.indexOf && -1 != e.dataTransfer.types.indexOf('text/uri-list')) {
                  me.url.value = e.dataTransfer.getData('text/uri-list');
                  me.submitForm(true,me.form,'urlSearch');
              }else{
                  return;
              }
          });
  
          // 绑定取消识图按钮的点击事件
          me.cancelButton && addEvent(me.cancelButton, 'click', function (e) {
              me.stopPropagation(e);
  
              me.isIe ? window.document.execCommand('Stop'): window.stop();
              me.form.reset();
              me.form2.reset();
              me.isSubmit = 0;
              me.draghp.style.display = 'none';
              me.point.style.display = 'none';
              me.onuploadtg = 0;
  
              if (!me.isDisplay) {
                  me.closest();
              }
          });
      },
  
      sendSpeedLog: function (obj) {
          var img = new Image();
          var url = '//imgstat.baidu.com/6.gif?';
          var urlJson = $.extend({
              _dev: 'pc',
              speed: 'shitu',
              shituvs: window.vsid
          }, obj);
  
          var i = 0;
          for (var key in urlJson) {
              if (i === 0) {
                  url += key + '=' + urlJson[key];
              } else {
                  url += '&' + key + '=' + urlJson[key];
              }
              i++;
          }
  
          img.src = url + '&_=' + (Date.now ? Date.now() : +new Date);
      },
  
      getFm: function () {
          var fm = '';
          switch (window.tn) {
              case 'index':
                  fm = 'index';
                  break;
              case 'result':
                  fm = 'searchresult';
                  break;
              case 'detail':
                  fm = 'searchdetail';
                  break;
              case 'shitu-index':
                  fm = 'home';
                  break;
          }
          return fm;
      },
  
      getRange: function () {
          var range = '';
          switch (window.tn) {
              case 'result':
                  range = '{"page_from": "imagePage"}';
                  break;
              case 'detail':
                  range = '{"page_from": "imageView"}';
                  break;
              case 'index':
                  range = '{"page_from": "imageIndex"}';
                  break;
              case 'shitu-index':
                  range = '{"page_from": "shituIndex"}';
                  break;
          }
          return range;
      },
  
      // url识图页面跳转
      shitu2jump: function (queryImageUrl, uptype) {
          var me = this;
          var formData = new FormData();
          formData.append('sdkParams', window.sdkParams);
          $.ajax({
              url: uploadDomain + '/upload?image=' + encodeURIComponent(queryImageUrl)
                  + '&tn=pc&from=pc&image_source=PC_UPLOAD_URL&range=' + this.getRange()
                  + '&extUiData%5bisLogoShow%5d=1&uptime=' + Date.now(),
              type: 'post',
              processData: false,
              contentType: false,
              data: formData,
              success: function (response) {
                  if (response.status === 0) {
                      location.assign(response.data.url);
                  } else if (response.status === 1002) {
                      alert('未找到该图片的详细信息，请上传其他图片');
                      me.hideLoading();
                  } else {
                      alert('出错了！（规则未定）');
                      me.hideLoading();
                  }
              },
              error: function () {
                  alert('网络问题，请稍候再试！');
                  me.hideLoading();
              }
          });
      },
  
      // ajaxSubmit文件上传（选择图片上传）
      shituUploadByForm: function () {
          var me = this;
          var fileDom = this.file;
          if (fileDom.files && fileDom.files[0] && fileDom.files[0].size >= 1024 * 1024 * 10) {
              alert('上传的图片大小不能超过10M');
  
              me.hideLoading();
              return;
          }
          me.showLoading();
  
          var file = fileDom.files[0];
          var formData = new FormData();
          $.each(file, function (k, v) {
              formData.append(k, v);
          });
          formData.append('sdkParams', window.sdkParams);
  
          // 先压缩再上传
          try {
              compressImage(file, function (newFile) {
                  formData.append('image', newFile, file.name);
  
                  me.doAjax(formData);
              }, compressOpt);
          }
          catch {
              formData.append('image', file, file.name);
              me.doAjax(formData);
          }
      },
  
      // htm5文件上传（拖拽上传）
      shituUploadByHtml5: function (file) {
          var me = this;
          if (me.isOpera) {
              return;
          }
          if (file && file.size >= 1024 * 1024 * 10) {
              alert('拖动的图片大小不能超过10M');
              me.hideLoading();
  
              return;
          }
          me.showLoading();
  
          var formData = new FormData();
          $.each(file, function (k, v) {
              formData.append(k, v);
          });
          formData.append('sdkParams', window.sdkParams);
          this.sendSpeedLog({
              uptype: 'drag',
              pos: 1
          });
  
          // 先压缩再上传
          try {
              compressImage(file, function (newFile) {
                  formData.append('image', newFile, file.name);
                  me.doAjax(formData);
              }, compressOpt);
          }
          catch {
              formData.append('image', file, file.name);
              me.doAjax(formData);
          }
      },
  
      doAjax(formData) {
          var me = this;
          $.ajax({
              url: uploadDomain + '/upload?tn=pc&from=pc&image_source=PC_UPLOAD_IMAGE_MOVE&range='
                  + this.getRange() + '&extUiData%5bisLogoShow%5d=1&uptime=' + Date.now(),
              type: 'post',
              data: formData,
              processData: false,
              contentType: false,
              success: function (response) {
                  if (response.status === 0) {
                      location.assign(response.data.url);
                  } else if (response.status === 1002) {
                      alert('未找到该图片的详细信息，请上传其他图片');
                      me.hideLoading();
                  } else {
                      alert('出错了！（规则未定）');
                      me.hideLoading();
                  }
              },
              error: function () {
                  alert('网络问题，请稍候再试！');
                  me.hideLoading();
              }
          });
      },
  
      /**
       * 初始化入口显示状态
       */
      initDisplay: function () {
          if (this.entry) {
              this.entry.style.display = '';
          }
          if (this.dragts) {
              this.closedg();
          }
          if (this.form) {
              this.form.reset();
          }
          if (this.form2) {
              this.form2.reset();
          }
          if (this.point) {
              this.point.style.display = 'none';
          }
  
          // 如果来自识图，则自动展开识图区域
          if (this.isFromSt) {
              this.isDisplay = 1;
              this.displayst(true);
          }
      },
  
      initTipsStatus: function () {
          var me = this;
          var isFirstShowTip = $.cookie('firstShowTip') || $.cookie.get('firstShowTip');
          if (isFirstShowTip === '1') {
              me.showStTips(false);
          } else {
              $.cookie.set('firstShowTip', 1);
              me.autoCloseStTips();
          }
      },
  
      initHomeStatus: function () {
          this.close.style.display = 'none';
      },
  
      autoCloseStTips: function () {
          var me = this;
          me.closeStTipsTimer = null;
          me.closeStTipsTimer = setTimeout(function () {
              me.showStTips(false);
              me.closeStTipsTimer = null;
          }, 5000);
      },
  
      closedg: function () {
          var me = this;
          if(!me.isOpera && window.FileReader) {
              return;
          }
          if(this.dragts) {
              this.dragts.style.display = 'none';
          }
          return;
      },
  
      openContent: function () {
          var me = this;
          me.isDisplay = 1;
          me.displayst(true);
      },
  
      /**
       * 隐藏识图区域
       *
       * @param {event} e 事件对象
       * @param {number} tg 标识
       */
      hideContent: function (e, tg) {
          var e = window.event || e;
          var target = e.srcElement || e.target;
  
          // 向上搜索
          while (target && target.tagName !== 'BODY' && target.tagName !== 'HTML' && target.getAttribute) {
              var id = target.getAttribute('id');
              if (id == 'stcontent') {
                  return false;
              }
              target = target.parentNode;
          }
  
          if (tg === 0) {
              this.closest();
              return true;
          } else {
              this.draghp.style.display = 'none';
              return true;
          }
      },
  
      fixedMouse: function (e, target) {
          var related,type = e.type.toLowerCase(),me = this;//这里获取事件名
          if (type == 'mouseover') {
              related = e.relatedTarget || e.fromElement
          } else if (type == 'mouseout') {
              related = e.relatedTarget || e.toElement
          } else return true;
          return related && related.prefix != 'xul' && !this.contains(target, related) && related !== target;
      },
  
      contains: function (p, c) {
          return p.contains ? p != c && p.contains(c) : !!(p.compareDocumentPosition(c) & 16);
      },
  
      /**
       * 添加事件
       *
       * @param {Element} elem 元素
       * @param {string} type 事件名称
       * @param {Function} fn 处理函数
       */
      addEvent: function (elem, type, fn) {
          if (elem.attachEvent) {
              var fnKey = type + fn;
              elem[fnKey] = function () {
                  fn(window.event);
              };
              elem.attachEvent('on' + type, elem[fnKey]);
          } else {
              elem.addEventListener(type, fn, false);
          }
      },
  
      /**
       * 取消事件
       *
       * @param {Element} elem 元素
       * @param {string} type 事件名称
       * @param {Function} fn 处理函数
       */
      removeEvent: function (elem, type, fn) {
          if (elem.detachEvent) {
              var fnKey = type + fn;
              elem.detachEvent('on' + type, elem[fnKey]);
              elem[fnKey] = null;
          } else {
              elem.removeEventListener(type, fn, false);
          }
      },
  
      /**
       * 取消默认行为
       *
       * @param {Event} event 事件对象
       */
      preventDefault: function (event) {
          event.preventDefault ? event.preventDefault() : (event.returnValue = false);
      },
  
      /**
       * 取消冒泡
       *
       * @param {Event} event 事件对象
       */
      stopPropagation: function (event) {
          event.stopPropagation ? event.stopPropagation() : (event.cancelBubble = true);
      },
  
      /**
       * 展示视图提示信息
       *
       * @param {boolean} isShow 是否显示
       */
      showStTips: function (isShow) {
          if (isShow) {
              this.stTipsBox.style.display = 'block';
          } else {
              this.stTipsBox.style.display = 'none';
          }
      },
  
      /**
       * 关闭识图区域
       */
      closest: function (closeFrom) {
          var me = this;
          if (!this.isdrag && closeFrom) {
              // 隐藏Safari拖拽提示
              this.undragtip.style.display = 'none';
              this.untip.style.display = 'none';
              this.close.style.display = 'none';
              setTimeout(function () {
                  me.isdrag = true;
              }, 300);
          } else if (!closeFrom || (this.isdrag && closeFrom)) {
              // 隐藏非点击关闭来源的关闭
              this.content.style.display = 'none';
              this.undragtip.style.display = 'none';
              this.untip.style.display = 'none';
              if (this.hpobj) {
                  this.hpobj.style.display = '';
              }
              this.isDisplay = 0;
              // this.kw.focus();
              if (this.homeForm) {
                  this.homeForm.style.visibility = 'visible';
              }
              this.entry && (this.entry.style.visibility = 'visible');
              this.isShowSt = false;
          }
      },
  
      /**
       * 展开识图区域
       *
       * @param {boolean} focus 是否focus
       */
      displayst: function (focus) {
          this.point.style.display = 'none';
          if (this.statetip) {
              this.close.style.display = 'block';
              this.statetip.style.display = 'block';
          }
          if (this.isIE6789) {
              this.untip.style.display = 'block';
              if (this.statetip) {
                  this.close.style.display = 'none';
                  this.statetip.style.display = 'none';
              }
          }
          this.content.style.display = '';
          this.isdrag = true;
          if (this.hpobj) this.hpobj.style.display = 'none';
          focus && this.url.focus();
          if (this.homeForm) {
              this.homeForm.style.visibility = 'hidden';
          }
          this.entry && (this.entry.style.visibility = 'hidden');
          this.isShowSt = true;
  
          // 针对IE模拟placeholder效果
          if ($.browser.msie) {
              $(this.url).focus(function () {
                  var input = $(this);
                  if (input.val() === input.attr('placeholder')) {
                      input.val('');
                      input.removeClass('placeholder');
                  }
              }).blur(function () {
                  var input = $(this);
                  if (input.val() === '' || input.val() === input.attr('placeholder')) {
                      input.addClass('placeholder');
                      input.val(input.attr('placeholder'));
                  }
              }).blur();
          }
      },
  
      /**
       * 显示视图提示信息
       */
      afterCloseSt: function () {
          // 识图首页
          if (window.tn === 'shitu-index') {
              return;
          }
          // 获取识图入口icon的位置
          var entry = $(this.entry);
          var entryPos = entry.position();
          entryPos.left = entryPos.left + entry.width() / 2;
          entryPos.top = entryPos.top + entry.height() / 2;
  
          var me = this;
          this.animate.hideAnimation(entryPos, function () {
              if (window.tn !== 'index') {
                  me.showStTips(true);
                  setTimeout(function () {
                      me.showStTips(false);
                  }, 1000);
              }
  
              // 重置表单
              me.form.reset();
              me.form2.reset();
          });
      },
  
      checkImgType: function (fileURL) {
          return true;
          var right_type = new Array(
                              '.jpg',
                              '.gif',
                              '.jpeg',
                              '.png',
                              '.bmp'
                          );
          var right_typeLen = right_type.length;
          var imgUrl = fileURL.toLowerCase();
          // 去除行首空格
          imgUrl = imgUrl.replace(/(^\s*)/g, '');
          // 去除行尾空格
          imgUrl = imgUrl.replace(/(\s*$)/g, '');
          var postfixLen = imgUrl.length;
          var len4 = imgUrl.substring(postfixLen - 4, postfixLen);
          var len5 = imgUrl.substring(postfixLen - 5, postfixLen);
          for (var i = 0; i < right_typeLen; i++) {
              if ((len4 == right_type[i]) || (len5 == right_type[i])) {
                  return true;
              }
          }
          return false;
      },
  
      /**
       * 获取上传图片信息、隐藏提示层
       */
      getValue: function () {
          // file上传图片信息，url表示上传图片url，point表示提示层
          // 获取上传图片信息时需要隐藏提示层
          var file = this.file,
              // url = this.url,
              point = this.point,
              form2 = this.form2;
              point.style.display = 'none';
  
          var fileURL = file.value;
          // url.value = fileURL;
  
          // 是否存在二进制图片URL
          var returnvalue = (fileURL != '') && this.checkImgType(fileURL);
  
          this.shituvalue2.value = 'upload';
          // 提交表单
          this.submitForm(returnvalue, form2, 'uploadSearch');
      },
  
      /**
       * 提交表单数据,针对图片URL
       * @param returnvalue
       * @param form
       * @param type
       * @returns {boolean}
       */
      submitForm: function (returnvalue, form, type) {
          if (!returnvalue) {
              alert('您的文件格式不正确或图片网址过长。支持jpg、gif、png、jpeg、bmp格式图片及250个字符内的图片网址。');
              this.point.style.display = 'none';
              this.draghp.style.display = 'none';
              form.reset();
              return false;
          } else {
              // 判断来源
              if (window.tn === 'index') {
                  window.ss && window.ss({type: 'searchNum', p: type, form: 'wantu'});
              } else if (window.tn === 'result') {
                  form.fm.value = 'searchresult';
                  window.ss && window.ss({type: 'searchNum', p: type, form: 'searchresult'});
              } else if (window.tn === 'detail') {
                  form.fm.value = 'searchdetail';
                  window.ss && window.ss({type: 'searchNum', p: type, form: 'searchDetail'});
              } else if (window.tn === 'shitu-index') {
                  form.fm.value = 'home';
              }
              form.fm.sdkParams = window.sdkParams;
              this.entry && (this.entry.style.zIndex = '2');
              this.point.style.display = 'block';
              if (this.statetip) {
                  this.close.style.display = 'none';
                  this.statetip.style.display = 'none';
              }
              this.onuploadtg = 1;
  
              this.submit2shitu(form);
  
              if (this.safari && !this.chrome) {  // 识图后返回仍然"识图loading"
                  setTimeout(function () {
                      this.point.style.display = 'none';
                  }, 500);
              }
              return true;
          }
      },
  
      submit2shitu: function (form) {
          if (form.id === 'form1') {
              this.shitu2jump(this.url.value, this.shituvalue.value);
          } else {
              this.shituUploadByForm();
          }
      },
  
      showLoading: function () {
          this.point.style.display = 'block';
          if (this.statetip) {
              this.close.style.display = 'none';
              this.statetip.style.display = 'none';
          }
      },
  
      hideLoading: function () {
          this.point.style.display = 'none';
      },
  
      on: function (e, name, func) {
          if(this.callbacks[e])
              this.callbacks[e][name] = func;
      },
  
      fire: function (e, name) {
          if(this.callbacks[e]) {
              this.callbacks[e][name] = null;
              delete this.callbacks[e][name];
          }
      }
  });
  
  module.exports = Shitu;
  

});
