window.LayoutView = React.createBackboneClass({
    mixins: [
        BaseView
    ],
    getInitialState: function () {
        return {
            mainView: null,
            mainParams: null,
            dialogView: null,
            dialogParams: null,
            dialogSize: 'small',
            dialogCustomClass: '',
            loading: false
        }
    },
    componentDidMount: function () {
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
                dialogParams: params,
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
    componentDidUpdate: function (prevProps, prevState) {
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
    getMainView: function () {
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
    onCloseDialog: function () {
        this.publish('showDialog');
    },
  /**
   * build dialog view
   */
    getDialogView: function () {
        if (this.state.dialogView) {
            if (_.isString(this.state.dialogView)) {
                var content = this.state.dialogView;
                return (
                    <div className="row">
                        <div className="small-12 columns">
                            <p>&nbsp;</p>
                            <h5>{content}</h5>
                            <p>&nbsp;</p>
                        </div>
                        <div className="small-12 columns">
                            <div className="button-group float-right">
                                <a href="javascript:;" className="button tiny" onClick={this.onCloseDialog}>Okay</a>
                            </div>
                        </div>
                        <button onClick={this.onCloseDialog} className="close-button" data-close aria-label="Close reveal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
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
    render: function () {
        var model = this.getModel(),
            mainView = this.getMainView(),
            dialogView = this.getDialogView();

        return (
            <div>
                <div className="off-canvas-wrapper">
                <div className="off-canvas-wrapper-inner" data-off-canvas-wrapper>

                <div className="off-canvas position-left" id="offCanvas" data-off-canvas>
                    <button className="close-button" aria-label="Close menu" type="button" data-close>
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <ul className="vertical menu">
                      <li><a href="#">menu1</a></li>
                      <li><a href="#">menu</a></li>
                      <li><a href="#">menu</a></li>
                      <li><a href="#">menu</a></li>
                      <li><a href="#">menu</a></li>
                      <li><a href="#">menu</a></li>
                    </ul>

                  </div>

                  <div className="off-canvas-content" data-off-canvas-content>
                    {mainView}
                  </div>
                </div>
                </div>
              
                <div className={'reveal ' + this.state.dialogSize + ' ' + this.state.dialogCustomClass} data-reveal data-options="closeOnClick: false; closeOnEsc: false;">
                    {dialogView}
                </div>

              <div className="overlayer" style={{'display': this.state.loading ? 'block' : 'none'}}>
                    <LoadingView />
                </div>
            </div>
        );
    }
});