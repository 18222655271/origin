define([], function () {
    if (Config.modulename == 'admin' && Config.controllername == 'index' && Config.actionname == 'index') {

    require.config({
        paths: {
            'kefu': '../addons/kefu/js/kefu'
        },
        shim: {
            'kefu': {
                deps: ['css!../addons/kefu/css/kefu_admin_default.css'],
                exports: 'KeFu'
            }
        }
    });

    require(['kefu'], function (KeFu) {
        KeFu.initialize(document.domain, 'admin');
    });

} else {

    try {
        var parentConifg = window.parent.Config;
    } catch (err) {
        var parentConifg = false;
    }

    if (parentConifg && parentConifg.modulename == 'admin') {
        // 监听后台iframe内的快捷键打开会话窗口
        $(document).on('keyup', function (event) {

            if (window.parent.KeFu) {

                // console.log('当前按钮的code-iframe内:', event.keyCode);

                // 对打开会话窗口的监听
                // 打开会话窗口快捷键[ctrl + /],若需修改，请拿到对应键的keyCode替换下一行的191即可，191代表[/]键的keyCode
                if (event.keyCode === 191 && event.ctrlKey) {

                    if (window.parent.KeFu.last_sender) {
                        if (parseInt(window.parent.KeFu.last_sender) === window.parent.KeFu.session_id) {
                            // 展开分组
                            if (!window.parent.KeFu.group_show.dialogue) {
                                $('#heading_dialogue a').click();
                            }
                        } else {
                            window.parent.KeFu.changeSession(window.parent.KeFu.last_sender);
                            window.parent.KeFu.last_sender = null;
                        }
                    } else if (window.parent.KeFu.window_is_show) {
                        window.parent.KeFu.toggle_window('hide');
                    }

                    if (!window.parent.KeFu.window_is_show) {
                        window.parent.KeFu.toggle_window('show');
                    }
                    return ;
                }
            }

        });

    } else {

        require.config({
            paths: {
                'kefu': '../addons/kefu/js/kefu'
            },
            shim: {
                'kefu': {
                    deps: ['css!../addons/kefu/css/kefu_default.css'],
                    exports: 'KeFu'
                }
            }
        });

        require(['kefu'], function (KeFu) {
            KeFu.initialize(document.domain, 'index');
        });
    }
}
if (Config.modulename == 'admin' && Config.controllername == 'index' && Config.actionname == 'index') {
    require.config({
        paths: {
            'vue': "../addons/shopro/libs/vue",
            'moment': "../addons/shopro/libs/moment",
            'text': "../addons/shopro/libs/require-text",
            'chat': '../addons/shopro/libs/chat',
            'ELEMENT': '../addons/shopro/libs/element/element',
        },
        shim: {
            'ELEMENT': {
                deps: ['css!../addons/shopro/libs/element/element.css']
            },
        },
    });
    require(['vue', 'jquery', 'chat', 'text!../addons/shopro/chat.html', 'ELEMENT', 'moment'], function (Vue, $, Chat, ChatTemp, ELEMENT, Moment) {

        Vue.use(ELEMENT);

        var wsUri;
        Fast.api.ajax({
            url: 'shopro/chat/index/init',
            loading: false,
            type: 'GET'
        }, function (ret, res) {
            if (res.data.config.type == 'shopro') {

                let wg = 'ws';
                if (res.data.config.system.is_ssl == 1) {
                    wg = 'wss';
                }
                wsUri = wg + '://' + window.location.hostname + ':' + res.data.config.system.gateway_port;
                // 反向代理
                if (res.data.config.system.is_ssl == 1 && res.data.config.system.ssl_type == 'reverse_proxy') {
                    wsUri = wg + '://' + window.location.hostname + '/websocket/';
                }
                $("body").append(`<div id="chatTemplateContainer" style="display:none"></div>
                    <div id="chatService"><Chat :passvalue="obj"></Chat></div>`);

                $("#chatTemplateContainer").append(ChatTemp);

                new Vue({
                    el: "#chatService",
                    data() {
                        return {
                            obj: {
                                commonWordsList: res.data.fast_reply,
                                token: res.data.token,
                                wsUri: wsUri,
                                expire_time: res.data.expire_time,
                                customer_service_id: res.data.customer_service.id,
                                adminData: res.data,
                                emoji_list: res.data.emoji
                            }
                        }
                    }
                });

            }
            return false;
        }, function (ret, res) {
            if (res.msg == '') {
                return false;
            }
        })
    });
}
if (Config.modulename === 'index' && Config.controllername === 'user' && ['login', 'register'].indexOf(Config.actionname) > -1 && $("#register-form,#login-form").size() > 0) {
    $('<style>.social-login{display:flex}.social-login a{flex:1;margin:0 2px;}.social-login a:first-child{margin-left:0;}.social-login a:last-child{margin-right:0;}</style>').appendTo("head");
    $("#register-form,#login-form").append('<div class="form-group social-login"></div>');
    if (Config.third.status.indexOf("wechat") > -1) {
        $('<a class="btn btn-success" href="' + Fast.api.fixurl('/third/connect/wechat') + '"><i class="fa fa-wechat"></i> 微信登录</a>').appendTo(".social-login");
    }
    if (Config.third.status.indexOf("qq") > -1) {
        $('<a class="btn btn-info" href="' + Fast.api.fixurl('/third/connect/qq') + '"><i class="fa fa-qq"></i> QQ登录</a>').appendTo(".social-login");
    }
    if (Config.third.status.indexOf("weibo") > -1) {
        $('<a class="btn btn-danger" href="' + Fast.api.fixurl('/third/connect/weibo') + '"><i class="fa fa-weibo"></i> 微博登录</a>').appendTo(".social-login");
    }
}

});