<link href="<?=$this->getModuleUrl('static/css/coustomcss.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/codemirror/codemirror.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/codemirror/addon/fold/foldgutter.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/codemirror/addon/display/fullscreen.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/js/codemirror/addon/hint/show-hint.css') ?>" rel="stylesheet">

<legend><?=$this->getTrans('menuCoustomCSS') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <div class="col-lg-6">
            <textarea class="form-control" rows="20" id="coustomCSS" name="coustomCSS" placeholder="Code goes here..."><?=$this->escape($this->get('coustomCSS')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/codemirror.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/mode/css/css.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/keymap/sublime.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/selection/active-line.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/comment/comment.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/comment/continuecomment.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/hint/show-hint.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/hint/css-hint.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/edit/closebrackets.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/edit/matchbrackets.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/display/placeholder.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/display/fullscreen.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/display/palette.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/fold/foldgutter.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/fold/foldcode.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/fold/brace-fold.js') ?>"></script>
<script type="text/javascript" src="<?=$this->getModuleUrl('static/js/codemirror/addon/fold/comment-fold.js') ?>"></script>
<script type="text/javascript">
var editor = CodeMirror.fromTextArea(document.getElementById("coustomCSS"), {
    lineNumbers: true,
    autoCloseBrackets: true,
    styleActiveLine: true,
    matchBrackets: true,
    foldGutter: true,
    paletteHints: true,
    indentUnit: 4,
    keyMap: "sublime",
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
    viewportMargin: Infinity,
    extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Ctrl-Alt-C": "toggleComment",
        F11: function(cm) {
            cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        Esc: function(cm) {
            if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        }
    }
});
</script>
