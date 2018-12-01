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
        <input type="text" placeholder="Paste url to catch content">
        <button type="submit">
          <span>Go</span>
        </button>
      </form>
      <div class="wrapper">
        <div class="html-link">
          <?php // echo file_get_contents('fixtures/manythings.html') ?>
        </div>
      </div>
    </main>
    <aside class="html-audios">
      <div class="wrapper">
        <ul>
          <!-- <li>
            <audio controls>
              <source src="http://www.archive.org/download/AmericanStories/The_Boarded_Window_-_By_Ambrose_Bierce.mp3">
            </audio>
          </li> -->
        </ul>
      </div>
    </aside>
  </div>
  <script id="word-item" type="x-tmpl-mustache">
    <h2>{{word}}</h2>
    {{#definitions.length}}
      <div class="info">
        <ul class="definitions">
          {{#definitions}}
            <li>
              <p class="type">{{type}}</p>
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
                        <source src="{{url}}">
                      </audio>
                      <span>{{phonetic}}</span>
                    </li>
                  {{/audios}}
                </ul>
              {{/audios.length}}
            </li>
          {{/definitions}}
        </ul>
      </div>
    {{/definitions.length}}
  </script>
  <script id="audios" type="x-tmpl-mustache">
    <ul>
      {{#audios}}
        <li>
          <audio controls>
            <source src="{{.}}">
          </audio>
        </li>
      {{/audios}}
    </ul>
  </script>
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/mustache.min.js"></script>
  <script src="js/app.js"></script>
</body>
</html>
