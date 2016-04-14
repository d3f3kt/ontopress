// setup an "Weitere Ontologie hochladen" link

jQuery(document).ready(function () {
    if (uploadFilesCollectionHolder = jQuery('#ontologyAddType_ontologyFiles')) {
        addLinkToOntologyUploadFiles(uploadFilesCollectionHolder);
    }

    if (formFieldCheckboxes = jQuery('#createFormType_ontologyFields')) {
        addSelectAllLink(formFieldCheckboxes);
    }
});

function addSelectAllLink(formFieldCheckboxes)
{
    selectLink = jQuery('<a href="#">Alle auswählen</a>');
    deselectLink = jQuery('<a href="#">Alle abwählen</a>');

    formFieldCheckboxes.prepend(deselectLink);
    formFieldCheckboxes.prepend(jQuery("<span>|</span>"));
    formFieldCheckboxes.prepend(selectLink);

    selectLink.on('click', function(e) {
        e.preventDefault();

        jQuery(this).parent().find('input').each(function() {
            this.checked = true;
        });
    });

    deselectLink.on('click', function(e) {
        e.preventDefault();
        jQuery(this).parent().find('input').each(function() {
            this.checked = false;
        });
    });
}

function addLinkToOntologyUploadFiles(collectionHolder)
{
    addTagLink = jQuery('<a href="#" class="add_tag_link">Weitere Ontology hochladen</a>');
    collectionHolder.append(addTagLink);

    addTagLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form
        addTagForm(collectionHolder, addTagLink);
    });
}

function addTagForm(collectionHolder, addTagLink)
{
    // Get the data-prototype
    var prototype = collectionHolder.data('prototype');

    // Set '$$name$$' in the prototype's HTML to Null
    var newForm = prototype.replace(/__name__/g, "");
    newForm = newForm.replace("label__", "");
    newForm = jQuery(newForm);
    newForm.find("th").remove();
    newForm.find("#ontologyAddType_ontologyFiles__file").after('<a href="#" class="remove-tag">[X]</a>');

    collectionHolder.children('tbody').append(newForm);

    // handle the removal
    jQuery('.remove-tag').click(function (e) {
        e.preventDefault();

        jQuery(this).parent().parent().parent().parent().parent().parent().remove();

        return false;
    });
}
