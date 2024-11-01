const {__} = wp.i18n;
const {Component, Fragment, createRef} = wp.element;
const {Placeholder, Spinner, PanelBody, SelectControl, TextControl, Button, ToolbarGroup} = wp.components;
const {InspectorControls, BlockControls, AlignmentToolbar} = wp.blockEditor;

import './style.scss';

class Edit extends Component {

    state = {
        htmlView: '',
        stationInit: false,
        loadingStationView: true,
        edit: '' === this.props.attributes.id,
    };

    componentDidMount() {
        const {attributes} = this.props;

        if (attributes.id && this.state.htmlView === '') {
            this.getStationView();
        }

        this.initPlayer();

    }

    async getStationView() {
        const {attributes} = this.props;

        const station_view = await wp.apiFetch({
            path: 'wptv/v1/player/' + attributes.id,
        });

        this.setState({
            htmlView: station_view.success !== undefined && station_view.success === true ? station_view.data : '',
            loadingStationView: false
        });

        this.initPlayer();
    }

    componentDidUpdate() {
        const {attributes} = this.props;

        if (attributes.id && this.state.htmlView === '') {
            this.getStationView();
        }

        if (this.state.htmlView !== '' && !this.state.stationInit) {
            this.setState({
                stationInit: true,
            })
        }

        this.initPlayer();
    }

    initPlayer() {
        new MediaElementPlayer('wptv_media_player', {
            videoWidth: '100%',
            videoHeight: '100%',
        });
    }

    render() {
        const {attributes, setAttributes} = this.props;
        const {htmlView, loadingStationView, edit} = this.state;


        return (
            <Fragment>
                <InspectorControls>

                    <PanelBody title={__('Player Settings', 'wp-radio')}>

                        <TextControl
                            label={__('Channel ID', 'wp-radio')}
                            value={attributes.id}
                            onChange={(newValue) => {

                                setAttributes({
                                    id: '' !== newValue ? parseInt(newValue) : '',
                                });

                            }}
                        />

                        <Button
                            label="Done"
                            isPrimary={true}
                            disabled={attributes.id === ''}
                            onClick={() => {
                                this.setState({
                                    edit: false,
                                    htmlView: '',
                                    stationInit: false,
                                    loadingStationView: true,
                                });
                            }
                            }
                        >Done</Button>

                    </PanelBody>
                </InspectorControls>

                <BlockControls>
                    <Button
                        className='components-toolbar'
                        icon='edit'
                        onClick={() => {
                            this.setState({
                                edit: true,
                            });

                            setAttributes({
                                id: '',
                            });
                        }
                        }
                    >Change Station</Button>

                </BlockControls>

                {
                    edit || !attributes.id ?
                        <Placeholder
                            icon="controls-play"
                            label={__('TV Player', 'wp-radio')}>
                            <p style={{
                                width: '100%',
                                margin: 0
                            }}>Enter the ID of the TV channel in the <strong>Channel ID</strong> input field.</p>

                            <TextControl
                                label={__('Channel ID', 'wp-radio')}
                                value={attributes.id}
                                onChange={(newValue) => {

                                    this.setState({
                                        edit: true,
                                    });

                                    setAttributes({
                                        id: '' !== newValue ? parseInt(newValue) : '',
                                    });

                                }}
                            />

                            <Button
                                label="Done"
                                disabled={attributes.id === ''}
                                isPrimary={true}
                                onClick={() => {
                                    this.setState({
                                        edit: false,
                                        htmlView: '',
                                        stationInit: false,
                                        loadingStationView: true,
                                    });
                                }
                                }
                            >Done</Button>

                        </Placeholder>
                        :
                        <Fragment>
                            {
                                loadingStationView ?
                                    <Placeholder
                                        icon="controls-play"
                                        label={__('TV Player', 'wp-radio')}>
                                        <Spinner/>
                                    </Placeholder>
                                    :
                                    <div style={{
                                        paddingTop: '1px',
                                        textAlign: attributes.alignment
                                    }} dangerouslySetInnerHTML={{__html: htmlView}}/>
                            }
                        </Fragment>
                }

            </Fragment>
        )
    }
}

export default Edit;