<?php 
  $locales = ['de','en','fr'];
  $progress = [
    'de' => [50,50],
    'en' => [41,50],
    'fr' => [48,50]
  ];
  $entries = [
    [
      "id" => "basket.cluster_badge_description",
      "group" => "basket-strings",
      "comment" => "COLOR: [weiß|schwarz]",
      "langs" => [
        "de" => [
          "text" => "Ein Cluster enthält alle Artikel, die bestimmte Anforderungen erfüllen (z. B. Kopierpapier, DIN A4, 80 g/m², {COLOR}).\n\n\n\"Alt-T\" öffnet die Vorschau",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "20.03.13"
        ],
        "en" => [
          "text" => "A Cluster contains all items that fulfil certain requirements (e. g. copy paper, DIN A4, 80 g/m², \n{COLOR, select,\n  weiß {white}\n  other {black}\n}).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "20.03.13"
        ],
        "fr" => [
          "text" => "Un cluster contient tous les produits répondant à des besoins spécifiques (papier pour imprimante, format A4, 80g/m2, \n{COLOR, select,\n  weiß {blanc}\n  other {noir}\n}).",
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
        "fr" => null
      ]
    ],
    [
      "id" => "load_productrow&shy;.changed_deliveryTime&shy;.original_deliveryTime_was",
      "group" => "basket-strings",
      "comment" => "AGB_URL\nDSE_LINK\nDSE_LINK\nDSE_LINK\nDSE_LINK\nDSE_LINK",
      "langs" => [
        "de" => [
          "text" => "Lieferzeit&auml;nderung! Die urspr&uuml;ngliche Lieferzeit des gespeicherten Artikels war",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "16.05.13"
        ],
        "en" => null,
        "fr" => null
      ]
    ]
  ];
  $currentPage = 10;
  $totalPages = 17;
?>
<!doctype html>
<head>
  <meta charset="UTF-8">
	<title>MessageFormat Live Preview</title>

  <!--Messageformat-->
	<script src='bower_components/messageformat/messageformat.js'></script>
  <script src='messageFormatPreview.js'></script>
  <? foreach ($locales as $locale): ?>
    <script src='bower_components/messageformat/locale/<?=$locale?>.js'></script>
  <? endforeach; ?>

  <!--Codemirror-->
	<script src="bower_components/codemirror/lib/codemirror.js"></script>
	<link href="bower_components/codemirror/lib/codemirror.css" rel="stylesheet">
  <script src="bower_components/codemirror/addon/edit/matchbrackets.js"></script>
  <script src="bower_components/codemirror/addon/edit/closebrackets.js"></script>
  <script src="messageformat.js"></script>
  <script src="bower_components/codemirror/addon/lint/lint.js"></script>
  <link href="bower_components/codemirror/addon/lint/lint.css" rel="stylesheet">
  <script src="bower_components/codemirror.messageformat/forgiving-messageformat-parser.js"></script>
  <script src="messageformat-lint.js"></script>
  
  <!--Markdown-->
  <script src="bower_components/marked/marked.min.js"></script>
  
  <!---jQuery & Bootstrap-->
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">

  <style>
    body {
      min-width:<?=(1+count($locales))*500?>px;
    }
    th {
      background-color: #666;
      box-shadow: 0px 0px 20px #999;
    }
    th:not(:first-child) {
      border-left: 1px solid white;
    }
    td {
      padding: 0px !important;
    }
    /* First column */
    td:first-child > .limit { 
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
    .limit{
      overflow-y: auto;
      height: 192px;
      margin: 0px;
      padding: 0px;
      position: relative;
    }
    .preview > .well, .preview > .panel {
      margin: 20px;
    }
    .CodeMirror {
      height: 100%;
    }
    .CodeMirror-linenumber {
      visibility: hidden;
    }
    .actionButtons {
      position: absolute;
      top: 4px;
      left: 4px;
      z-index:5;
      width:24px;
    }
    .actionButtons > .btn-group > * {
      width: 22px;
      padding: 0px;
      margin: 2px;
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
    <table class='table fixedHeader' style="table-layout:fixed;position:fixed;top:0px;z-index:101;min-width:<?=(1+count($locales))*500?>px;"><thead>
      <tr>
        <th class='text-center' style='border-bottom:none;'>
          <div style='float:left;'>
            <input type='submit' class='btn btn-default' value='Speichern'></input>
            <button class='btn btn-default' id='close'>Schließen</button>
          </div>
          <div style='float:right;'>
            <button class='btn btn-default' id='showAll'>Alle einblenden</button>
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
            <div class="progress" style='margin:7px;'>
              <div class="progress-bar progress-bar-warning <?=$locale?>" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:<?=$progress[$locale][0]/$progress[$locale][1]*100?>%; min-width: 2em;">
            </div>
          </th>
        <? endforeach; ?>
      </tr>
      </thead>
    </table>
    <table class='table table-bordered' style="table-layout:fixed;height:100%;margin-top:50px"><tbody>
      <? foreach($entries as $row => $current): ?>
        <tr data-editors='<?=$row*count($locales)?>'>
          <td>
            <div class='limit'>
              <h4 style='word-wrap: break-word;'><?=str_replace([".","_"], ["&shy.","&shy_"], $current['id'])?></h4>
              <h4 style='float:left;margin:0px'>
                <small><?=$current['group']?></small>
              </h4>
              <div style='float:right;'>
                <button class='btn btn-xs btn-default hideThis'>Ausblenden</button>
                <div class="btn-group btn-toggle"> 
                  <a class="btn btn-xs btn-default nohover code active">Code</a>
                  <a class="btn btn-xs btn-default nohover test">Test</a>
                </div>
              </div>
              <pre class='well well-sm comment'><?=($current["comment"] == null || $current["comment"] == "") ? "-\n" : $current["comment"]?></pre>
            </div>
          </td>
          <? $col = 0; foreach($current['langs'] as $locale => $content):?>
            <td data-editor='<?= $row*count($locales)+$col ?>' data-locale='<?=$locale?>'>
              <div class='limit editor'>
          	    <textarea name ='<?=$current['id']?>[<?=$locale?>]' class='<?=$locale?>' style='width:100%;height:100%'><?=$content ? $content['text'] : ""?></textarea>    
                <div class='actionButtons'>
                  <div class='btn-group' data-toggle='buttons'>
                    <button class='btn btn-sm btn-default undo' data-toggle="tooltip" title="Rückgängig" data-placement="right">
                      <i class='fa fa-undo'></i>
                    </button> 
                  </div>
                  <br>
                  <div class='btn-group' data-toggle='buttons'>
                    <button class='btn btn-sm btn-default insertPlural <?= ($locale == 'hu') ? 'disabled' : ''?>' data-toggle="tooltip" title="Plural Syntax einfügen" data-placement="right">
                      P
                    </button> 
                  </div>
                  <br>
                  <div class='btn-group' data-toggle='buttons'>
                    <button class='btn btn-sm btn-default insertSelect' data-toggle="tooltip" title="Select Syntax einfügen" data-placement="right">
                      S
                    </button> 
                  </div>
                  <br>
                  <? /* Entfernen und Geprüft Checkboxes werden nicht benötigt
                  <div class='btn-group' data-toggle='buttons'>    
                    <label class='btn btn-sm btn-default' data-toggle="tooltip" title="Löschen" data-placement="right">
                      <input type='checkbox' name='<?=$current['id']?>[<?=$locale?>][remove]' autocomplete='off'> 
                      <i class='fa fa-trash'></i>
                    </label>
                  </div>
                  <br>
                 <? if ($content['lastAuthor'] != null) :?>
                    <div class='btn-group' data-toggle='buttons'>
                      <label class='btn btn-sm btn-default btn-active-success' data-toggle="tooltip" title="Geprüft" data-placement="right">
                        <input type='checkbox' name='<?=$current['id']?>[<?=$locale?>][confirm]' autocomplete='off'> 
                        <i class='fa fa-check'></i>
                      </label> 
                    </div>
                    <br>
                  <? endif; */ ?>
                  <? if ($locale != 'de'): ?>
                    <div class='btn-group' data-toggle='buttons'>
                      <button class='btn btn-sm btn-default copyGerman' data-toggle="tooltip" title="Deutsch&nbsp;übernehmen" data-placement="right">
                        <i class='fa fa-copy'></i>
                      </label> 
                    </div>
                    <br>
                  <? endif; ?>
                </div>
                <div style='position:absolute; left:0px; bottom: 0px; z-index:5; color: #999;'>
                  <i class='fa fa-edit' style='padding:10px 8px; display:none' data-toggle="tooltip" title="Geändert" data-placement="top"></i>
                  <br>
                  <? if ($content != null) :?>
                    <i class='fa fa-info-circle' style='padding:10px 8px;' data-toggle="tooltip" title="Letze Änderung am <?=$content['lastChanged']?> von <?=$content['lastAuthor']?>" data-placement="top"></i>
                  <? endif;?>
                </div>
              </div>
              <div class='limit preview' style='display:none;'></div>
            </td>
          <? $col++; endforeach; ?>
        </tr>
      <? endforeach; ?>
    </tbody></table>
    <div class='text-center'>
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
    var changed = {}
    $(function(){

      editorInstances = $('textarea').map(function(index,ta){
        var ta = CodeMirror.fromTextArea(ta, {
          mode: "messageformat.js",
          lineNumbers: true,
          styleActiveLine: true,
          lint: true,
          lineWrapping: true,
          viewportMargin: Infinity,
          autoCloseBrackets: true,
          matchBrackets: true,
          extraKeys: {
            "Tab": false,
            "Alt-T": function(cm) {
              toggleTestForButton($(cm.getTextArea()).closest('tr').find('.btn-toggle'));
            }
          },

        });
        ta.on('changes',function(ta){
          var td = $(ta.getTextArea()).closest('td');
          var id = td.data('editor');
          if (ta.getValue() == ta.getTextArea().value){
            ta.markClean();
            td.find('.fa-edit').hide();
          }
          else {
            td.find('.fa-edit').show();
          }
          changed[id] = !ta.isClean()
          $('[type=submit]').removeClass('btn-default').removeClass('btn-warning')
          $('[type=submit]').addClass(needsSave() ? 'btn-warning' : 'btn-default')
        })
        return ta;
      });

      function needsSave(){
        var ns = false;
        for (var key in changed) {
          ns = ns || changed[key];
        }
        return ns;
      }

      $('[data-toggle="tooltip"]').tooltip({container: "body"});

      $('#toggleAll').click(function(){
        $(this).find('.btn').toggleClass('active');
        // These toggles are set differently
        var opp = $(this).find('.active').hasClass('test') ? 'code' : 'test';
        $('.btn-toggle').filter(function(i, e){ return $(e).find('.'+opp).hasClass('active'); }).each(function(i,e){ toggleTestForButton($(e)); });
      })

      $('.btn-toggle').click(function() {
        toggleTestForButton($(this));
      });

      function toggleTestForButton(btnToggle){
        btnToggle.find('.btn').toggleClass('active');  
        var row = btnToggle.closest('tr');
        var editors = row.find('.editor');
        var previews = row.find('.preview');
        if (btnToggle.find('.active').hasClass('test')){
          for (var i = 0; i < 3; i++)
            renderPreview(editorInstances[row.data('editors')+i].getValue(),previews[i],row.find('.comment').html(), locales[i]);
          $(editors).hide();
          $(previews).show();
        }
        else {
          $(editors).show();
          $(previews).hide();
        }
      }

      var renderPreview = function(from,to,comment, locale){
        try {

          var whitespace = from.replace(/\n\n\n/g,'<br/></br>').replace(/\n\n/g,'<br/>').replace(/\s+/g,' ');
          var renderer = new marked.Renderer();
          renderer.paragraph = function(string){return string.replace(/&#/g,'&\\#')+"<br/>\n"};
          var md = marked(whitespace,{renderer: renderer});
          // remove trailing <br/> 
          md = md.substring(0,md.length-6);
          var samples = generateSamples(locale,md,comment);
          $(to).html((samples.length == 0 || samples[0].match(/^\s*$/)) ? "" : "<div class='well well-sm'>"+samples.join("</div><div class='well well-sm'>")+"</div>");
        }
        catch (err){
          $(to).html('<div class="panel panel-danger"><div class="panel-heading">Fehler</div><div class="panel-body">'+err.message+'</div></div>')
        }
      }

      var otherProgress = <?=json_encode($progress)?>;
      for (var i = 0; i < locales.length; i++){
        var total = 0, finished = 0;
        for (var j = i; j < editorInstances.length; j += locales.length){
          total += 1;
          // If contains text, consider done
          if (!editorInstances[j].getValue().match(/^\s*$/))
            finished += 1;
        }
        otherProgress[locales[i]][0] -= finished;
        otherProgress[locales[i]][1] -= total;
      }

      function update(){
        for (var i = 0; i < locales.length; i++){
          var total = otherProgress[locales[i]][1], finished = otherProgress[locales[i]][0];
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

      $('form').submit(function() {
        for (var i = 0; i < editorInstances.length; i++){
          // If changed, write the changes to the text area
          if (changed[i])
            editorInstances[i].getTextArea().value = editorInstances[i].getValue();
          // If not changed, remove text area's name so it's not submitted
          else 
            $(editorInstances[i].getTextArea()).removeAttr('name');
        }
        // Empty changed so we can leave page
        changed = {}
        return true;
     });

      $('.copyGerman').click(function(){
        var editor = $(this).closest('td').data('editor');
        var germanEditor = Math.floor(editor/3)*3;
        editorInstances[editor].setValue(editorInstances[germanEditor].getValue());
      });

      $('.undo').click(function(){
        var editor = $(this).closest('td').data('editor');
        editorInstances[editor].undo();
        update();
      });

      $('.insertPlural').click(function(){
        var editor = $(this).closest('td').data('editor');
        var locale = $(this).closest('td').data('locale');
        var cases;
        switch (locale){
          case 'pl':
            cases = ["one","few","many","other"]; break;
          case 'cs':; case 'sk':
            cases = ["one","few","other"]; break;
          case 'de':; case 'en':; case 'fr':; case 'it':; case 'nl':; case 'es':
            cases = ["one","other"]; break;
        }
        editorInstances[editor].focus();
        editorInstances[editor].replaceSelection("\n{VARIABLE, plural,\n  " + cases.join(" {}\n  ") + " {}\n}\n", "around");
        update();
        return false;
      })

      $('.insertSelect').click(function(){
        var editor = $(this).closest('td').data('editor');
        editorInstances[editor].focus();
        editorInstances[editor].replaceSelection("\n");
        editorInstances[editor].replaceSelection("{VARIABLE, select,\n  case1 {}\n  case2 {}\n  other {}\n}\n","around");
        update();
        return false;
      });

      window.onbeforeunload = function() {
          return needsSave() ? 'Sie haben nicht gespeicherte Änderungen!' : null;
      }

      $(window).scroll(function(){
        $('.fixedHeader').css('left',-window.pageXOffset)
      });

      $('.hideThis').click(function(){
        $(this).closest('tr').hide();
        return false;
      });

      $('#showAll').click(function(){
        $('tr').show();
        return false;
      });

      $('#close').click(function(){
        window.close();
        return false;
      });

    })
  </script>    
</body>
</html>