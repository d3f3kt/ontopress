# Project Name

This Wordpress plugin allows the retrieval of information for places and buildings,
using html forms, which  evaluate ontologys and save the information in semantic web graphs.

With the help of this plugin, individual forms and and matching ressources, can be created from the ontologys.

## Installation

Navigate to your plugins tab on wordpress. Choose "Add new" and choose "Upload".
Proceed to select the zip file containing OntoPress and click "Install now".
After the plugin was successfully installed, click "Activate now" and you can now use OntoPress.

## Usage

### Create and manage ontologys:
To add an ontology, you either choose "hinzufügen" below the ontology counter on the dashboard,
or you navigate to the ontology management in the menu of the plugin and choose "Neu hinzufügen".
You will be redirected to the creation wizard, where you set the name of the ontology and upload the ontology itself.
You can upload multiple ontologies, by selecting "Weitere Ontology  hochladen".
Ontologys are only allowed to have a .ttl or .txt extension.
If the upload was successful, the ontology will be displayed on the ontology management page.
Created ontologies can be deleted, by choosing "löschen", below the desired ontology.
To see the connected forms of an ontology, click on the form count of the ontology.

### Creating forms

Forms are used to choose certain properties of an ontology and will be connected to it.

To add a form, you either choose "hinzufügen" below the form counter on the dashboard,
or you navigate to the form management in the menu of the plugin and choose "Neu hinzufügen".
You will be redirected to the form creation wizard, where you choose the ontology which the form will be added to.
Next you have to set the name of the form and choose any desired property that should be included.
You will be presented a generated twig code, which can be adjusted for personal needs.
Select "speichern" to connect the form to the ontology.
The created form will be displayed on the form management page, with the pattern "*ontology\form*".
Forms can be deleted, by choosing "löschen" below the form. If you want to adjust the twig code of an already
generated form, you can choose "bearbeiten" and you will be redirected to the twig code of that form.
When an ontology gets deleted, all connected forms are deleted as well.



## Credits


**Team Lead**

David Geistert

**Investigation**

Lukas Janik

**Modelling**

Christofer Meinecke

Dominik Michael

**Testing**

Noah Walle

**Implementation**

Christofer Meinecke

Dominik Michael

**Dokumentation**

Lukas Rosenkranz

**Quality Assurance**

Enno Jertschat


## License

TODO: Write license