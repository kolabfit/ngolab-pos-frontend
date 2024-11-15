"use strict"
function addSwitcher() {
    var dlabSwitcher = `
        <div class="sidebar-right">
            <div class="bg-overlay"></div>
            <a
                class="sidebar-right-trigger wave-effect wave-effect-x"
                data-bs-toggle="tooltip"
                data-placement="right"
                data-original-title="Change Layout"
                href="javascript:void(0);">
                <span>
                    <i class="fa fa-cog fa-spin"></i>
                    <a class="sidebar-close-trigger" href="javascript:void(0);">
                        <span>
                            <i class="la-times las"></i>
                        </span>
                    </a>
                    <div class="sidebar-right-inner">
                        <h4>
                            Settings
                            <a
                            href="javascript:void(0);"
                            onclick="deleteAllCookie()"
                            class="btn btn-primary btn-sm pull-right"
                            >Delete All Cookie</a>
                        </h4>
                        <div class="tab-content tab-content-default tabcontent-border">
                            <div class="fade tab-pane active show" id="tab1">
                                <div class="admin-settings">
                                    <div class="row">
                                    <div class="col-sm-12">
                                        <p>Background</p>
                                        <select
                                        class="default-select wide form-control"
                                        id="theme_version"
                                        name="theme_version"
                                        >
                                        <option value="dark">Dark</option>
                                        <option value="light">Light</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Layout</p>
                                        <select
                                        class="default-select wide form-control"
                                        id="theme_layout"
                                        name="theme_layout"
                                        >
                                        <option value="vertical">Vertical</option>
                                        <option value="horizontal">Horizontal</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Header position</p>
                                        <select
                                        class="default-select wide form-control"
                                        id="header_position"
                                        name="header_position"
                                        >
                                        <option value="static">Static</option>
                                        <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Sidebar</p>
                                        <select
                                        class="default-select wide form-control"
                                        id="sidebar_style"
                                        name="sidebar_style"
                                        >
                                        <option value="full">Full</option>
                                        <option value="mini">Mini</option>
                                        <option value="compact">Compact</option>
                                        <option value="modern">Modern</option>
                                        <option value="overlay">Overlay</option>
                                        <option value="icon-hover">Icon-hover</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>Sidebar position</p>
                                        <select
                                        class="default-select wide form-control"
                                        id="sidebar_position"
                                        name="sidebar_position"
                                        >
                                        <option value="static">Static</option>
                                        <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </span>
            </a>
        </div>`;

    var demoPanel = `
        <div class="dlab-demo-panel">
            <div class="bg-close"></div>
            <a
                class="dlab-demo-trigger"
                data-toggle="tooltip"
                data-placement="right"
                href="javascript:void(0)">
                <span>
                    <i class="las la-tint"></i>
                </span>
            </a>
            <div class="dlab-demo-inner">
                <div class="dlab-demo-header">
                    <p>Select Theme</p>
                    <a class="dlab-demo-close" href="javascript:void(0)"
                        ><span><i class="las la-times"></i></span
                    ></a>
                </div>
                <div class="dlab-demo-content">
                    <div class="dlab-wrapper">
                        <div class="overlay-bx dlab-demo-bx demo-active">
                            <div class="overlay-wrapper">
                                <img src="images/demo/pic1.jpg" alt="" class="w-100" />
                            </div>
                            <div class="overlay-layer">
                                <a href="javascript:void(0);" data-theme="1" class="btn dlab_theme_demo btn-secondary btn-sm mr-2" >Demo 1</a>
                            </div>
                        </div>
                        <h5 class="text-black mb-5">Demo 1</h5>
                        <hr />
                            <div class="overlay-bx dlab-demo-bx">
                            <div class="overlay-wrapper">
                                <img src="images/demo/pic2.jpg" alt="" class="w-100" />
                            </div>
                                <div class="overlay-layer">
                                    <a href="javascript:void(0);" data-theme="2" class="btn dlab_theme_demo btn-secondary btn-sm mr-2">Demo 2</a>
                                </div>
                            </div>
                            <h5 class="text-black mb-5">Demo 2</h5>
                            <hr />
                            <div class="overlay-bx dlab-demo-bx">
                                <div class="overlay-wrapper">
                                    <img src="images/demo/pic3.jpg" alt="" class="w-100" />
                                </div>
                                <div class="overlay-layer">
                                    <a href="javascript:void(0);" data-theme="3" class="btn dlab_theme_demo btn-secondary btn-sm mr-2">Demo 3</a>
                                </div>
                            </div>
                            <h5 class="text-black mb-5">Demo 3</h5>
                            <hr />
                            <div class="overlay-bx dlab-demo-bx">
                            <div class="overlay-wrapper">
                                <img src="images/demo/pic4.jpg" alt="" class="w-100" />
                            </div>
                            <div class="overlay-layer">
                                <a
                                href="javascript:void(0);"
                                data-theme="4"
                                class="btn dlab_theme_demo btn-secondary btn-sm mr-2"
                                >Demo 4</a
                                >
                            </div>
                            </div>
                            <h5 class="text-black mb-5">Demo 4</h5>
                            <hr />
                            <div class="overlay-bx dlab-demo-bx">
                            <div class="overlay-wrapper">
                                <img src="images/demo/pic5.jpg" alt="" class="w-100" />
                            </div>
                            <div class="overlay-layer">
                                <a
                                href="javascript:void(0);"
                                data-theme="5"
                                class="btn dlab_theme_demo btn-secondary btn-sm mr-2"
                                >Demo 5</a
                                >
                            </div>
                            </div>
                            <h5 class="text-black mb-5">Demo 5</h5>
                            <div class="overlay-bx dlab-demo-bx">
                            <div class="overlay-wrapper">
                                <img src="images/demo/pic6.jpg" alt="" class="w-100" />
                            </div>
                            <div class="overlay-layer">
                                <a
                                href="javascript:void(0);"
                                data-theme="6"
                                class="btn dlab_theme_demo btn-secondary btn-sm mr-2"
                                >Demo 6</a>
                            </div>
                        </div>
                        <h5 class="text-black mb-5">Demo 6</h5>
                    </div>
                </div>
            </div>
        </div>

    `;

    // Sidebar
    if ($("#dlabSwitcher").length == 0) {
        const tempDiv = document.createElement('div');
        const parser = new DOMParser();
        const switcherDoc = parser.parseFromString(dlabSwitcher, 'text/html');
        const panelDoc = parser.parseFromString(demoPanel, 'text/html');
        tempDiv.appendChild(switcherDoc.body.firstChild);
        tempDiv.appendChild(panelDoc.body.firstChild);
        document.body.appendChild(tempDiv);

        //const ps = new PerfectScrollbar('.sidebar-right-inner');
        //console.log(ps.reach.x);	
        //ps.isRtl = false;

        $('.sidebar-right-trigger').on('click', function () {
            $('.sidebar-right').toggleClass('show');
        });
        $('.sidebar-close-trigger,.bg-overlay').on('click', function () {
            $('.sidebar-right').removeClass('show');
        });
    }
}

(function ($) {
    "use strict"
    addSwitcher();

    const body = $('body');
    const html = $('html');

    //get the DOM elements from right sidebar
    const versionSelect = $('#theme_version');
    const sidebarStyleSelect = $('#sidebar_style');
    const sidebarPositionSelect = $('#sidebar_position');
    const headerPositionSelect = $('#header_position');
    const themeDirectionSelect = $('#theme_direction');

    //change the theme version controller
    versionSelect.on('change', function () {
        body.attr('data-theme-version', this.value);

        if (this.value === 'light') {
            // If Light
            jQuery(".nav-header .logo-abbr").attr("src", "./images/logo.png");
            jQuery(".nav-header .logo-compact").attr("src", "images/logo-text.png");
            jQuery(".nav-header .brand-title").attr("src", "images/logo-text.png");

            setCookie('logo_src', './images/logo.png');
            setCookie('logo_src2', 'images/logo-text.png');
        } else {
            // Default Dark
            jQuery(".nav-header .logo-abbr").attr("src", "./images/logo-white.png");
            jQuery(".nav-header .logo-compact").attr("src", "images/logo-text-white.png");
            jQuery(".nav-header .brand-title").attr("src", "images/logo-text-white.png");

            setCookie('logo_src', './images/logo-white.png');
            setCookie('logo_src2', 'images/logo-text-white.png');
        }

        setCookie('version', this.value);
    });

    //change the sidebar position controller
    sidebarPositionSelect.on('change', function () {
        this.value === "fixed" && body.attr('data-sidebar-style') === "modern" && body.attr('data-layout') === "vertical" ?
            alert("Sorry, Modern sidebar layout dosen't support fixed position!") :
            body.attr('data-sidebar-position', this.value);
        setCookie('sidebarPosition', this.value);
    });

    //change the header position controller
    headerPositionSelect.on('change', function () {
        body.attr('data-header-position', this.value);
        setCookie('headerPosition', this.value);
    });

    //change the theme direction (rtl, ltr) controller
    themeDirectionSelect.on('change', function () {
        html.attr('dir', this.value);
        html.attr('class', '');
        html.addClass(this.value);
        body.attr('direction', this.value);
        setCookie('direction', this.value);
    });

    //change the sidebar style controller
    sidebarStyleSelect.on('change', function () {
        if (body.attr('data-layout') === "horizontal") {
            if (this.value === "overlay") {
                alert("Sorry! Overlay is not possible in Horizontal layout.");
                return;
            }
        }

        if (body.attr('data-layout') === "vertical") {
            if (body.attr('data-container') === "boxed" && this.value === "full") {
                alert("Sorry! Full menu is not available in Vertical Boxed layout.");
                return;
            }

            if (this.value === "modern" && body.attr('data-sidebar-position') === "fixed") {
                alert("Sorry! Modern sidebar layout is not available in the fixed position. Please change the sidebar position into Static.");
                return;
            }
        }

        body.attr('data-sidebar-style', this.value);

        if (body.attr('data-sidebar-style') === 'icon-hover') {
            $('.dlabnav').on('hover', function () {
                $('#main-wrapper').addClass('iconhover-toggle');
            }, function () {
                $('#main-wrapper').removeClass('iconhover-toggle');
            });
        }

        setCookie('sidebarStyle', this.value);
    });

    /* jQuery("#nav_header_color_1").on('click',function(){
        jQuery(".nav-header .logo-abbr").attr("src", "./images/logo.png");
        setCookie('logo_src', './images/logo.png');
        return false;
    }); */

    /* jQuery("#sidebar_color_2, #sidebar_color_3, #sidebar_color_4, #sidebar_color_5, #sidebar_color_6, #sidebar_color_7, #sidebar_color_8, #sidebar_color_9, #sidebar_color_10, #sidebar_color_11, #sidebar_color_12, #sidebar_color_13, #sidebar_color_14, #sidebar_color_15").on('click',function(){
        jQuery(".nav-header .logo-abbr").attr("src", "./images/logo-white.png");
        jQuery(".nav-header .brand-title").attr("src", "./images/logo-text-white.png");
        setCookie('logo_src', './images/logo-white.png');
        return false;
    }); */

    /* jQuery("#nav_header_color_3").on('click',function(){
        jQuery(".nav-header .logo-abbr").attr("src", "./images/logo-white.png");
        setCookie('logo_src', './images/logo-white.png');
        return false;
    }); */

})(jQuery);


