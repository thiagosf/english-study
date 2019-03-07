$(function () {
  var mock = false;
  var words = []
  var wordsEl = $('.words ul')
  var formEl = $('.form-link')
  var htmlLinkEl = $('.html-link')
  var audiosEl = $('.html-audios ul')
  var currentAudio = null
  var P_KEY = 80

  function enableClickContent () {
    $('.html-link').click(function(e) {
      if (!e.isDefaultPrevented()) {
        s = window.getSelection()
        if (s.anchorNode) {
          var range = s.getRangeAt(0)
          if (range.startOffset === range.endOffset) {
            var node = s.anchorNode
            var increment = 1
            try {
              while (range.toString().indexOf(' ') != 0) {
                var index = (range.startOffset - 1)
                if (index === -1) {
                  increment = 0
                }
                range.setStart(node, index)
              }
            } catch (error) {
              //
            }
            range.setStart(node, range.startOffset + increment)
            try {
              do {
                range.setEnd(node, range.endOffset + 1)
              } while (range.toString().indexOf(' ') == -1 && range.toString().trim() != '')
            } catch (error) {
              //
            }
          }
          var str = range.toString().trim()
          s.removeAllRanges()
          addWord(str)
        }
      }
    })
  }

  function addWord (word) {
    if (word) {
      word = normalizeWord(word)
      if (isNaN(+word)) {
        if (words.indexOf(word) === -1) {
          words.push(word)
          addWordToList(word)
          markWords()
        }
      }
    }
  }

  function refreshWordList () {
    wordsEl.html('')
    for (var i in words) {
      var html = buildWordItem({ word: words[i] })
      var li = $('<li>', { html: html, class: 'word' })
      wordsEl.append(li)
    }
  }

  function addWordToList (word) {
    var html = buildWordItem({ word: word })
    var li = $('<li>', { html: html, class: 'word' })
    wordsEl.append(li)
  }

  function buildWordItem (data) {
    var template = $('#word-item').html()
    var rendered = Mustache.render(template, data)
    return rendered
  }

  function wordClick (element) {
    var word = element.target.innerText.trim()
    var li = $(element.target).closest('li')
    if (li.hasClass('is-loaded')) {
      li.toggleClass('is-expanded')
    } else {
      li.addClass('is-loading')
      search(word, function (data) {
        li.removeClass('is-loading')
        if (data) {
          var html = buildWordItem({
            word: word,
            definitions: data
          })
          li.html(html)
          li.addClass('is-loaded')
          li.addClass('is-expanded')
        } else {
          li.addClass('is-error')
        }
      })
    }
  }

  function search (word, callback) {
    $.ajax({
      url: 'api.php',
      data: {
        method: 'dictionary',
        mock: mock,
        query: word
      },
      success: function (result) {
        callback(result ? result.data : null)
      },
      error: function () {
        callback()
      }
    })
  }

  function formSubmit (e) {
    e.preventDefault()
    formEl.addClass('is-loading')
    $.ajax({
      url: 'api.php',
      data: {
        method: 'scrap',
        mock: mock,
        url: formEl.find('input').val()
      },
      success: function (result) {
        try {
          htmlLinkEl.html(result.data.html)
          addAudios(result.data.audios)
        } catch (error) {
          console.log(error)
        }
        formEl.removeClass('is-loading')
      },
      error: function (error) {
        console.log(error)
        formEl.removeClass('is-loading')
      }
    })
  }

  function addAudios (audios) {
    var template = $('#audios').html()
    var rendered = Mustache.render(template, {
      audios: audios.map(function (audio) {
        return {
          url: audio,
          name: audio.split('/').pop()
        }
      })
    })
    audiosEl.html(rendered)

    audiosEl.find('audio').each(function (index, item) {
      if (index === 0) {
        currentAudio = item
      }
      item.onplay = function (event) {
        currentAudio = item
      }
    })
  }

  function removeWordMark (e) {
    e.preventDefault()
    removeWord(e.target.innerText)
  }

  function removeWordLink (e) {
    e.preventDefault()
    var word = $(e.target).closest('li').find('h2').text()
    removeWord(word)
  }

  function removeWord (word) {
    word = normalizeWord(word)
    var index = words.indexOf(word)
    if (index > -1) {
      words.splice(index, 1)
      refreshWordList()
      markWords()
    }
  }

  function addEvents () {
    wordsEl.on('click', 'li h2', wordClick)
    wordsEl.on('click', 'li .remove', removeWordLink)
    formEl.on('submit', formSubmit)
    htmlLinkEl.on('click', 'mark', removeWordMark)
  }

  function markWords (word) {
    htmlLinkEl.unmark({
      done: function() {
        var punctuations = ":;.,-–—‒_(){}[]!'\"+=".split('')
        if (word) {
          mark = word
        } else {
          mark = []
          for (var i in punctuations) {
            for (var k in words) {
              mark.push(words[k])
              mark.push(words[k] + punctuations[i])
            }
          }
        }
        htmlLinkEl.mark(mark, {
          accuracy: 'exactly',
          ignorePunctuation: punctuations
        })
      }
    })
  }

  function normalizeWord (word) {
    word = word.toLowerCase()
    word = word.replace(/(,|\.|;|:|!|"|\)|\]|“|”)/ig, ' ').trim()
    return word
  }

  function addPlayPauseControl () {
    $(document).on('keyup', function (event) {
      if (event.target.tagName !== 'INPUT') {
        if (parseInt(event.which) === P_KEY) {
          playOrPauseCurrentlyAudio()
        }
      }
    })
  }

  function getCurrentlyPlayingAudio () {
    return currentAudio
  }

  function playOrPauseCurrentlyAudio () {
    var audio = getCurrentlyPlayingAudio()
    if (audio) {
      if (audio.paused) {
        audio.play()
      } else {
        audio.pause()
      }
    }
  }

  addEvents()
  enableClickContent()
  addPlayPauseControl()
})
