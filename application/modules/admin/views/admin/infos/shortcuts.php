<legend><?=$this->getTrans('keyboardBackendShortcuts') ?></legend>
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
                <td><span class="key-shortcut"><?=$this->getTrans('keyAlt') ?></span> + <span class="key-shortcut">A</span></td>
                <td><?=$this->getTrans('shortcutArticle') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyAlt') ?></span> + <span class="key-shortcut">U</span></td>
                <td><?=$this->getTrans('shortcutUser') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyAlt') ?></span> + <span class="key-shortcut">S</span></td>
                <td><?=$this->getTrans('shortcutSettings') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyAlt') ?></span> + <span class="key-shortcut">H</span></td>
                <td><?=$this->getTrans('shortcutPHPInfo') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyAlt') ?></span> + <span class="key-shortcut">K</span></td>
                <td><?=$this->getTrans('shortcutKeybord') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyAlt') ?></span> + <span class="key-shortcut">I</span></td>
                <td><?=$this->getTrans('shortcutOfficialSite') ?></td>
            </tr>
        </tbody>
    </table>
</div>

<legend><?=$this->getTrans('keyboardShortcutsCustumCSS') ?></legend>
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
                <td><span class="key-shortcut">F11</span></td>
                <td><?=$this->getTrans('shortcutCustumCSSFullscreen') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyTab') ?></span></td>
                <td><?=$this->getTrans('shortcutCustumCSSLineRight') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyShift') ?></span> + <span class="key-shortcut"><?=$this->getTrans('keyTab') ?></span></td>
                <td><?=$this->getTrans('shortcutCustumCSSLineLeft') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyCtrl') ?></span> + <span class="key-shortcut"><?=$this->getTrans('keyShift') ?></span> + <span class="key-shortcut">&uarr;</span> / <span class="key-shortcut">&darr;</span></td>
                <td><?=$this->getTrans('shortcutCustumCSSMoveUpOrDown') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyCtrl') ?></span> + <i class="fa fa-mouse-pointer"></i></td>
                <td><?=$this->getTrans('shortcutCustumCSSMultiEdit') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyCtrl') ?></span> + <span class="key-shortcut"><?=$this->getTrans('keyAlt') ?></span> + <span class="key-shortcut">C</span></td>
                <td><?=$this->getTrans('shortcutCustumCSStoggleComment') ?></td>
            </tr>
            <tr>
                <td><span class="key-shortcut"><?=$this->getTrans('keyCtrl') ?></span> + <span class="key-shortcut"><?=$this->getTrans('keySpace') ?></span></td>
                <td><?=$this->getTrans('shortcutCustumCSSAutocomplete') ?></td>
            </tr>
        </tbody>
    </table>
</div>
