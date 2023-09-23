//兼容windows下低版本qq浏览器中找不到globalThis
this.globalThis || (this.globalThis = this)
