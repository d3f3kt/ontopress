// setup an "Weitere Ontologie hochladen" link
var addTagLink = jQuery('<a href="#" class="add_tag_link">Weitere Ontology hochladen</a>');

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    var collectionHolder = jQuery('#ontologyAddType_ontologyFiles');

    collectionHolder.append(addTagLink);

    addTagLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form
        addTagForm(collectionHolder, addTagLink);
    });


});

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
