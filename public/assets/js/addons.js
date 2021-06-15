define([], function () {
    require.config({
    paths: {
        'editable': '../libs/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.min',
        'x-editable': '../addons/editable/js/bootstrap-editable.min',
    },
    shim: {
        'editable': {
            deps: ['x-editable', 'bootstrap-table']
        },
        "x-editable": {
            deps: ["css!../addons/editable/css/bootstrap-editable.css"],
        }
    }
});
if ($("table.table").size() > 0) {
    require(['editable', 'table'], function (Editable, Table) {
        $.fn.bootstrapTable.defaults.onEditableSave = function (field, row, oldValue, $el) {
            var data = {};
            data["row[" + field + "]"] = row[field];
            Fast.api.ajax({
                url: this.extend.edit_url + "/ids/" + row[this.pk],
                data: data
            });
        };
    });
}
require.config({
    paths: {
        'geetest': '../addons/geetest/js/geetest.min'
    }
});

require(['geetest'], function (Geet) {
    var geetInit = false;
    window.renderGeetest = function () {
        $("input[name='captcha']:visible").each(function () {
            var obj = $(this);
            var form = obj.closest('form');
            obj.parent()
                .removeClass('input-group')
                .html('<div class="embed-captcha"><input type="hidden" name="captcha" class="form-control" data-msg-required="请完成验证码验证" data-rule="required" /> </div> <p class="wait show" style="min-height:44px;line-height:44px;">正在加载验证码...</p>');

            Fast.api.ajax("/addons/geetest/index/start", function (data) {
                // 参数1：配置参数
                // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                initGeetest({
                    gt: data.gt,
                    https: true,
                    challenge: data.challenge,
                    new_captcha: data.new_captcha,
                    product: Config.geetest.product, // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                    width: '100%',
                    offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                }, function (captchaObj) {
                    // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
                    geetInit = captchaObj;
                    captchaObj.appendTo($(".embed-captcha", form));
                    captchaObj.onReady(function () {
                        $(".wait", form).remove();
                    });
                    captchaObj.onSuccess(function () {
                        var result = captchaObj.getValidate();
                        if (result) {
                            $('input[name="captcha"]', form).val('ok');
                        }
                    });
                    captchaObj.onError(function () {
                        geetInit.reset();
                    });
                });
                // 监听表单错误事件
                form.on("error.form", function (e, data) {
                    geetInit.reset();
                });
                return false;
            });
        });
    };
    renderGeetest();
});

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