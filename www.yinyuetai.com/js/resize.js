;(function () {
  var BASE_PAGE_WIDTH = 1600
  var BASE_REM_SIZE = 100

  var MIN_PAGE_WIDTH = 800
  var MIN_REM_SIZE = 50

  function resize() {
    var pageWidth = window.innerWidth

    if (pageWidth <= MIN_PAGE_WIDTH) {
      window.gRemSize = MIN_REM_SIZE
    } else {
      window.gRemSize = (pageWidth / BASE_PAGE_WIDTH) * BASE_REM_SIZE
    }

    console.log('pageWidth:', pageWidth)
    console.log('window.gRemSize:', window.gRemSize)

    document.documentElement.style.fontSize = window.gRemSize + 'px'
  }

  resize()

  window.addEventListener('resize', resize)
})()
