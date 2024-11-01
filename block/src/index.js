const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;

import Edit from "./Edit";

registerBlockType('wptv/tv-player', {
    title: __('TV Player', 'wp-radio'),
    icon: 'video-alt3',
    category: 'media',
    attributes: {
        id: {
            type: 'number',
            default: '',
        },
        alignment: {
            type: 'string',
        },
    },

    supports: {
        align: ['center', 'wide', 'full'],
    },

    edit: Edit,


    save: ({attributes}) => {
        const {alignment, id} = attributes;

        return (
            <div style={{textAlign: alignment}}>{`[wptv_player id=${id}]`}</div>
        )
    }
});