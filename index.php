<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>English Study</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="page">
    <aside class="main-aside">
      <div class="wrapper">
        <h1 class="main-title">English Study</h1>
        <p class="start-tip">Click on the words to see definition</p>
        <nav class="words">
          <ul></ul>
        </nav>
      </div>
    </aside>
    <main class="main-content">
      <form action="#" class="form-link">
        <input type="text" placeholder="Paste url to catch content" name="url">
        <button type="submit">
          <span>Go</span>
        </button>
      </form>
      <div class="wrapper">
        <div class="html-link">
          <h3>How it works:</h3>
          <ol>
            <li>You paste a url in above form</li>
            <li>We catch the content of url</li>
            <li>We catch the audios of content</li>
            <li>You listen the audio and read the content</li>
            <li>You click in the words to check definition and understand the content better</li>
          </ol>
          <p>Enjoy! 🚀</p>
        </div>
      </div>
    </main>
    <aside class="html-audios">
      <div class="wrapper">
        <h2>Audios</h2>
        <p>Press <code>P</code> to play/pause.</p>
        <ul></ul>
      </div>
    </aside>
  </div>
  <script id="word-item" type="x-tmpl-mustache">
    <h2>{{word}}</h2>
    <a href="#" class="remove">x</a>
    {{#definitions.length}}
      <div class="info">
        <ul class="definitions">
          {{#definitions}}
            <li>
              <p class="type">{{type.text}}</p>
              <ul class="items">
                {{#items}}
                  <li>
                    <p class="definition">{{definition}}</p>
                    {{#examples.length}}
                      <ul class="examples">
                        {{#examples}}
                          <li>{{.}}</li>
                        {{/examples}}
                      </ul>
                    {{/examples.length}}
                  </li>
                {{/items}}
              </ul>
              {{#audios.length}}
                <ul class="audios">
                  {{#audios}}
                    <li>
                      <audio controls>
                        <source src="{{{url}}}">
                      </audio>
                      <span>{{phonetic}}</span>
                    </li>
                  {{/audios}}
                </ul>
              {{/audios.length}}
              <a class="reference" href="{{{reference}}}" target="_blank">reference</a>
            </li>
          {{/definitions}}
        </ul>
      </div>
    {{/definitions.length}}
    {{^definitions.length}}
      <div class="info">
        <p>ops, not found</p>
      </div>
    {{/definitions.length}}
  </script>
  <script id="audios" type="x-tmpl-mustache">
    <ul>
      {{#audios}}
        <li>
          <audio controls>
            <source src="{{{url}}}">
          </audio>
          <small class="name">{{name}}</small>
          <a class="download" href="{{{url}}}" download="{{{url}}}">download</a>
        </li>
      {{/audios}}
    </ul>
  </script>
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/mustache.min.js"></script>
  <script src="js/jquery.mark.min.js"></script>
  <script src="js/app.js"></script>
</body>
</html>
