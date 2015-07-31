<?php 
  $locales = ['de','en','fr'];
  $entries = [
    [
      "id" => "basket.cluster_badge_description",
      "group" => "basket-strings",
      "comment" => "COLOR: [weiß|schwarz]",
      "langs" => [
        "de" => [
          "text" => "Ein Cluster enthält alle Artikel, die bestimmte Anforderungen erfüllen (z. B. Kopierpapier, DIN A4, 80 g/m², {COLOR}).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "20.03.13"
        ],
        "en" => [
          "text" => "A Cluster contains all items that fulfil certain requirements (e. g. copy paper, DIN A4, 80 g/m², \n{COLOR, select,\n  weiß {white}\n    other {black}\n}).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "20.03.13"
        ],
        "fr" => [
          "text" => "Un cluster contient tous les produits répondant à des besoins spécifiques (papier pour imprimante, format A4, 80g/m2, \n{COLOR, select,\n  weiß {blanc}\n    other {noir}\n}).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "20.03.13"
        ]
      ]
    ], 
    [
      "id" => "basket.commit.general_terms_and_privacy",
      "group" => "basket-strings",
      "comment" => "AGB_URL\nDSE_LINK\nDSE_LINK\nDSE_LINK\nDSE_LINK\nDSE_LINK",
      "langs" => [
        "de" => [
          "text" => "Ja, ich bestätige die [AGB]({AGB_URL}) und die [Datenschutzerklärung]({DSE_LINK}) der Mercateo AG.",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "21.04.13"
        ],
        "en" => [
          "text" => "Yes, I confirm the [General Terms and Conditions]({AGB_URL}) and the [data protection notice]({DSE_LINK}) of Mercateo",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "21.04.13"
        ],
        "fr" => [
          "text" => "Oui, j'accepte les [conditions générales de vente]({AGB_URL}) et la [déclaration de confidentialité]({DSE_LINK}) de Mercateo France SAS",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "21.04.13"
        ]
      ]
    ],
    [
      "id" => "placedorder.print.hint.printing_guide",
      "group" => "basket-strings",
      "comment" => "OS: [mac|windows]",
      "langs" => [
        "de" => [
          "text" => "Sie können diese Seite ausdrucken, indem Sie die Druckfunktion Ihres Browsers verwenden ({OS, select, mac {CMD} other {STRG}}+P).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "30.04.13"
        ],
        "en" => [
          "text" => "You can print out this page by using the printing function of your browser ({OS, select, mac {CMD} other {STRG}}+P).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "30.04.13"
        ],
        "fr" => [
          "text" => "Vous pouvez imprimer cette page en utilisant la fonction impression de votre navigateur ({OS, select, mac {CMD} other {STRG}}+P).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "30.04.13"
        ]
      ]
    ],
    [
      "id" => "load_productrow&shy;.changed_deliveryTime&shy;.original_deliveryTime_was",
      "group" => "basket-strings",
      "comment" => null,
      "langs" => [
        "de" => [
          "text" => "Lieferzeit&auml;nderung! Die urspr&uuml;ngliche Lieferzeit des gespeicherten Artikels war",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "16.05.13"
        ],
        "en" => [
          "text" => null,
          "lastAuthor" => null,
          "lastChanged" => null
        ],
        "fr" => [
          "text" => null,
          "lastAuthor" => null,
          "lastChanged" => null
        ]
      ]
    ]

  ];
  for ($i = 0; $i < 2; $i++)
    $entries = array_merge($entries,$entries);
  $currentPage = 10;
  $totalPages = 17;
?>
<!doctype html>
<head>
  <meta charset="UTF-8">
	<title>MessageFormat Live Preview</title>
  <!--Messageformat-->
	<script src='bower_components/messageformat/messageformat.js'></script>
  <script src='bower_components/messageformat/locale/de.js'></script>
  <script src='bower_components/messageformat/locale/en.js'></script>
  <script src='bower_components/messageformat/locale/fr.js'></script>
	<script src='messageFormatPreview.js'></script>
  <!--Codemirror-->
	<script src="bower_components/codemirror/lib/codemirror.js"></script>
	<link  href="bower_components/codemirror/lib/codemirror.css" rel="stylesheet">
	<script src="messageformat.js"></script>

  <script src="bower_components/codemirror/addon/edit/matchbrackets.js"></script>
  <script src="bower_components/codemirror/addon/edit/closebrackets.js"></script>

  <script src="bower_components/codemirror/addon/lint/lint.js"></script>
  <link rel="stylesheet" href="bower_components/codemirror/addon/lint/lint.css">
  <script src="bower_components/codemirror.messageformat/forgiving-messageformat-parser.js"></script>
  <script src="bower_components/codemirror.messageformat/messageformat-lint.js"></script>
  <!--Markdown-->
  <script src="bower_components/marked/marked.min.js"></script>
  <!---JQuery & Bootstrap-->
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <link  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <script src="bower_components/bootstrap-checkbox.min.js"></script>
  <style>
    .CodeMirror {
      height: 100%;
    }
    .well {
      margin: 20px;
    }
    .limit{
      overflow-y: auto;
      height: 192px;
      margin: 0px;
      padding: 0px;
      position: relative;
    }
    tbody > tr:first-child > th:first-child {
      width: 500px;
    }
    /* First column */
    .table > tbody > tr > td:first-child > .limit { 
      overflow: hidden;
      padding: 8px;
    }
    .comment {
      max-height: 90px;
      overflow-y: auto;
      margin: 8px;
      position: absolute;
      bottom: 0px;
      left: 0px;
      right: 0px;
    }
    .table> tbody > tr > td {
      padding: 0px;
    }
    table {
      table-layout: fixed;
      min-width: 1200px;
    }
    .progress {
      margin: 7px;
    }
    .text-center > .label {
      margin: 7px;
    }
    body {
      /*min-width:<?=(1+count($locales))*500?>px;*/
    }
    .btn-active-danger.active {
      color: #fff;
      background-color: #c9302c;
      border-color: #ac2925;
    }
    .btn-active-danger.active:hover {
      color: #fff;
      background-color: #ac2925;
      border-color: #761c19;
    }
    .btn-active-success.active {
      color: #fff;
      background-color: #449d44;
      border-color: #398439;
    }
    .btn-active-success.active:hover {
      color: #fff;
      background-color: #398439;
      border-color: #255625;
    }
    .limit > div > .btn-group > label {
      width: 22px;
      padding: 0px;
      margin: 2px;
    }
    .infoButtons {
      position: absolute;
      bottom: 4px;
      left: 4px;
      z-index:5;
      width:24px;
      vertical-align: bottom;
    }
    .dark > th {
      background-color: #666;
    }
    .CodeMirror-linenumber {
      visibility: hidden;
    }
    .btn-default.nohover:hover {
      color: #333;
      background-color: #fff;
      border-color: #ccc;
    }
    .btn-default.nohover.active:hover {
      color: #333;
      background-color: #e6e6e6;
      border-color: #adadad;
    }
  </style>
</head>
<body>
  <form action="/" method="POST">
    <table class='table' style="table-layout:fixed;position:fixed;top:0px;z-index:101"><thead>
      <tr class='dark'>
        <th class='text-center' style='border-bottom:none;'>
          <div style='float:left;'>
            <input type='submit' class='btn btn-default' value='Speichern'></input>
          </div>
          <div style='float:right;'>
              <div class="btn-group" id='toggleAll'> 
                <a class="btn btn-default nohover code active">Code</a>
                <a class="btn btn-default nohover test">Test</a>
              </div>          
            </div>
        </th>
        <? foreach($locales as $locale): ?>
          <th class='text-center' style='border-bottom:none'>
            <span style='float:left; padding-right: 10px; color: white; font-size:24px;'>
              <?=strtoupper($locale)?> 
            </span>
            <div class="progress">
              <div class="progress-bar progress-bar-warning <?=$locale?>" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:0%; min-width: 2em;">
            </div>
          </th>
        <? endforeach; ?>
      </tr>
      </thead>
    </table>
    <div style='height:100%;margin-top:51px;'>
      <table class='table table-bordered' style="table-layout:fixed"><tbody>
        <? foreach($entries as $index => $current): ?>
          <tr class='<?=$index?>'>
            <td>
              <div class='limit'>
                <h4 style='overflow:hidden;text-overflow: ellipsis;' name='<?=$current["id"]?>'><?=$current['id']?></h4>
                <h4 style='float:left;margin:0px'>
                  <small><?=$current['group']?></small>
                </h4>
                <div style='float:right;'>
                  <div class="btn-group btn-toggle"> 
                    <a class="btn btn-xs btn-default nohover code active">Code</a>
                    <a class="btn btn-xs btn-default nohover test">Test</a>
                  </div>
                </div>
                <pre class='well well-sm comment'><?=($current["comment"] == null || $current["comment"] == "") ? "-\n" : $current["comment"]?></pre>
              </div>
            </td>
            <? foreach($current['langs'] as $locale => $content): ?>
              <td>
                <div class='limit'>
            	    <textarea name ='<?=$current['id']?>[<?=$locale?>][text]' class='<?=$locale?>' style='width:100%;height:100%'><?=$content['text'] ? $content['text'] : ""?></textarea>    
                  <div class='preview <?=$locale?>'></div>
                  <div class='infoButtons'>
                    <div class='btn-group' data-toggle='buttons'>
                      <label class='btn btn-sm btn-default btn-active-success' data-toggle="tooltip" title="Geprüft" data-placement="right">
                        <input type='checkbox' name='<?=$current['id']?>[<?=$locale?>][confirm]' autocomplete='off'> 
                        <i class='fa fa-check'></i>
                      </label> 
                    </div>
                    <br>
                    <div class='btn-group' data-toggle='buttons'>                   
                      <label class='btn btn-sm btn-default btn-active-danger' data-toggle="tooltip" title="Entfernen" data-placement="right">
                        <input type='checkbox' name='<?=$current['id']?>[<?=$locale?>][delete]' autocomplete='off'> 
                        <i class='fa fa-trash'></i>
                      </label>  
                    </div>
                    <br>
                    <div class='btn-group'>                   
                      <label class='btn btn-sm btn-default nohover' data-toggle="tooltip" data-container="body" title="Letze Änderung:<br/><?= ($content['lastAuthor'] != null) ? ($content['lastChanged']."<br/>".$content['lastAuthor']) : "-" ?>" data-html="true" data-placement="right">
                        <i class='fa fa-info-circle'></i>
                      </label>  
                    </div>
                  </div>
               </div>
              </td>
            <? endforeach; ?>
          </tr>
        <? endforeach; ?>
      </tbody></table>
      <ul class="pagination pagination-sm">
            <li class='<?= ($currentPage == 0) ? "disabled" : "" ?>'>
              <a href="/<?= ($currentPage - 1)?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <? for ($i = max(1,$currentPage-5); $i < min($totalPages,$currentPage+5); $i++): ?>
              <? if ($i == $currentPage): ?>
                <li class='active'><a href="#"><?=$i?></a>
              <? else: ?>
                <li><a href="/<?= ($currentPage - 1)?>"><?=$i?></a></li>
              <? endif; ?>
            <? endfor; ?>
            <li class='<?= ($currentPage == $totalPages) ? "disabled" : "" ?>'>
              <a href="/<?= ($currentPage + 1)?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
    </div>
  </form>
	<script type="text/javascript">
    var locales = <?= json_encode($locales)?>;
    var editorInstances;
    var previewCount = 0;
    $(function(){

      editorInstances = $('textarea').map(function(index,ta){
        return CodeMirror.fromTextArea(ta, {
          mode: "messageformat.js",
          lineNumbers: true,
          styleActiveLine: true,
          lint: true,
          lineWrapping: true,
          viewportMargin: Infinity,
          autoCloseBrackets: true,
          matchBrackets: true
        });
      })

      $('[data-toggle="tooltip"]').tooltip()

      $('#toggleAll').click(function(){
        $(this).find('.btn').toggleClass('active');
        // These toggles are set differently
        var opp = $(this).find('.active').hasClass('test') ? 'code' : 'test'
        $('.btn-toggle').filter(function(index, element){ return $(element).find('.'+opp).hasClass('active')}).each(function(index,el){ toggleTestForButton($(el).parent()); });
      })


      $('.btn-toggle').click(function() {
        toggleTestForButton($(this))
      });

      function toggleTestForButton(btnToggle){
        btnToggle.find('.btn').toggleClass('active');  
        var row = btnToggle.closest('tr')
        var editors = row.find('.CodeMirror, .infoButtons')
        var previews = row.find('.preview')
        if (btnToggle.find('.active').hasClass('test')){
          for (var i = 0; i < 3; i++)
          renderPreview(editorInstances[parseInt(row.attr('class'))*3+i].getValue(),previews[i],row.find('.comment').html(), locales[i]);
          $(editors).hide();
          $(previews).show();
        }
        else {
          $(editors).show();
          $(previews).hide();
        }
          
      }

      var renderPreview = function(from,to,comment, locale){
        var whitespace = from.replace(/\n\n\n/g,'<br/></br>').replace(/\n\n/g,'<br/>').replace(/\s+/g,' ');
        var renderer = new marked.Renderer();
        renderer.paragraph = function(string){return string.replace(/&#/g,'&\\#')+"<br/>\n"};
        var md = marked(whitespace,{renderer: renderer});
        // remove trailing <br/> 
        md = md.substring(0,md.length-6);
        var samples = generateSamples(locale,md,comment);
        $(to).html((samples.length == 0 || samples[0].match(/^\s*$/)) ? "" : "<div class='well well-sm'>"+samples.join("</div><div class='well well-sm'>")+"</div>");
      }

      function update(){
        for (var i = 0; i < locales.length; i++){
          var total = 0, finished = 0;
          for (var j = i; j < editorInstances.length; j += locales.length){
            total += 1;
            // If contains text, consider done
            if (!editorInstances[j].getValue().match(/^\s*$/))
              finished += 1;
          }
          var pb = $('.progress-bar.'+locales[i]);
          pb.removeClass('progress-bar-success');
          pb.removeClass('progress-bar-warning');
          if (total == finished)
            pb.addClass('progress-bar-success');
          else
            pb.addClass('progress-bar-warning');
          pb.html(finished+'/'+total);
          pb.css('width',finished/total*100+'%');
        }
      }
      update();
      for (var i = 0; i < editorInstances.length; i++)
        editorInstances[i].on("blur",update);

      $("form").submit(function() {
        for (var i =0; i < editorInstances.length; i++)
          editorInstances[i].toTextArea()
        return true;
     });

    })
  </script>    
</body>
</html>