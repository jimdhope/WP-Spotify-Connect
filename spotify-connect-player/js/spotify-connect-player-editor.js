const { registerBlockType } = wp.blocks;
const { createElement } = wp.element;
const { InspectorControls, PanelColorSettings } = wp.blockEditor;
const { SelectControl, PanelBody } = wp.components;

registerBlockType( 'spotify-connect/player', {
    title: 'Spotify Connect Player',
    icon: 'spotify',
    category: 'widgets',
    attributes: {
        colorScheme: {
            type: 'string',
            default: '#181818',
        },
        prevIcon: {
            type: 'string',
            default: 'default-prev',
        },
        playIcon: {
            type: 'string',
            default: 'default-play',
        },
        nextIcon: {
            type: 'string',
            default: 'default-next',
        },
    },
    edit: function( { attributes, setAttributes } ) {
        const { colorScheme, prevIcon, playIcon, nextIcon } = attributes;

        const renderIcon = (iconKey) => {
            switch (iconKey) {
                case 'default-prev': return createElement( 'svg', { role: 'img', height: '16', width: '16', viewBox: '0 0 16 16', fill: 'currentColor' }, createElement( 'path', { d: 'M3.3 1a.7.7 0 01.7.7v6.6a.7.7 0 01-.7.7H1.7a.7.7 0 01-.7-.7V1.7a.7.7 0 01.7-.7h1.6zM15 7.3a.7.7 0 00-.7-.7H5.7a.7.7 0 00-.7.7v1.4a.7.7 0 00.7.7h8.6a.7.7 0 00.7-.7V7.3z' } ) );
                case 'default-play': return createElement( 'svg', { role: 'img', height: '16', width: '16', viewBox: '0 0 16 16', fill: 'currentColor' }, createElement( 'path', { d: 'M3 1.713a.7.7 0 011.05-.607l10.89 6.288a.7.7 0 010 1.214L4.05 14.894A.7.7 0 013 14.288V1.713z' } ) );
                case 'default-next': return createElement( 'svg', { role: 'img', height: '16', width: '16', viewBox: '0 0 16 16', fill: 'currentColor' }, createElement( 'path', { d: 'M12.7 1a.7.7 0 00-.7.7v6.6a.7.7 0 00.7.7h1.6a.7.7 0 00.7-.7V1.7a.7.7 0 00-.7-.7h-1.6zM1 7.3a.7.7 0 01.7-.7h8.6a.7.7 0 01.7.7v1.4a.7.7 0 01-.7.7H1.7a.7.7 0 01-.7-.7V7.3z' } ) );
                default: return ''; // Fallback to empty string if somehow an invalid iconKey is passed
            }
        };

        return createElement(
            wp.element.Fragment,
            null,
            createElement(
                InspectorControls,
                null,
                createElement(
                    PanelColorSettings,
                    {
                        title: 'Color Settings',
                        initialOpen: true,
                        colorSettings: [
                            {
                                value: colorScheme,
                                onChange: ( newColorScheme ) => setAttributes( { colorScheme: newColorScheme } ),
                                label: 'Player Background Color',
                            },
                        ],
                    }
                )
            ),
            createElement(
                'div',
                { className: 'spotify-player-editor-preview', style: { backgroundColor: colorScheme } },
                createElement(
                    'div',
                    { className: 'player-left' },
                    createElement(
                        'div',
                        { className: 'album-art' },
                        createElement( 'img', { src: 'https://via.placeholder.com/60', alt: 'Album Art' } )
                    ),
                    createElement(
                        'div',
                        { className: 'track-info' },
                        createElement( 'h3', null, 'Track Title' ),
                        createElement( 'p', null, 'Artist Name' )
                    )
                ),
                createElement(
                    'div',
                    { className: 'player-center' },
                    createElement(
                        'div',
                        { className: 'controls' },
                        createElement( 'button', null, renderIcon('default-prev') ),
                        createElement( 'button', null, renderIcon('default-play') ),
                        createElement( 'button', null, renderIcon('default-next') )
                    ),
                    createElement(
                        'div',
                        { className: 'timeline' },
                        createElement( 'span', null, '0:00' ),
                        createElement( 'input', { type: 'range', value: '0', max: '100' } ),
                        createElement( 'span', null, '3:00' )
                    )
                )
            )
        );
    },
    save: function() {
        return null;
    },
} );
