'use strict';

window.LayoutView = React.createBackboneClass({
    mixins: [BaseView],
    getInitialState: function getInitialState() {
        return {
            mainView: null,
            mainParams: null,
            dialogView: null,
            dialogParams: null,
            dialogSize: 'small',
            dialogCustomClass: '',
            loading: false
        };
    },
    componentDidMount: function componentDidMount() {
        var self = this;
        /**
         * set main view
         * @param  {React} view
         * @param  {Backbone.Collection} or Backbone.Model} params
         * @param  {showMenu: {boolean}
         */
        this.subscribe("loadView", function (view, params, options) {
            self.setState({
                mainView: view,
                mainParams: params
            });
        });
        /**
         * show a dialog
         * @param  {React} view
         * @param  {Backbone.Collection} or Backbone.Model} params
         * @param  {size: {String} size Foundation Reveal Size Value: tiny, small, large, full} options
         */
        this.subscribe("showDialog", function (view, params, options) {
            self.setState({
                dialogView: view,
                dialogParams: params
            });

            self.setState({
                dialogSize: _.has(options, 'size') ? options.size : 'small',
                dialogCustomClass: _.has(options, 'customClass') ? options.customClass : ''
            });
        });

        /**
         * loading control
         */
        var loadingCount = 0,
            loadingDelay = 500,
            loadingTimeout = null;
        this.subscribe('showLoading', function () {
            loadingCount++;
            window.clearTimeout(loadingTimeout);
            loadingTimeout = window.setTimeout(function () {
                self.setState({
                    loading: true
                });
            }, loadingDelay);
        });
        this.subscribe('hideLoading', function () {
            loadingCount--;
            window.setTimeout(function () {
                if (loadingCount <= 0) {
                    window.clearTimeout(loadingTimeout);
                    self.setState({
                        loading: false
                    });
                    loadingCount = 0;
                }
            }, 300);
        });
    },
    componentDidUpdate: function componentDidUpdate(prevProps, prevState) {
        if (this.state.dialogView && Foundation) {
            $('.reveal').foundation();
            var dialogSize = this.state.dialogSize;
            $(document).off('open.zf.reveal.mainView');
            $(document).one('open.zf.reveal.mainView', function () {
                $('.reveal-overlay').css({
                    display: dialogSize == 'full' ? 'none' : 'block'
                });
            });
            $('.reveal').foundation('open');
        } else {
            $('.reveal').foundation();
            $('.reveal').foundation('close');
        }
    },
    /**
     * build main view
     */
    getMainView: function getMainView() {
        if (this.state.mainView) {
            if (React.isValidElement(this.state.mainView)) {
                return this.state.mainView;
            } else {
                var MainView = React.createFactory(this.state.mainView);
                return MainView(this.state.mainParams);
            }
        }
        return null;
    },
    onCloseDialog: function onCloseDialog() {
        this.publish('showDialog');
    },
    /**
     * build dialog view
     */
    getDialogView: function getDialogView() {
        if (this.state.dialogView) {
            if (_.isString(this.state.dialogView)) {
                var content = this.state.dialogView;
                return React.createElement(
                    'div',
                    { className: 'row' },
                    React.createElement(
                        'div',
                        { className: 'small-12 columns' },
                        React.createElement(
                            'p',
                            null,
                            ' '
                        ),
                        React.createElement(
                            'h5',
                            null,
                            content
                        ),
                        React.createElement(
                            'p',
                            null,
                            ' '
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'small-12 columns' },
                        React.createElement(
                            'div',
                            { className: 'button-group float-right' },
                            React.createElement(
                                'a',
                                { href: 'javascript:;', className: 'button tiny', onClick: this.onCloseDialog },
                                'Okay'
                            )
                        )
                    ),
                    React.createElement(
                        'button',
                        { onClick: this.onCloseDialog, className: 'close-button', 'data-close': true, 'aria-label': 'Close reveal', type: 'button' },
                        React.createElement(
                            'span',
                            { 'aria-hidden': 'true' },
                            '×'
                        )
                    )
                );
            } else if (React.isValidElement(this.state.dialogView)) {
                return this.state.dialogView;
            } else {
                var DialogView = React.createFactory(this.state.dialogView),
                    params = _.extend(this.state.dialogParams, {
                    ref: "DialogView"
                });
                return DialogView(params);
            }
        }
        return null;
    },
    render: function render() {
        var model = this.getModel(),
            mainView = this.getMainView(),
            dialogView = this.getDialogView();

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'off-canvas-wrapper' },
                React.createElement(
                    'div',
                    { className: 'off-canvas-wrapper-inner', 'data-off-canvas-wrapper': true },
                    React.createElement(
                        'div',
                        { className: 'off-canvas position-left', id: 'offCanvas', 'data-off-canvas': true },
                        React.createElement(
                            'button',
                            { className: 'close-button', 'aria-label': 'Close menu', type: 'button', 'data-close': true },
                            React.createElement(
                                'span',
                                { 'aria-hidden': 'true' },
                                '×'
                            )
                        ),
                        React.createElement(
                            'ul',
                            { className: 'vertical menu' },
                            React.createElement(
                                'li',
                                null,
                                React.createElement(
                                    'a',
                                    { href: '#' },
                                    'menu1'
                                )
                            ),
                            React.createElement(
                                'li',
                                null,
                                React.createElement(
                                    'a',
                                    { href: '#' },
                                    'menu'
                                )
                            ),
                            React.createElement(
                                'li',
                                null,
                                React.createElement(
                                    'a',
                                    { href: '#' },
                                    'menu'
                                )
                            ),
                            React.createElement(
                                'li',
                                null,
                                React.createElement(
                                    'a',
                                    { href: '#' },
                                    'menu'
                                )
                            ),
                            React.createElement(
                                'li',
                                null,
                                React.createElement(
                                    'a',
                                    { href: '#' },
                                    'menu'
                                )
                            ),
                            React.createElement(
                                'li',
                                null,
                                React.createElement(
                                    'a',
                                    { href: '#' },
                                    'menu'
                                )
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'off-canvas-content', 'data-off-canvas-content': true },
                        mainView
                    )
                )
            ),
            React.createElement(
                'div',
                { className: 'reveal ' + this.state.dialogSize + ' ' + this.state.dialogCustomClass, 'data-reveal': true, 'data-options': 'closeOnClick: false; closeOnEsc: false;' },
                dialogView
            ),
            React.createElement(
                'div',
                { className: 'overlayer', style: { 'display': this.state.loading ? 'block' : 'none' } },
                React.createElement(LoadingView, null)
            )
        );
    }
});
