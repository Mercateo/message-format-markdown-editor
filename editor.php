<?php 
  $NUM_PER_PAGE = 20;
  $BASENAMES = [
    'basket-strings',
    'basket-core',
    'b4s3 n4m3'
  ];
  $ALL_LOCALES = [
    ['Tschechisch','cs'],
    ['Deutsch','de'],
    ['Englisch','en'],
    ['Spanisch','es'],
    ['Französisch','fr'],
    ['Ungarisch','hu'],
    ['Italienisch','it'],
    ['Niederländisch','nl'],
    ['Polnisch','pl'],
    ['Slowakisch','sk']
  ];

  // REQUEST:
  $locales = ['de','en','fr'];
  $page = 0;
  $filter = [
    'baseNames' => ['basket-strings'],
    'value' => "test",
    'key' => null,
  ];
  if (count($filter['baseNames']) == count($BASENAMES))
    $filter['baseNames'] = null;

  function createSelect($columns, $filter){
    $conditions = [];
    if (isset($filter['baseName']) && $filter['baseName'] != null && $filter['baseName'] != null)
      $conditions[] = "fld_baseName = ".implode(mysql_real_escape_string($filter['baseName'])," OR fld_baseName = ");
    if (isset($filter['value']) && $filter['value'] != null && $filter['value'] != "")
      $conditions[] = "fld_value LIKE ".mysql_real_escape_string($filter['value']);
    if (isset($filter['key']) && $filter['key'] != null && $filter['key'] != "")
      $conditions[] = "fld_key LIKE ".mysql_real_escape_string($filter['key']);

    if (count($conditions) > 0)
      return "(SELECT ".mysql_real_escape_string($columns)." FROM RSDB_RESOURCE_FILE_ENTRIES WHERE ".join(" AND ",$conditions).")";
    else
      return "(SELECT ".mysql_real_escape_string($columns)." FROM RSDB_RESOURCE_FILE_ENTRIES)";
  }

  // // SELECT COUNT(value), language 
  // // FROM createSelect("fld_language, fld_value",$filter) 
  // // WHERE fld_language IN $locales 
  // // AND fld_value > '' 
  // // ORDER BY fld_language 
  // // GROUP BY fld_language
  // Existing translations for the current selection
  $progress = [
    'de' => 50,
    'en' => 41,
    'fr' => 48
  ];

  // // SELECT COUNT(DISTINCT fld_key) 
  // // FROM createSelect("fld_key",$filter) 
  // Number of keys for the current selection
  $totalEntries = 50;
  $totalPages = ceil($totalEntries/$NUM_PER_PAGE);


  // // SELECT key, baseName, comment 
  // // FROM createSelect("fld_key, fld_baseName, fld_comment",$filter) 
  // // ORDER BY fld_baseName, fld_key
  // // GROUP BY fld_key, fld_baseName
  // // LIMIT $request['page']*20, 20

  // // SELECT fld_value, fld_lastAuthor, fld_lastChanged
  // // FROM RSDB_RESOURCE_FILE_ENTRIES 
  // // WHERE fld_baseName = $entry['fld_baseName'] AND fld_key = $entry['fld_key']
  // // AND fld_language IN $locales
  // // ORDER BY fld_language

  // Array of keys, containing arrays of translations
  $entries = [
    [
      "key" => "basket.cluster_badge_description",
      "baseName" => "basket-strings",
      "comment" => "COLOR: [weiß|schwarz]",
      "translations" => [
        "de" => [
          "text" => "Ein Cluster enthält alle Artikel, die bestimmte Anforderungen erfüllen (z. B. Kopierpapier, DIN A4, 80 g/m², {COLOR}).",
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
      "key" => "basket.commit.general_terms_and_privacy",
      "baseName" => "basket-strings",
      "comment" => "AGB_URL\nDSE_LINK\nDSE_LINK\nDSE_LINK\nDSE_LINK\nDSE_LINK",
      "translations" => [
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
      "key" => "placedorder.print.hint.printing_guide",
      "baseName" => "basket-strings",
      "comment" => "OS: [mac|windows]",
      "translations" => [
        "de" => [
          "text" => "Sie können diese Seite ausdrucken, indem Sie die Druckfunktion Ihres Browsers verwenden ({OS, select, mac {CMD} other {STRG}}+P).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "30.04.13"
        ],
        "en" => [
          "text" => "You can print out this page by using the printing function of your browser ({OS, select, mac {CMD} other {STRG}}+P).",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "30.04.13"
        ]
      ]
    ],
    [
      "key" => "editor.test-string.messageformat.nomarkdown",
      "baseName" => "editor-test",
      "comment" => "FEATURES_URL: [http://mercateo.de/features]\nVARIABLE:   Diese Variable ist zum einbinden [Variable]\nUSER_GENDER:  [male|female]",
      "translations" => [
        "de" => [
          "text" => "Hallo\n{USER_GENDER, select,\n  male  {lieber User!}\n  other {liebe Userin!}\n}\n\nDies hier  ist ein ziemlich komplexer\nSatz. Ich versuche hier mal *alle* [Features]({FEATURES_URL})\nzu testen.\n\nWie viele Features haben wir? Ich glaube es  \n{FEATURES_COUNT, plural,\n    =0      {sind gar keine Features...}\n    one   {ist nur ein einziges.}\n    other {sind # insgesamt!}\n}\n\nDas ist ja schon mal ganz cool. Hier binden wir noch eine {VARIABLE} und noch eine {ANDERE_VARIABLE} ein.",
          "lastAuthor" => "Robert Bastian",
          "lastChanged" => "07.08.15"
        ]
      ]
    ]
  ];
?>
<!doctype html>
<head>
  <meta charset="UTF-8">
	<title>MessageFormat Live Preview</title>

  <!--Messageformat-->
	<script src='bower_components/messageformat/messageformat.js'></script>
  <script src="mercup-tools.js"></script>
  <? // Include the messageformat rules for all selected languages
  foreach ($locales as $locale): ?>
    <script src='bower_components/messageformat/locale/<?=substr($locale, 0, 2)?>.js'></script>
  <? endforeach; ?>

  <!--Codemirror-->
	<script src="bower_components/codemirror/lib/codemirror.js"></script>
	<link href="bower_components/codemirror/lib/codemirror.css" rel="stylesheet">
  <script src="bower_components/codemirror/addon/edit/matchbrackets.js"></script>
  <script src="bower_components/codemirror/addon/edit/closebrackets.js"></script>
  <script src="mercup-mode.js"></script>
  
  <!--Markdown-->
  <script src="bower_components/marked/marked.min.js"></script>
  
  <!---jQuery & Bootstrap-->
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css" rel="stylesheet">

  <style>
    body {
      min-width:<?=(1+count($locales))*400?>px;
    }
    #fixedHeader {
      table-layout:fixed;
      position:fixed;
      top:0px;
      z-index:10;
      min-width:<?=(1+count($locales))*400?>px;
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
  <form method="POST">
    <div class="modal fade" id='filterModal'>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Filter einstellen</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="locales">Sprachen</label>
              <select multiple class="form-control selectpicker" title='Keine' name='locales' data-selected-text-format="count > 5" data-live-search="true" data-max-options="5">
                <? foreach ($ALL_LOCALES as $locale): ?>
                  <option value='<?=$locale[1]?>' data-subtext="<?=$locale[1]?>" <?=in_array($locale[1], $locales) ? "selected" : ""?>><?=$locale[0]?></option>
                <? endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for='filter[baseNames]'>Base names</label>
              <select multiple class="form-control selectpicker" title='Alle' name='filter[baseNames]' data-selected-text-format="count > 5" data-live-search="true">
                <? foreach ($BASENAMES as $baseName): ?>
                  <option value='<?=$baseName?>' <?= ($filter['baseNames'] == null || $filter['baseNames'] === "" || in_array($baseName, $filter['baseNames'])) ? "selected" : ""?>><?=$baseName?></option>
                <? endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="valueSearch">Suche im Wert</label>
              <input type="text" name='filter[value]' class="form-control" id="valueSearch" value='<?= ($filter['value'] == null) ? "" : $filter['value']?>'>
              <p class="help-block"><a target='_blank' href='http://www.strassenprogrammierer.de/regular-expression-regex-praxis_tipp_597.html'>Reguläre Ausdrücke</a> unterstützt (z.b. <code>^$</code> für leere Strings)</p>

            </div>
            <div class="form-group">
              <label for="keySearch">Suche im Schlüssel</label>
              <input type="text" name='filter[key]' class="form-control" id="keySearch" value='<?= ($filter['key'] == null) ? "" : $filter['key']?>'>
              <p class="help-block"><a target='_blank' href='http://www.strassenprogrammierer.de/regular-expression-regex-praxis_tipp_597.html'>Reguläre Ausdrücke</a> unterstützt (z.b. <code>^$</code> für leere Strings)</p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
            <input type="submit" value='Suchen' id='filterSubmit' class="btn btn-primary"></button>
            <p id='saveWarning' class="help-block">Änderungen werden vor dem Suchen automatisch gespeichert.</p>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <input type='hidden' name='page' value='<?=$page?>'>
    <table class='table' id='fixedHeader'><thead>
      <tr>
        <th class='text-center' style='border-bottom:none;'>
          <div style='float:left;'>
            <input type='submit' id='save' class='btn' value='Speichern' data-default-style='btn-default' data-highlight-style='btn-primary'></input>
          </div>
          <button class='btn btn-default' id='filter'>Filter</button>
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
              <div class="progress-bar" data-loading-style='progress-bar-info' data-finished-style='progress-bar-success' data-locale='<?=$locale?>' style="width:<?=$progress[$locale]/$totalEntries*100?>%; min-width: 2em;">
            </div>
          </th>
        <? endforeach; ?>
      </tr>
      </thead>
    </table>
    <table class='table table-bordered' style="table-layout:fixed;height:100%;margin-top:50px"><tbody>
      <? foreach($entries as $row => $entry): ?>
        <tr data-editors='<?=$row*count($locales)?>'>
          <td>
            <div class='limit'>
              <h4 style='word-wrap: break-word;'><?=str_replace([".","_"], ["&shy.","&shy_"], $entry['key'])?></h4>
              <h4 style='float:left;margin:0px'>
                <small><?=$entry['baseName']?></small>
              </h4>
              <div style='float:right;'>
                <button class='btn btn-xs btn-default hideThis'>Ausblenden</button>
                <div class="btn-group btn-toggle"> 
                  <a class="btn btn-xs btn-default nohover code active">Code</a>
                  <a class="btn btn-xs btn-default nohover test">Test</a>
                </div>
              </div>
              <pre class='well well-sm comment'><?=($entry["comment"] == null || $entry["comment"] == "") ? "-\n" : $entry["comment"]?></pre>
            </div>
          </td>
          <? $col = 0; foreach($locales as $locale):?>
            <td data-editor='<?= $row*count($locales)+$col ?>' data-locale='<?=$locale?>'>
              <div class='limit editor'>
          	    <textarea name ='updatedValue[<?=$entry['baseName']?>+++<?=$entry['key']?>+++<?=$locale?>]' style='width:100%;height:100%'><?=isset($entry['translations'][$locale]) ? $entry['translations'][$locale]['text'] : ""?></textarea>
                <div class='actionButtons'>
                  <div class='btn-group' data-toggle='buttons'>
                    <button class='btn btn-sm btn-default undo' data-toggle="tooltip" title="Rückgängig">
                      <i class='fa fa-undo'></i>
                    </button> 
                  </div>
                  <br>
                  <div class='btn-group' data-toggle='buttons'>
                    <button class='btn btn-sm btn-default insertPlural <?= ($locale == 'hu') ? 'disabled' : ''?>' data-toggle="tooltip" title="Plural Syntax einfügen">
                      P
                    </button> 
                  </div>
                  <br>
                  <div class='btn-group' data-toggle='buttons'>
                    <button class='btn btn-sm btn-default insertSelect' data-toggle="tooltip" title="Select Syntax einfügen">
                      S
                    </button> 
                  </div>
                  <br>
                  <? /* Entfernen und Geprüft Checkboxes werden nicht benötigt
                  <div class='btn-group' data-toggle='buttons'>    
                    <label class='btn btn-sm btn-default' data-toggle="tooltip" title="Löschen">
                      <input type='checkbox' name='<?=$current['id']?>[<?=$locale?>][remove]' autocomplete='off'> 
                      <i class='fa fa-trash'></i>
                    </label>
                  </div>
                  <br>
                 <? if ($content['lastAuthor'] != null) :?>
                    <div class='btn-group' data-toggle='buttons'>
                      <label class='btn btn-sm btn-default btn-active-success' data-toggle="tooltip" title="Geprüft">
                        <input type='checkbox' name='<?=$current['id']?>[<?=$locale?>][confirm]' autocomplete='off'> 
                        <i class='fa fa-check'></i>
                      </label> 
                    </div>
                    <br>
                  <? endif; */ ?>
                  <? if ($col > 0): ?>
                    <div class='btn-group' data-toggle='buttons'>
                      <button class='btn btn-sm btn-default copyReference' data-toggle="tooltip" title="Deutsch&nbsp;übernehmen">
                        <i class='fa fa-copy'></i>
                      </label> 
                    </div>
                    <br>
                  <? endif; ?>
                </div>
                <div style='position:absolute; left:0px; bottom: 0px; z-index:5;'>
                  <? if (isset($entry['translations'][$locale])) :?>
                    <i class='fa fa-edit' style='padding:10px 8px; color: #BBB;' data-color='#BBB' data-changed-color='#2e6da4' data-toggle="tooltip" title="Letze Änderung:<br/><?=$entry['translations'][$locale]['lastChanged']?><br/><?=$entry['translations'][$locale]['lastAuthor']?>" data-html='true'></i>
                  <? else: ?>
                    <i class='fa fa-edit' style='padding:10px 8px; color: #f7f7f7;' data-color='#f7f7f7' data-changed-color='#2e6da4'></i>
                  <? endif;?>
                </div>
              </div>
              <div class='limit preview' style='display:none;'></div>
            </td>
          <? $col++; endforeach; ?>
        </tr>
      <? endforeach; ?>
    </tbody></table>
  </form>
  <div class='text-center nav'>
    <p>Einträge <?=$request['page']*$NUM_PER_PAGE+1?> bis <?=min(($request['page']+1)*$NUM_PER_PAGE,$totalEntries)?> von <?=$totalEntries?></p>
    <ul class="pagination pagination-sm">
      <? if ($request['page'] == 0): ?>
        <li class='disabled'> <span aria-hidden="true">&laquo;</span></li>
      <? else: ?>
        <li>
          <a href="?page=<?= ($request['page'] - 1)?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
      <? endif; ?>
      <? for ($i = max(0,$request['page']-5); $i <= min($totalPages-1,$request['page']+5); $i++): ?>
        <? if ($i == $request['page']): ?>
          <li class='active'><a href="#"><?=($i+1)?></a>
        <? else: ?>
          <li><a href="?page=<?=$i?>"><?=($i+1)?></a></li>
        <? endif; ?>
      <? endfor; ?>
      <? if ($request['page'] == $totalPages - 1): ?>
        <li class='disabled'> <span aria-hidden="true">&raquo;</span></li>
      <? else: ?>
        <li>
          <a href="?page=<?= ($request['page'] + 1)?>" aria-label="Previous">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      <? endif; ?>
    </ul>
  </div>
	<script type="text/javascript">
    $(function(){

      var changed = {}

      // Initialising the CodeMirror editors from the text areas
      var editorInstances = $('textarea').map(function(index,ta){
        var ta = CodeMirror.fromTextArea(ta, {
          mode: "mercup.js",
          lineNumbers: true,
          styleActiveLine: true,
          lint: true,
          lineWrapping: true,
          viewportMargin: Infinity,
          autoCloseBrackets: true,
          matchBrackets: true,
          extraKeys: {
            "Alt-T": function(cm) {
              toggleTestForButton($(cm.getTextArea()).closest('tr').find('.btn-toggle'));
            }
          },

        });
        // On changes we need to figure out if the content actually changed, then set the edited flag 
        // update the save button and progress bars
        ta.on('changes',function(ta){
          var td = $(ta.getTextArea()).closest('td');
          if (ta.getValue() == ta.getTextArea().value){
            ta.markClean();
            td.find('.fa-edit').css('color',td.find('.fa-edit').data('color'));
          }
          else {
            td.find('.fa-edit').css('color',td.find('.fa-edit').data('changed-color'));
          }
          changed[td.data('editor')] = !ta.isClean()
          updateProgressBarsAndSaveButton();
        })
        return ta;
      });
      
      // Initialising tooltips
      $('[data-toggle="tooltip"]').tooltip({container: "body", placement:"left"});

      // This button toggles all test/code buttons
      $('#toggleAll').click(function(){
        $(this).find('.btn').toggleClass('active');
        // These toggles are set differently
        var opp = $(this).find('.active').hasClass('test') ? 'code' : 'test';
        $('.btn-toggle').filter(function(i, e){ return $(e).find('.'+opp).hasClass('active'); }).each(function(i,e){ toggleTestForButton($(e)); });
      })

      // When clicked anywhere, the toggle toggles
      $('.btn-toggle').click(function() {
        toggleTestForButton($(this));
      });

      // Toggles a single pair of buttons
      function toggleTestForButton(btnToggle){
        // Switching styles
        btnToggle.find('.btn').toggleClass('active');  
        // Locating the row and comment
        var row = btnToggle.closest('tr');
        var comment = row.find('.comment').html();
        // If we switched to test, generate the previews
        if (btnToggle.find('.active').hasClass('test'))
          // For each cell in the row ...
          row.find('td').not(':first').each(function(index,td){
            // ... generate the preview from the editor's content, into the preview, using the comment and the cell's locale
            renderPreview(editorInstances[$(td).data('editor')].getValue(),$(td).find('.preview'),comment,$(td).data('locale'));
          });
        row.find('.editor, .preview').toggle();
      }

      // Renders a preview of 'from' into the object 'to', with comment 'comment' and locale 'locale'
      var renderPreview = function(from,to,comment,locale){
        if (from.match(/^\s*$/)){
          $(to).html('<div class="panel panel-warning"><div class="panel-heading">Kein String</div></div>');          
        }
        else {
          try {
            var messageFormat = toMessageFormat(from);
            try {
              var combinations = samplesForString(locale,messageFormat,comment);
              $(to).html("");
              var compiled = (new MessageFormat(locale)).compile(messageFormat);
              for (var i = 0; i < combinations.length; i++)
                $(to).append("<div class='well well-sm'>" + compiled(combinations[i]) + "</div>");
            }
            catch (err){
              $(to).html('<div class="panel panel-danger"><div class="panel-heading">MessageFormat Fehler</div><div class="panel-body">'+err.message+'<br>Line: '+err.line+'</div></div>')
            }
          }
          catch (err){
            $(to).html('<div class="panel panel-danger"><div class="panel-heading">Markdown Fehler</div><div class="panel-body">'+err.message+'</div></div>')
          }
        }
      }

      // Progress bar logic:
      // Array storing the global progress for each locale
      var progress = <?=json_encode($progress)?>;
      // Total amount of keys in selection
      var TOTAL_ENTRIES = <?=json_encode($totalEntries)?>;
      // These editors were initally empty
      var initiallyEmpty = editorInstances.map(function(i,e){ return /^\s*$/.test(e.getValue()); });

      function updateProgressBarsAndSaveButton(){
        $('.progress-bar').each(function(index,pb){
          pb = $(pb);
          var done = progress[pb.data('locale')];
          // Only if an editor was initially empty and has changed does this count as an additional completion
          for (var i = index; i < editorInstances.length; i += <?=count($progress)?>)
            if (initiallyEmpty[i] && !!changed[i])
              done += 1;
          pb.removeClass(pb.data('loading-style')).removeClass(pb.data('finished-style'));
          pb.addClass(pb.data((done == TOTAL_ENTRIES) ? 'finished-style' : 'loading-style'));
          pb.html(done+"/"+TOTAL_ENTRIES);
          pb.css('width',done/TOTAL_ENTRIES*100+'%')
        });
        // Update the submit button
        var needsSave = false;
        for (var key in changed) {
          needsSave = needsSave || changed[key];
        }
        var sb = $('#save');
        sb.addClass(sb.data('default-style')+" "+sb.data('highlight-style')).removeClass(needsSave ? sb.data('default-style') : sb.data('highlight-style'));
      }
      updateProgressBarsAndSaveButton();

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
        var sb = $('#save');
        sb.addClass(sb.data('default-style')).removeClass(sb.data('highlight-style'));
        return true;
     });

      // Copy source text into this editor
      $('.copyReference').click(function(){
        var editor = $(this).closest('td').data('editor');
        var referenceEditor = $(this).closest('tr').data('editors');
        editorInstances[editor].setValue(editorInstances[referenceEditor].getValue());
        return false;
      });

      // Undo
      $('.undo').click(function(){
        var editor = $(this).closest('td').data('editor');
        editorInstances[editor].undo();
        updateProgressBarsAndSaveButton();
        return false;
      });

      // Insert a plural construct into this editor
      $('.insertPlural').click(function(){
        var editor = $(this).closest('td').data('editor');
        var locale = $(this).closest('td').data('locale');
        var cases;
        // Different plural cases for different locales
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
        return false;
      });

      // Insert select construct into this editor
      $('.insertSelect').click(function(){
        var editor = $(this).closest('td').data('editor');
        editorInstances[editor].focus();
        editorInstances[editor].replaceSelection("\n{VARIABLE, select,\n  case1 {}\n  case2 {}\n  other {}\n}\n","around");
        return false;
      });

      // Warning before leaving without saving
      window.onbeforeunload = function() {
          return $('#save').hasClass($('#save').data('highlight-style')) ? 'Sie haben nicht gespeicherte Änderungen!' : null;
      }

      // Scrolling header in x-direction
      $(window).scroll(function(){
        $('#fixedHeader').css('left',-window.pageXOffset)
      });

      // Hiding a row
      $('.hideThis').click(function(){
        $(this).closest('tr').hide();
        return false;
      });

      // Unhiding all rows
      $('#showAll').click(function(){
        $('tr').show();
        return false;
      });

      // Smart nav links
      $('.nav').find('a').click(function(){
        if ($('#save').hasClass($('#save').data('highlight-style'))){
          // We need to save, so send a POST to change pages!
          $('[name=page]').val($(this).html());
          $('form').submit();
          return false;
        }
      });

      $('#filter').click(function(){
        if ($('#save').hasClass($('#save').data('highlight-style')))
          $('#saveWarning').show();
        else
          $('#saveWarning').hide();
        $('#filterModal').modal();
        return false;
      });

      $('.selectpicker').selectpicker({
        showSubtext: true
      });
    })
  </script>    
</body>
</html>