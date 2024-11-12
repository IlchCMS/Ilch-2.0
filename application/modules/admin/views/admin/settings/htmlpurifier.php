<h1><?= $this->getTrans('htmlPurifierSettings') ?></h1>
<div id="htmlPurifierOwnDomain" class="row mb-3">
    <label class="col-xl-2 col-form-label" for="ownDomain">
        <?= $this->getTrans('htmlPurifierOwnDomain') ?>:
    </label>
    <div class="col-xl-4">
        <input type="text"
               class="form-control"
               id="ownDomain"
               name="ownDomain"
               value="<?= $this->escape($this->get('domain')) ?>"
               disabled />
        <div id="htmlPurifierOwnDomainHelp" class="form-text"><?= $this->getTrans('htmlPurifierOwnDomainHelp') ?></div>
    </div>
</div>
<div id="htmlPurifierDomains" class="row mb-3">
    <label class="col-xl-2 col-form-label" for="domain">
        <?= $this->getTrans('htmlPurifierDomains') ?>:
    </label>
    <div class="col-xl-4">
        <div class="input-group">
            <input type="url"
                   class="form-control"
                   id="domain"
                   name="domain"
                   pattern="https://.*" />
            <button class="btn btn-outline-secondary" type="button" id="addDomain"><?= $this->getTrans('htmlPurifierAddDomain') ?></button>
        </div>
        <div id="htmlPurifierDomainsHelp" class="form-text"><?= $this->getTrans('htmlPurifierDomainsHelp') ?></div>
    </div>
</div>
<div id="htmlPurifierUrlsConsideredSafe" class="row mb-3">
    <label class="col-xl-2 col-form-label">
        <?= $this->getTrans('htmlPurifierUrlsConsideredSafe') ?>:
    </label>
    <div class="col-xl-4">
        <ul class="list-group" id="domainList">
            <?php foreach ($this->get('additionalDomains') as $index => $domain) : ?>
                <li class="list-group-item list-group-item-info" id="additionalDomain-<?= $index ?>"><?= $this->escape($domain) ?><i class="delete text-danger float-end fa-regular fa-trash-can"></i></li>
            <?php endforeach; ?>
            <?php foreach ($this->get('urlsConsideredSafe') as $url) : ?>
                <li class="list-group-item"><?= $this->escape($url) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<form id="formDomains" method="post">
    <?=$this->getTokenField() ?>
    <?=$this->getSaveBar() ?>

    <?php foreach ($this->get('additionalDomains') as $index => $domain) : ?>
        <input type="hidden" class="additionalDomains" name="additionalDomains[]" id="input-additionalDomain-<?= $index ?>" value="<?= $domain ?>" />
    <?php endforeach; ?>
</form>

<script>
    document.getElementById("addDomain").addEventListener("click", () => {
        let domainInput = document.getElementById("domain");

        if (domainInput.value !== "" && domainInput.reportValidity()) {
            let domain = domainInput.value.replace(/^https?:\/\//, '');
            let listOfAdditionalDomains = document.getElementsByClassName("additionalDomains");

            for (let index = 0; index < listOfAdditionalDomains.length; index++) {
                if (listOfAdditionalDomains[index].value === domain) {
                    alert("<?= $this->getTrans('htmlPurifierDomainAlreadyAdded') ?>");
                    return;
                }
            }

            let randomIdPart = Math.random().toString(16).slice(2);
            let domainList = document.getElementById("domainList");
            let listElementDomain = document.createElement("li");
            let deleteDomainButton = document.createElement("i");
            let formDomains = document.getElementById("formDomains");
            let hiddenInputDomains = document.createElement("input");

            listElementDomain.className = "list-group-item list-group-item-info";
            listElementDomain.setAttribute("id", "additionalDomain-" + randomIdPart);
            listElementDomain.append(domain);
            deleteDomainButton.setAttribute("class", "delete text-danger float-end fa-regular fa-trash-can");
            deleteDomainButton.addEventListener("click", () => deleteDomain(deleteDomainButton));
            listElementDomain.appendChild(deleteDomainButton);
            domainList.prepend(listElementDomain);

            hiddenInputDomains.type = "hidden";
            hiddenInputDomains.className = "additionalDomains";
            hiddenInputDomains.name = "additionalDomains[]";
            hiddenInputDomains.id = "input-additionalDomain-" + randomIdPart;
            hiddenInputDomains.value = domain;
            formDomains.appendChild(hiddenInputDomains);

            domainInput.value = '';
        }
    });

    let deleteDomainButtons = document.getElementsByClassName("delete");

    Array.from(deleteDomainButtons).forEach(function(deleteDomainButton) {
        deleteDomainButton.addEventListener("click", () => deleteDomain(deleteDomainButton));
    });

    function deleteDomain(deleteDomainButton)
    {
        document.getElementById("input-" + deleteDomainButton.closest("li").id).remove();
        deleteDomainButton.closest("li").remove();
    }
</script>
