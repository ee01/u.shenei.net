define("common:widget/ui/arch/Component/Component",function(t){function n(t){return Object.prototype.toString.call(t)}var i=t("common:widget/ui/arch/base/base"),e=t("common:widget/ui/arch/EventDispatcher/EventDispatcher"),o=i.Class(function(t,n){e.call(this);var i=this.getParams(t,n,!0);this.configs={},this.setConfig(i)},e).extend({getParams:function(t,i,e){var o,r={};t=t||{},i=i||{};for(o in i)r[o]=i[o];for(o in t)e?("undefined"==typeof r[o]||n(t[o])===n(r[o]))&&(r[o]=t[o]):r[o]=t[o];return r},getConfig:function(t){return t?this.configs[t]:this.configs},setConfig:function(t,n){if(!n&&"object"==typeof t){for(var i in t)this.configs[i]=t[i];return this}return this.configs[t]=n,this}});return o});