<style>
.key-shortcut {
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -o-border-radius: 4px;
    -khtml-border-radius: 4px;
    display: inline-block;
    padding: 3px 4px 3px 4px;
    line-height: 14px;
    background-color: #fcfcfc;
    border: solid 1px #ccc;
    border-bottom-color: #bbb;
    box-shadow: inset 0 -1px 0 #bbb;
    min-width: 24px;
    text-align: center;
}
</style>

<legend><?=$this->getTrans('keyboardShortcuts') ?></legend>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-2">
            <col />
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('shortcut') ?></th>
                <th><?=$this->getTrans('shortcutOption') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="key-shortcut">ALT</span> + <span class="key-shortcut">A</span></td>
                <td><?=$this->getTrans('shortcutArticle') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut">ALT</span> + <span class="key-shortcut">U</span></td>
                <td><?=$this->getTrans('shortcutUser') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut">ALT</span> + <span class="key-shortcut">S</span></td>
                <td><?=$this->getTrans('shortcutSettings') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut">ALT</span> + <span class="key-shortcut">H</span></td>
                <td><?=$this->getTrans('shortcutPHPInfo') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut">ALT</span> + <span class="key-shortcut">K</span></td>
                <td><?=$this->getTrans('shortcutKeybord') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut">ALT</span> + <span class="key-shortcut">I</span></td>
                <td><?=$this->getTrans('shortcutOfficialSite') ?></td>
            </tr>        
        </tbody>
    </table>
</div>
