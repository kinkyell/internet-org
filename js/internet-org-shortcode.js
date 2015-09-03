/**
 * Callback function for conditional login in Shortcake/Shortcode UI
 *
 * @see https://github.com/fusioneng/Shortcake/wiki/Event-Attribute-Callbacks
 *
 * @param changed
 * @param collection
 * @param shortcode
 */
function updateLinkToPressFieldListener( changed, collection, shortcode ) {

    /**
     * Helper function to grab a field object
     * @param name
     */
    function attributeByName(name) {
        return _.find(
            collection,
            function( viewModel ) {
                return name === viewModel.model.get('attr');
            }
        );
    }


    var updatedVal = changed.value,
        Imagefield = attributeByName( 'image' ),
        Titlefield = attributeByName( 'link_title'),
        Descfield = attributeByName( 'link_desc' );


    //default for conditional fields
    Imagefield.$el.hide();
    Titlefield.$el.show();
    Descfield.$el.show();

    //The real conditional logic stuff
    if ( updatedVal === 'panel' ) {
        Imagefield.$el.show();
        Titlefield.$el.hide();
        Descfield.$el.hide();
    } else if ( updatedVal === 'titled' ) {
        Imagefield.$el.hide();
        Titlefield.$el.show();
        Descfield.$el.show();
    }
}
//Call our listener on the link to press select button
wp.shortcake.hooks.addAction( 'io-custom-link.link_to_press', updateLinkToPressFieldListener );