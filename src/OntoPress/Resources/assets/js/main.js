// setup an "Weitere Ontologie hochladen" link
var $addTagLink = jQuery('<a href="#" class="add_tag_link">Weitere Ontology hochladen</a>');
var $newLinkLi = $addTagLink;

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $collectionHolder = jQuery('#ontologyAddType_ontologyFiles');

    $collectionHolder.append($newLinkLi);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form
        addTagForm($collectionHolder, $newLinkLi);
    });


});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype
    var prototype = $collectionHolder.data('prototype');

    // Set '$$name$$' in the prototype's HTML to Null
    var newForm = prototype.replace(/__name__/g, "");
    var newForm = newForm.replace("label__", "");
    //var newForm = newForm.deleteTHead();

    var $newFormLi = jQuery('<tr></tr>').append(newForm);

    // also add a remove button
    $newFormLi.append('<a href="#" class="remove-tag">x</a>');

    //Set the new "Duchsuchen" Button above the "Weitere Ontologie hochladen" Button
    $newLinkLi.before($newFormLi);

    // handle the removal
    jQuery('.remove-tag').click(function(e) {
        e.preventDefault();

        jQuery(this).parent().remove();

        return false;
    });
}
