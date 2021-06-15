/**
 * Kefu v1.0.7
 * FastAdmin在线客服系统
 * https://www.fastadmin.net/store/kefu.html
 *
 * Copyright 2020 白衣素袖
 *
 * FastAdmin在线客服系统不是开源产品，所有文字、图片、样式、风格等版权归在线客服作者所有，如有复制、仿冒、抄袭、盗用，FastAdmin和在线客服作者将追究法律责任
 *
 * Released on: November 6, 2020
 */
// 音频播放初始化
window.AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext || window.msAudioContext;

var KeFu = {
    ws: {
        SocketTask: null,
        Timer: null,
        ErrorMsg: [],
        MaxRetryCount: 3,// 最大重连次数
        CurrentRetryCount: 0,
        url: null
    },
    audio: {
        context: new window.AudioContext(),
        source: null,
        buffer: null
    },
    config: null,
    url: null,
    fixed_csr: 0,
    window_is_show: false,
    fast_move: false,
    csr: "",// 当前用户的客服代表ID
    session_id: 0,
    last_sender: null,
    token_list: [],
    fast_reply: [],// 快捷回复富文本支持
    session_user: "",
    select_session_user: "",// 右键菜单选中的 session_user
    slider: null,// 幻灯片对象
    clickkefu_button: false,// 防止浮动按钮被拖动时触发点击事件
    allowed_close_window: true,// 是否允许ecs关闭窗口（当预览图片时，不允许关闭）
    record_scroll_height: 0,// 聊天记录窗口的滚动条高度
    resize_load: 0,
    group_show: {
        'dialogue': false,
        'invitation': false,
        'recently': false
    }, // 分组是否展开,未展开时添加红点
    initialize: function (url = document.domain, modulename = 'index', initSuccess = null, fixed_csr = 0) {

        KeFu.url = url;
        KeFu.fixed_csr = fixed_csr;
        var initialize_url = KeFu.buildUrl(url, modulename, 'initialize');

        $.ajax({
            url: initialize_url,
            success: function (data) {

                if (data.code == 401) {
                    console.warn(data.msg);
                    return;
                } else if (data.code != 1) {
                    layer.msg(data.msg);
                    return;
                }

                KeFu.config = data.data.config;
                KeFu.config.user_id = data.data.user_info.user_id;
                KeFu.token_list = data.data.token_list;
                if (KeFu.token_list.kefu_tourists_token) {
                    localStorage.setItem('kefu_tourists_token', KeFu.token_list.kefu_tourists_token);
                }
                KeFu.fast_reply = data.data.fast_reply;
                KeFu.bulidChat(data.data.window_html);

                if (parseInt(KeFu.config.auto_invitation_switch) > 0 && KeFu.config.modulename != 'admin') {

                    let only_first_invitation = parseInt(KeFu.config.only_first_invitation);

                    // kefu_auto_invitation 在窗口成功弹出后创建
                    if ((only_first_invitation == 1 && !localStorage.getItem('kefu_auto_invitation')) || only_first_invitation == 0) {

                        setTimeout(function () {
                            KeFu.bulidInviteBox();
                        }, (KeFu.config.auto_invitation_timing * 1000));
                    }

                }

                // 构建ws和文件上传的url
                KeFu.ws.url = KeFu.buildUrl(url, modulename, 'ws', KeFu.config.websocket_port);
                KeFu.config.upload.uploadurl = KeFu.buildUrl(url, modulename, "upload");

                if (modulename == 'admin') {
                    // 立即链接 Websocket
                    KeFu.ConnectSocket();
                } else {
                    // 若用户 10 秒后任在此页面，链接Socket
                    setTimeout(function () {
                        if (!KeFu.ws.SocketTask || KeFu.ws.SocketTask.readyState == 3 || KeFu.ws.SocketTask.readyState == 2) {
                            KeFu.ConnectSocket();
                        }
                    }, 10000);
                }

                if (typeof initSuccess == 'function') {
                    initSuccess();
                }

                // 读取按钮位置
                var kefu_button_coordinate = localStorage.getItem("kefu_button_coordinate");
                if (kefu_button_coordinate) {
                    kefu_button_coordinate = kefu_button_coordinate.split(',');
                    if (kefu_button_coordinate[0] && kefu_button_coordinate[1] && $('body').width() > Number(kefu_button_coordinate[1])) {
                        $("#kefu_button").css({
                            "top": Number(kefu_button_coordinate[0]),
                            "left": Number(kefu_button_coordinate[1])
                        });
                    }
                }

                // 新消息
                if (data.data.new_msg) {
                    KeFu.toggle_popover('show', data.data.new_msg);
                    KeFu.new_message_prompt('#kefu_button');
                } else if (!localStorage.getItem('kefu_new_user')) {
                    KeFu.toggle_popover('show', KeFu.config.new_user_tip);
                    KeFu.new_message_prompt('#kefu_button');
                }

                KeFu.eventReg();
            }
        });
    },
    ConnectSocket: function () {

        if ("WebSocket" in window) {
            var ws = new WebSocket(KeFu.ws.url);
            KeFu.ws.SocketTask = ws;

            ws.onopen = function () {

                // 重新发送所有出错的消息
                if (KeFu.ws.ErrorMsg.length > 0) {

                    for (let i in KeFu.ws.ErrorMsg) {
                        KeFu.ws_send(KeFu.ws.ErrorMsg[i]);
                    }

                    KeFu.ws.ErrorMsg = [];
                }

                if (KeFu.ws.Timer != null) {
                    clearInterval(KeFu.ws.Timer);
                }

                KeFu.ws.Timer = setInterval(KeFu.ws_send, 28000);//定时发送心跳
            };

            ws.onmessage = function (evt) {
                var msg = $.parseJSON(evt.data);
                let action = KeFu.domsg[msg.msgtype] || KeFu.domsg['default']
                action.call(this, msg)
            };

            ws.onclose = function (e) {

                if (KeFu.ws.Timer != null) {
                    clearInterval(KeFu.ws.Timer);
                }

                KeFu.ws.ws_error = true;
                $('#kefu_error').html('网络链接已断开');
                KeFu.toggle_popover('show', 'WebSocket 链接已断开');
                if (KeFu.ws.MaxRetryCount) {
                    KeFu.ws.Timer = setInterval(KeFu.retry_webSocket, 3000);//每3秒重新连接一次
                }
            };

            ws.onerror = function (e) {
                // 错误
                KeFu.ws.ws_error = true;
                console.error('websocket 错误:', e);
                $('#kefu_error').html('WebSocket 发生错误');
                KeFu.toggle_popover('show', 'WebSocket 发生错误');
            };
        } else {
            KeFu.ws.ws_error = true;
            layer.msg(KeFu.config.chat_name + '：您的浏览器不支持 WebSocket!');
        }
    },
    retry_webSocket: function () {
        if (KeFu.ws.CurrentRetryCount < KeFu.ws.MaxRetryCount) {
            KeFu.ws.CurrentRetryCount++;
            KeFu.ConnectSocket();
            console.log('重连 WebSocket 第' + KeFu.ws.CurrentRetryCount + '次');
        } else {
            if (KeFu.ws.Timer != null) {
                clearInterval(KeFu.ws.Timer);
            }

            console.log('每隔10秒将再次尝试重连 WebSocket')
            KeFu.ws.Timer = setInterval(KeFu.ConnectSocket, 10000);//每10秒重新连接一次
        }
    },
    change_csr_status: function (status_id) {

        status_id = parseInt(status_id);

        const states = new Map([
            [0, ['离线', '#777']],
            [1, ['繁忙', '#8a6d3b']],
            [2, ['离开', '#a94442']],
            [3, ['在线', '#3c763d']],
            ['default', ['未知', '#777']],
        ])

        let state = states.get(status_id) || states.get('default');

        if (KeFu.config.modulename == 'admin') {
            $('#kefu_csr_status button .kefu_status').html(state[0]);
            $('#kefu_csr_status button').css('color', state[1]);
        } else {
            $('.modal-title #csr_status').html(' • ' + state[0]);
            $('.modal-title #csr_status').css('color', state[1]);
        }
    },
    // 会话分组的红点管理
    session_group_red_dot: function (group, red_dot) {
        var element_id = '#heading_' + group;
        if (red_dot) {
            $('.KeFu .modal-body .kefu-left ' + element_id + ' .panel-title .red_dot').fadeIn();
        } else {
            $('.KeFu .modal-body .kefu-left ' + element_id + ' .panel-title .red_dot').fadeOut();
        }
    },
    get_format_session_time: function () {
        var date_obj = new Date();
        var hours = date_obj.getHours();
        hours = hours < 10 ? '0' + hours : hours;
        var minutes = date_obj.getMinutes();
        minutes = minutes < 10 ? '0' + minutes : minutes;
        return hours + ':' + minutes;
    },
    domsg: {
        default: (msg) => {
            // console.log('default', msg);
        },
        initialize: (msg) => {

            $('#kefu_error').html('');

            if (KeFu.ws.ws_error) {
                KeFu.ws.CurrentRetryCount = 0;
                KeFu.toggle_popover('hide');
                KeFu.ws.ws_error = false;
            }

            if (msg.data.modulename == 'admin') {

                KeFu.change_csr_status(msg.data.user_info.status_text);
                $('#modal-title').html(msg.data.chat_name + '-' + msg.data.user_info.nickname);

                // 渲染聊天列表
                if (msg.data.session.dialogue && msg.data.session.dialogue.length) {

                    for (let i in msg.data.session.dialogue) {
                        KeFu.buildSession(msg.data.session.dialogue[i], 'dialogue');
                    }

                    KeFu.session_id = msg.data.session.dialogue[msg.data.session.dialogue.length - 1].id;
                    $('#heading_dialogue a').click();
                }

                if (msg.data.session.invitation && msg.data.session.invitation.length) {

                    for (let i in msg.data.session.invitation) {
                        KeFu.buildSession(msg.data.session.invitation[i], 'invitation');
                    }

                }

                if (msg.data.session.recently && msg.data.session.recently.length) {

                    for (let i in msg.data.session.recently) {
                        KeFu.buildSession(msg.data.session.recently[i], 'recently');
                    }

                    if (!KeFu.session_id) {
                        KeFu.session_id = msg.data.session.recently[msg.data.session.recently.length - 1].id;
                        $('#heading_recently a').click();
                    }
                }

                KeFu.changeSession(KeFu.session_id);
            } else {
                if (msg.data.new_msg) {
                    KeFu.toggle_popover('show', msg.data.new_msg);
                    KeFu.new_message_prompt('#kefu_button');
                }
            }
        },
        user_card: (msg) => {
            if (msg.data) {
                $('#card-user').val(msg.data.nickname + ' ID:' + msg.data.id + (msg.data.user_id ? ' 会员ID:' + msg.data.user_id : ''));
                $('#card-nickname').val(msg.data.nickname_origin);
                $('#card-referrer').val(msg.data.referrer);
                $('#card-contact').val(msg.data.contact);
                $('#card-note').val(msg.data.note);
            }
        },
        search_user: (msg) => {
            // 渲染搜索结果列表
            var element_id = '#session_list_search';
            KeFu.search_primary = -1;
            KeFu.search_select_id = '';
            $(element_id).html('');
            $(element_id).fadeIn();

            if (msg.data.length) {

                $.each(msg.data, function (index, item) {

                    $(element_id).append(
                        '<li class="person" data-session="' + item.id + '" data-session_user="' + item.session_user + '" data-group="search" data-nickname="' + item.nickname + '">' +
                        '<img class="person_avatar" src="' + item.avatar + '" alt="" />' +
                        '<div class="session_info_item">' +
                        '<span class="name">' + item.nickname + '</span>' +
                        '<span class="time">' + item.last_time + '</span>' +
                        '</div>\
                        <div class="session_info_item">' +
                        '<span class="preview">' + item.last_message + '</span>' +
                        '</div>' +
                        '</li>'
                    );
                });
            } else {
                $(element_id).append('<div class="none_session">找不到用户~</div>');
            }
        },
        user_initialize: (msg) => {
            // 用户客服分配结束
            if (msg.code == 1) {

                if (msg.data.session.user_tourists) {
                    KeFu.sendMessage = function () {
                        layer.msg('请登录后发送消息~');
                    }
                    KeFu.edit_send_tis('为保护您的隐私请 <a href="' + msg.data.session.user_login_url + '">登录</a> 后发送消息');
                }

                KeFu.csr = msg.data.session.csr;
                KeFu.session_id = msg.data.session.id;
                $('#modal-title').html('客服 ' + msg.data.session.nickname + ' 为您服务');
                KeFu.toggle_window_view('kefu_scroll');
                KeFu.change_csr_status(msg.data.session.csr_status);
            } else if (msg.code == 302) {

                if (!KeFu.csr) {

                    // 打开留言板
                    KeFu.csr = 'none';
                    $('#modal-title').html('当前无客服在线哦~');
                    KeFu.toggle_window_view('kefu_leave_message');
                } else {
                    KeFu.edit_send_tis('当前客服暂时离开,您可以直接在此发送离线消息');
                }

            }
        },
        action_session: (msg) => {
            if (msg.data.action == 'received_invitation' && !KeFu.window_is_show) {

                // 显示邀请框
                var kefu_invite_box = $('.kefu_invite_box');
                if (kefu_invite_box.length) {
                    kefu_invite_box.fadeIn();
                } else {
                    KeFu.bulidInviteBox();
                }
            } else if (msg.data.action == 'send_success' && KeFu.session_user == msg.data.session_user) {

                // 重新加载轨迹
                var session = $("#session_panel [data-session_user='" + msg.data.session_user + "']");
                KeFu.changeSession(session.data('session'), 'trajectory');
            } else if (msg.data.action == 'transfer') {

                // 显示转接操作面板
                var tpl = '\
                <div class="kefu_transfer_session" data-transfer_user="' + msg.data.session_user + '">\
                    <div class="form-group">\
                        <label>转接给</label>\
                        <select id="transfer_session_select" class="form-control">\
                        </select>\
                        <div class="transfer_session_buttons">\
                            <button type="button" id="transfer_session_cancel" class="btn btn-default btn-sm">取消</button>\
                            <button type="button" id="transfer_session_ok" class="btn btn-success btn-sm">确定</button>\
                        </div>\
                    </div>\
                </div>';

                var session = $("#session_panel [data-session_user='" + msg.data.session_user + "']");
                session.after(tpl);

                $.each(msg.data.csr_list, function (index, item) {
                    let html_text = item.nickname + '(ID:' + item.admin_id + ')';
                    $("#transfer_session_select").append("<option value='" + item.admin_id + "'>" + html_text + "</option>");
                });

                $(document).on('click', '#transfer_session_cancel', function (e) {
                    $('.kefu_transfer_session').remove();
                });

                $(document).on('click', '#transfer_session_ok', function (e) {

                    var transfer_user = $('.kefu_transfer_session').data('transfer_user');
                    var csr = $('#transfer_session_select').val();

                    if (transfer_user && csr) {
                        var action_session = {
                            c: 'Message',
                            a: 'actionSession',
                            data: {
                                action: 'transfer_done',
                                session_user: transfer_user,
                                csr: csr
                            }
                        };
                        KeFu.ws_send(action_session);
                    }

                    $('.kefu_transfer_session').remove();
                });

            } else if (msg.data.action == 'transfer_done' && msg.data.res) {

                // 转移成功
                layer.msg('会话已转移给客服 ' + msg.data.res);
                $("#session_panel [data-session_user='" + msg.data.session_user + "']").remove();
                KeFu.group_session_is_none('dialogue');
                KeFu.group_session_is_none('recently');
            } else if (msg.data.action == 'edit_nickname') {
                var session = $("#session_panel [data-session_user='" + msg.data.session_user + "']");
                session.children(".session_info_item").children(".name").eq(0).html(msg.data.list_nickname);

                if (KeFu.session_user == msg.data.session_user) {
                    $('#session_user_name').html(msg.data.new_nickname);
                }
            }
        },
        leave_message: (msg) => {
            layer.msg(msg.msg);
            $('#kefu_leave_message form')[0].reset()
        },
        show_msg: (msg) => {
            layer.msg(msg.msg);
        },
        clear: (msg) => {
            if (msg.msg) {
                layer.msg(msg.msg);
            }

            var clear = {
                c: 'Message',
                a: 'clear'
            };
            KeFu.ws_send(clear);

            KeFu.retry_webSocket = function () {
                clearInterval(KeFu.ws.Timer)
            };
        },
        offline: (msg) => {
            if (KeFu.config.modulename == 'admin') {
                $("#session_panel [data-transfer_user='" + msg.user_id + "']").remove();
                KeFu.edit_online_status(msg.user_id, false);
            } else {
                if (msg.user_id == KeFu.csr) {
                    KeFu.edit_send_tis('当前客服暂时离开,您可以直接在此发送离线消息');
                    KeFu.change_csr_status(0);
                }
            }
        },
        online: (msg) => {
            var element = $("#session_panel [data-session_user='" + msg.user_id + "']");

            if (msg.tourists != 'not' && !element.length) {
                // 添加到邀请中
                KeFu.buildSession(msg.tourists, 'invitation');

                if (!KeFu.group_show.invitation) {
                    KeFu.session_group_red_dot('invitation', true);
                }
            }

            if (KeFu.window_is_show && KeFu.session_user == msg.user_id) {
                $('#session_user_name').html(msg.user_name);
            }

            // 修改用户在线状态
            KeFu.edit_online_status(msg.user_id, true);

            // 来自 admin 的用户上线了
            if (msg.modulename == 'admin') {

                if (msg.user_id == KeFu.csr) {
                    let send_tis_key = parseInt(KeFu.config.send_message_key) == 1 ? 'Enter' : 'Ctrl+Enter';
                    KeFu.edit_send_tis('按下' + send_tis_key + '发送消息', '按下' + (send_tis_key == 'Enter' ? 'Ctrl+Enter' : 'Enter') + '换行');
                    KeFu.change_csr_status(3);
                } else if (KeFu.csr == 'none') {
                    // 重新为用户分配客服代表
                    var user_initialize = {
                        c: 'Message',
                        a: 'userInitialize'
                    };
                    KeFu.ws_send(user_initialize);
                }
            }
        },
        trajectory: (msg) => {
            var trajectory = msg.data.trajectory;
            KeFu.chat_record_page = msg.data.next_page;

            if (msg.data.page == 1) {
                $('#kefu_trajectory_log').html('');

                // 修改会话的最后消息
                let session = $("#session_panel [data-session_user='" + msg.data.user_info.session_user + "']");
                session.children(".session_info_item").children(".time").eq(0).html(msg.data.last_message.last_time);
                session.children(".session_info_item").find(".last_message").eq(0).html(msg.data.last_message.last_message);
            }

            $('#session_user_name').html(msg.data.user_info.nickname);

            // 构建轨迹
            var x = 'left';
            for (let i in trajectory) {
                if (msg.data.page == 1) {
                    KeFu.build_trajectory(i, msg.data.page, 'date');
                }

                for (let y in trajectory[i]) {
                    KeFu.build_trajectory(trajectory[i][y], msg.data.page, 'log', x);
                    x = (x == 'left') ? 'right' : 'left';
                }

                if (msg.data.page != 1) {
                    KeFu.build_trajectory(i, msg.data.page, 'date');
                }
            }

            KeFu.kefu_blacklist(3, msg.data.user_info.session_user, msg.data.user_info.blacklist);

            if (msg.data.page == 1) {
                if (KeFu.window_is_show) {
                    $('#kefu_trajectory').scrollTop($('#kefu_trajectory')[0].scrollHeight);
                } else {
                    KeFu.window_show_event = function () {
                        $('#kefu_trajectory').scrollTop($('#kefu_trajectory')[0].scrollHeight);
                    }
                }
            } else {
                $('#kefu_trajectory').scrollTop($('#kefu_trajectory')[0].scrollHeight - KeFu.record_scroll_height);
            }
        },
        chat_record: (msg) => {
            if (msg.data.page == 1) {
                $('#kefu_scroll').html('');
            }

            if (KeFu.config.modulename == 'admin') {
                $('#session_user_name').html(msg.data.session_info.nickname);
            }

            var chat_record = msg.data.chat_record;
            KeFu.chat_record_page = msg.data.next_page;

            for (let i in chat_record) {

                if (msg.data.page == 1) {
                    KeFu.buildPrompt(chat_record[i].datetime, msg.data.page);
                }

                for (let y in chat_record[i].data) {
                    KeFu.buildRecord(chat_record[i].data[y], msg.data.page)
                }

                if (msg.data.page != 1) {
                    KeFu.buildPrompt(chat_record[i].datetime, msg.data.page);
                }
            }

            KeFu.kefu_blacklist(3, msg.data.session_info.session_user, msg.data.session_info.blacklist);

            if (msg.data.page == 1) {
                if (KeFu.window_is_show) {
                    setTimeout(function () {
                        $('#kefu_scroll').scrollTop($('#kefu_scroll')[0].scrollHeight);
                    }, 100)
                } else {
                    KeFu.window_show_event = function () {
                        $('#kefu_scroll').scrollTop($('#kefu_scroll')[0].scrollHeight);
                    }
                }
            } else {
                $('#kefu_scroll').scrollTop($('#kefu_scroll')[0].scrollHeight - KeFu.record_scroll_height);
            }

            // 消息输入框聚焦
            setTimeout(function () {
                $('#kefu_message').focus();
            }, 500)
        },
        csr_change_status: (msg) => {
            if (KeFu.config.modulename == 'admin' && KeFu.config.user_id == msg.data.csr) {
                KeFu.change_csr_status(msg.data.csr_status);
            } else if (KeFu.csr == msg.data.csr) {
                KeFu.change_csr_status(msg.data.csr_status);
            }
        },
        blacklist: (msg) => {
            if (msg.data.action == 'del') {
                KeFu.kefu_blacklist(2, msg.data.session_user);
            } else if (msg.data.action == 'add') {
                KeFu.kefu_blacklist(1, msg.data.session_user);
            }
        },
        transfer_done: (msg) => {
            KeFu.csr = msg.data.csr;
            $('#modal-title').html('客服 ' + msg.data.nickname + ' 为您服务');
        },
        reload_record: (msg) => {
            // 重载聊天记录
            if (KeFu.session_id == msg.data.session_id) {
                var load_record = {
                    c: 'Message',
                    a: 'chatRecord',
                    data: {
                        session_id: msg.data.session_id,
                        page: 1
                    }
                };
                KeFu.ws_send(load_record)
            }
        },
        new_message: (msg) => {
            var message_content = msg.data.nickname + ':' + msg.data.last_message;

            if (KeFu.window_is_show) {
                // 窗口打开状态
                KeFu.new_message_prompt('#KeFuModal');
            } else {
                KeFu.toggle_popover('show', message_content);
                KeFu.new_message_prompt('#kefu_button');
            }

            if ($('#kefu_scroll').children('.status').children('span').eq(0).html() == '还没有消息') {
                $('#kefu_scroll').children('.status').children('span').eq(0).html(KeFu.get_format_session_time());
            }

            if (msg.data.session_id == KeFu.session_id) {
                $('#kefu_input_status').html('');
            }

            if (KeFu.config.modulename == 'admin') {

                // 为会话中添加一个红点
                if (!KeFu.group_show.dialogue) {
                    KeFu.session_group_red_dot('dialogue', true);
                }

                // 检查是否有该会话
                let session = $("#session_panel [data-session_user='" + msg.data.session_user + "']");

                // 记录最后发信人
                KeFu.last_sender = msg.data.session_id;

                if (session.length == 0) {
                    KeFu.buildSession(msg.data, 'dialogue');
                    KeFu.group_session_is_none('dialogue');
                } else {

                    // 会话将被移动->取消会话转移
                    $("#session_panel [data-transfer_user='" + msg.data.session_user + "']").remove();

                    // 确保 data 正确
                    session.data('session', msg.data.session_id);
                    session.attr("data-session", msg.data.session_id);
                    session.data('group', 'dialogue');
                    session.attr("data-group", 'dialogue');

                    // 修改该会话的最后消息
                    session.children(".session_info_item").children(".time").eq(0).html(msg.data.last_time);
                    session.children(".session_info_item").find(".last_message").eq(0).html(msg.data.last_message);

                    // 将会话移动到“会话中”的第一位
                    let first_session = $('#session_list_dialogue li');

                    if (first_session.length) {
                        // 会话中已有对话
                        first_session.eq(0).before(session);
                    } else {
                        // 会话中没有对话
                        $('#session_list_dialogue').prepend(session);
                    }

                    KeFu.group_session_is_none('dialogue');
                    KeFu.group_session_is_none('recently');
                    KeFu.group_session_is_none('invitation');

                    if (msg.data.session_user == KeFu.session_user && KeFu.window_is_show) {

                        if (session.data('group') != 'dialogue') {

                            // 会话被移动到“会话中”->取消该会话的选择状态
                            session.removeClass("active");
                        } else {

                            if ($('#kefu_trajectory').css('display') != 'block') {
                                // 必要时，去除该会话的红点
                                KeFu.buildRecord(msg.data, 1);
                                $('#kefu_scroll').scrollTop($('#kefu_scroll')[0].scrollHeight);

                                var load_message = {
                                    c: 'Message',
                                    a: 'readMessage',
                                    data: {
                                        record_id: msg.data.record_id,
                                        session_id: KeFu.session_id
                                    }
                                };

                                KeFu.ws_send(load_message);
                                return;
                            }
                        }
                    }

                    if (msg.data.unread_msg_count > 0) {
                        session.children(".session_info_item").children(".unread_msg_count").eq(0).html(msg.data.unread_msg_count).fadeIn();
                    } else {
                        session.children(".session_info_item").children(".unread_msg_count").eq(0).fadeOut();
                    }
                }

            } else {

                KeFu.buildRecord(msg.data, 1);

                if (msg.data.session_id == KeFu.session_id && KeFu.window_is_show) {

                    $('#kefu_scroll').scrollTop($('#kefu_scroll')[0].scrollHeight);

                    var load_message = {
                        c: 'Message',
                        a: 'readMessage',
                        data: {
                            record_id: msg.data.record_id,
                            session_id: KeFu.session_id
                        }
                    };

                    KeFu.ws_send(load_message);
                    return;
                }
            }
        },
        send_message: (msg) => {
            if (!msg.data.message_id) {
                return;
            }

            if (msg.code == 1) {
                let message_record = $('.kefu_message_' + msg.data.message_id);
                message_record.text('未读');
                message_record.removeClass('kefu_message_' + msg.data.message_id);
                message_record.addClass('kefu_message_' + msg.data.id);
            } else {
                $('.kefu_message_' + msg.data.message_id).addClass('kf-text-red').text('失败');
                layer.msg(msg.data.msg)
            }
        },
        read_message_done: (msg) => {

            if (msg.data.record_id == 'all') {
                if (KeFu.session_id == msg.data.session_id) {
                    $('.kefu_message_status').addClass('kf-text-grey').text('已读');
                }
            } else {
                let message_record = $('.kefu_message_' + msg.data.record_id);
                message_record.addClass('kf-text-grey').text('已读');
            }

        },
        message_input: (msg) => {
            var input_status_display = parseInt(KeFu.config.input_status_display);
            if (input_status_display == 0) {
                return;
            } else if (input_status_display == 2 && KeFu.config.modulename != 'admin') {
                return;
            }

            if (KeFu.session_id == msg.data.session_id) {
                if (msg.data.type == 'input') {
                    $('#kefu_input_status').html('对方正在输入...');
                } else {
                    $('#kefu_input_status').html('');
                }
            }
        },
        upload_multipart: (msg) => {
            if (msg.data.upload_multipart) {
                KeFu.config.upload.multipart = msg.data.upload_multipart
            }
        }
    },
    ws_send: function (message) {

        if (!message) {
            message = {c: 'Message', a: 'ping'};
        }

        if (KeFu.ws.SocketTask && KeFu.ws.SocketTask.readyState == 1) {
            KeFu.ws.SocketTask.send(JSON.stringify(message));
        } else {
            // console.log('消息发送出错', message)
            KeFu.ws.ErrorMsg.push(message);
        }

    },
    edit_send_tis: function (tis, title = '') {
        $('.KeFu .modal-body #send_tis').html(tis);
        $('.KeFu .modal-body #send_tis').attr('title', title);
    },
    toggle_popover: function (toggle, content) {

        if (!KeFu.window_is_show && content && toggle == 'show') {
            $('#kefu_button').attr("data-content", content);
            $('#kefu_button').popover('show');
        } else {
            $('#kefu_button').popover('hide');
        }
    },
    // 切换显示的视图-聊天视图、轨迹视图、留言视图、用户名片视图
    toggle_window_view: function (show_view_id) {

        // 隐藏所有视图
        $('.kefu_window_view').hide();
        // 显示指定的视图
        $('#' + show_view_id).show();

        // 聊天
        if (show_view_id == 'kefu_scroll') {

            $('.modal-body .write').show();
            if (KeFu.config.modulename == 'admin') {
                $('.KeFu .modal-body .kefu-right .chat').height('calc(100% - 158px)');
            }

        } else {

            if (KeFu.config.modulename == 'admin') {
                KeFu.hide_tool_panel();
                $('.KeFu .modal-body .kefu-right .chat').height('calc(100% - 58px)');
            }
            $('.modal-body .write').hide();
        }

        // 轨迹
        if (show_view_id == 'kefu_trajectory') {
            $('#kefu_view_trajectory').fadeOut();
        } else {
            $('#kefu_view_trajectory').fadeIn();
        }

        // 名片
        if (show_view_id == 'kefu_user_card') {
            $('#kefu_view_user_card').fadeOut();
        } else {
            $('#kefu_view_user_card').fadeIn();
        }

    },
    toggle_window: function (toggle) {

        if (toggle == 'show') {

            KeFu.window_is_show = true;

            if (!KeFu.isPc() && KeFu.config.modulename != 'admin') {
                // 跳转到手机单页
                window.location.href = KeFu.buildUrl(KeFu.url, KeFu.config.modulename, 'mobile');
                return;
            }

            // 隐藏悬浮按钮
            KeFu.toggle_popover('hide');
            $('#kefu_button').fadeOut();

            // 检查 websocket 是否连接
            if (!KeFu.ws.SocketTask || KeFu.ws.SocketTask.readyState != 1) {
                KeFu.ConnectSocket();
            }

            // 隐藏邀请框
            $('.kefu_invite_box').fadeOut();

            $('#KeFuModal').modal({
                // keyboard: (KeFu.config.ecs_exit == '1') ? true:false,
                keyboard: false,
                show: true
            });

            if (KeFu.config.modulename != 'admin') {

                // 分配/获取客服->获取聊天记录
                var user_initialize = {
                    c: 'Message',
                    a: 'userInitialize',
                    data: {
                        fixed_csr: KeFu.fixed_csr
                    }
                };
                KeFu.ws_send(user_initialize);

                // 绑定窗口中的动态数据
                if (KeFu.config.slider_images.length > 0) {

                    var SlideLi = '<div id="kefu_chat_slide" class="carousel slide" data-ride="carousel">' +
                        KeFu.buildSlideLi(KeFu.config.slider_images.length);

                    var slider_inner = '<div class="carousel-inner">';
                    for (var i = 0; i < KeFu.config.slider_images.length; i++) {
                        slider_inner += KeFu.buildSlide(i, KeFu.config.slider_images[i]);
                    }
                    slider_inner += '</div></div>';

                    $('#kefu_chat_slide_f').html(SlideLi + slider_inner);
                    $('#kefu_chat_slide_f').fadeIn();

                    $('#kefu_chat_slide').carousel({
                        interval: 3000
                    })
                }

                if (KeFu.config.chat_introduces) {
                    $('.KeFu .modal-body .kefu-right .chat_introduces').html(KeFu.config.chat_introduces);
                } else {
                    $('.KeFu .modal-body .kefu-right .chat_introduces').fadeOut();
                }

                if (KeFu.config.announcement) {
                    $('.KeFu .modal-body .kefu-left .announcement #announcement').html(KeFu.config.announcement);
                } else {
                    $('.KeFu .modal-body .kefu-left .announcement').fadeOut();
                    $('.KeFu .modal-body .kefu-left .chat').height('calc(100% - 110px)');
                }
            } else {

                // 找到当前会话，去掉红点标记(直接重载当前会话)
                if (KeFu.session_id) {
                    // 聊天记录的请求
                    var load_message = {
                        c: 'Message',
                        a: 'chatRecord',
                        data: {
                            session_id: KeFu.session_id,
                            page: 1
                        }
                    };

                    KeFu.ws_send(load_message);
                    // 清理红点
                    var session = $("#session_panel [data-session='" + KeFu.session_id + "']");
                    session.children(".session_info_item").children(".unread_msg_count").eq(0).fadeOut();
                }

            }

            let send_tis_key = parseInt(KeFu.config.send_message_key) == 1 ? 'Enter' : 'Ctrl+Enter';
            KeFu.edit_send_tis('按下' + send_tis_key + '发送消息', '按下' + (send_tis_key == 'Enter' ? 'Ctrl+Enter' : 'Enter') + '换行');
        } else {
            $('#KeFuModal').modal('hide');
        }
    },
    bulidInviteBox: function () {
        if (!KeFu.window_is_show && !KeFu.csr) {
            var el = '<div class="kefu_invite_box" style="background: url(' + KeFu.config.invite_box_img + ') no-repeat;background-size: 100% 100%;">\
                <div class="invite_box_close">&times;</div>\
                <div class="bottom_button">\
                <div class="later">稍后再说</div>\
                <div class="consulting">现在咨询</div>\
                </div>\
            </div>';
            $("body").append(el);
        }

        if (parseInt(KeFu.config.only_first_invitation) == 1) {
            localStorage.setItem('kefu_auto_invitation', true);
        }

        $(document).on('click', '.invite_box_close,.kefu_invite_box .later', function () {
            $('.kefu_invite_box').fadeOut();
        });
    },
    bulidChat: function (window_html) {

        $("body").append(window_html);

        // 初始化 popover
        $('#kefu_button').popover({
            trigger: 'manual',
            template: '<div class="popover kefu_popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
            container: '.KeFu'
        });

        if (KeFu.browserType() == "FF") {
            $('#kefu_message').attr('contenteditable', true);
        }

        // 初始化文件md5库，该库已修改为支持getScript
        if (KeFu.config.upload.storage != 'local') {
            $.getScript(KeFu.buildUrl(KeFu.url, KeFu.config.modulename, 'spark'), function () {

                // 更新云存储multipart
                $(document).on('click', '#chatfile', function () {
                    var user_initialize = {
                        c: 'Message',
                        a: 'getUploadMultipart'
                    };
                    KeFu.ws_send(user_initialize);
                });

                KeFu.getFileMd5 = function (file, cb) {
                    var blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice,
                        chunkSize = 2097152,
                        chunks = Math.ceil(file.size / chunkSize),
                        currentChunk = 0,
                        spark = new $.fn.SparkMD5.ArrayBuffer(),
                        fileReader = new FileReader();

                    fileReader.onload = function (e) {
                        spark.append(e.target.result);
                        currentChunk++;
                        if (currentChunk < chunks) {
                            loadNext();
                        } else {
                            cb && cb(spark.end());
                        }
                    };

                    fileReader.onerror = function () {
                        layer.msg('文件读取错误，请重试！');
                    };

                    function loadNext() {
                        var start = currentChunk * chunkSize,
                            end = ((start + chunkSize) >= file.size) ? file.size : start + chunkSize;

                        fileReader.readAsArrayBuffer(blobSlice.call(file, start, end));
                    }

                    loadNext();
                }
            });
        }

        // 初始化图片粘贴/拖拽上传
        $.getScript(KeFu.buildUrl(KeFu.url, KeFu.config.modulename, 'pasteupload.js'), function () {

            var upload_msg_index = 0;

            $.fn.pasteText = function (text) {
                // $('#kefu_message').append(text);
                upload_msg_index = layer.msg('正在上传图片...', {'time': 0});
            };

            $.fn.insertToTextArea = function (filename, url) {
                var options = $(this).data("pu-options") || $.fn.pasteUploadImage.defaults;

                var img_node = document.createElement('img');
                img_node.src = url;
                img_node.className = 'record';
                KeFu.insertToMessage(img_node);

                layer.close(upload_msg_index)
            };

            //粘贴上传图片
            $.fn.pasteUploadImage.defaults = $.extend(true, $.fn.pasteUploadImage.defaults, {
                fileName: "file",
                appendMimetype: false,
                uploadingText: '上传中...',
                ajaxOptions: {
                    url: KeFu.config.upload.uploadurl,
                    beforeSend: function (jqXHR, settings) {
                        $.each(KeFu.config.upload.multipart, function (i, j) {
                            settings.data.append(i, j);
                        });
                        return true;
                    }
                },
                success: function (data, filename, file) {
                    var ret = KeFu.onUploadResponse(data);
                    var url = KeFu.config.upload.cdnurl + ret.data.url;
                    $(this).insertToTextArea(filename, url);
                    // 发送notify请求
                    if (KeFu.config.upload.storage != 'local') {
                        KeFu.getFileMd5(file, function (md5) {
                            var ajax_data = {
                                size: file.size,
                                name: file.name,
                                md5: md5,
                                type: file.type,
                                url: ret.data.url
                            };

                            for (let i in KeFu.config.upload.multipart) {
                                ajax_data[i] = KeFu.config.upload.multipart[i];
                            }

                            $.ajax({
                                url: KeFu.buildUrl(KeFu.url, KeFu.config.upload.storage, 'storage_notify'),
                                type: 'POST',
                                data: ajax_data
                            });
                        })
                    }
                    return false;
                },
                error: function (data, filename, file) {
                    console.log(data, filename, file);
                    layer.close(upload_msg_index)
                    layer.msg('图片上传失败，请重试~');
                }
            });

            $('#kefu_message').pasteUploadImage();
        });
    },
    buildPrompt: function (data, page) {
        if (page == 1) {
            $('#kefu_scroll').append('<div class="status"><span>' + data + '</span></div>');
        } else {
            $('#kefu_scroll').prepend('<div class="status"><span>' + data + '</span></div>');
        }
    },
    onUploadResponse: function (response) {
        try {
            var ret = typeof response === 'object' ? response : JSON.parse(response);
            if (!ret.hasOwnProperty('code')) {
                $.extend(ret, {code: -2, msg: response, data: null});
            }
        } catch (e) {
            var ret = {code: -1, msg: e.message, data: null};
        }
        return ret;
    },
    changeSession: function (session_id, why = 'session') {

        if (KeFu.config.modulename != 'admin') {
            return;
        }

        // 找到这个会话
        var element = $("#session_panel [data-session='" + session_id + "']");

        if (element.length == 0) {
            KeFu.session_id = 0;
            $('#session_user_name').html('无会话');
            return;
        }

        // 取消所有选择
        $('.person').removeClass("active");
        element.addClass("active");

        // 隐藏表情和快捷回复面板
        KeFu.hide_tool_panel();

        if ($('.KeFu .modal-body .kefu-left').css('display') != 'none') {
            $('#kefu_message').focus();
        }

        // 该对话所在分组是否展开
        let group = element.data('group');
        if (group == 'invitation' && !KeFu.group_show.invitation) {
            $('#heading_invitation a').click();
        } else if (group == 'dialogue' && !KeFu.group_show.dialogue) {
            $('#heading_dialogue a').click();
        } else if (group == 'recently' && !KeFu.group_show.recently) {
            $('#heading_recently a').click();
        }

        // 记录会话用户
        KeFu.session_user = element.data('session_user');
        KeFu.session_id = session_id;

        if (element.data('group') == 'invitation' || why == 'trajectory') {

            // 加载轨迹记录
            var load_log = {
                c: 'Message',
                a: 'trajectory',
                data: {
                    session_user: KeFu.session_user,
                    page: 1
                }
            };

            // 打开轨迹视图
            KeFu.toggle_window_view('kefu_trajectory');
        } else {

            // 打开聊天记录视图
            KeFu.toggle_window_view('kefu_scroll');

            // 清理红点
            element.children(".session_info_item").children(".unread_msg_count").eq(0).fadeOut();

            // 聊天记录的请求
            var load_log = {
                c: 'Message',
                a: 'chatRecord',
                data: {
                    session_id: session_id,
                    page: 1
                }
            };
        }

        if (KeFu.window_is_show) {
            KeFu.ws_send(load_log);
        } else {
            KeFu.window_show_event = function () {
                KeFu.ws_send(load_log);
            }
        }
    },
    buildSession: function (item, group) {

        var element_id = '#session_list_' + group;

        if ($("#session_panel [data-session='" + item.id + "']").length) {
            // 去掉该会话再添加最新的
            $("#session_panel [data-session='" + item.id + "']").remove();
        }

        $(element_id).prepend(
            '<li class="person" data-session="' + item.id + '" data-session_user="' + item.session_user + '" data-group="' + group + '">' +
            '<img class="person_avatar' + (item.online ? '' : ' person_head_gray') + '" src="' + item.avatar + '" alt="" />' +
            '<div class="session_info_item">' +
            '<span class="name">' + item.nickname + '</span>' +
            '<span class="time">' + item.last_time + '</span>' +
            '</div>\
            <div class="session_info_item">' +
            '<span class="preview"><span style="' + (item.online ? 'display:none;' : '') + '" class="session_user_status">[离线]</span><span class="last_message">' + item.last_message + '</span></span>' +
            (item.unread_msg_count ? '<span class="unread_msg_count">' + item.unread_msg_count + '</span>' : '<span class="unread_msg_count count_hide"></span>') +
            '</div>' +
            '</li>'
        );

        KeFu.group_session_is_none(group);
    },
    build_trajectory: function (data, page, build_way, pos = 'left') {

        if (build_way == 'log') {

            const log_data = new Map([
                [0, ['访问', data.note + '访问页面：' + data.url + '<br />来路：' + data.referrer]],
                [1, ['被邀请', '']],
                [2, ['开始对话', '']],
                [3, ['拒绝对话', '']],
                [4, ['客服分配', '']],
                [5, ['用户离开', '用户的workerman链接已断开']],
                [6, ['留言', '<a href="./kefu/leavemessage/index?id=' + data.note + '&title=测试&ref=addtabs">点击查看留言</a>']],
                [7, ['其他', '']],
                [8, ['转移会话', '']],
                ['default', ['未知', '']],
            ])

            var log = log_data.get(parseInt(data.log_type)) || log_data.get('default');

            var element = '\
            <dd class="pos-' + pos + ' clearfix">\
                <div class="circ"></div>\
                <div class="time">' + data.createtime + '</div>\
                <div class="events">\
                    <div class="events-header">' + log[0] + '</div>\
                        <div class="events-body">' + (log[1] ? log[1] : data.note) + '</div>\
                    </div>\
                </div>\
            </dd>\
            ';
        } else {
            var element = '<dt> ' + data + ' </dt>';
        }

        if (page == 1) {
            $('#kefu_trajectory_log').append(element);
        } else {
            $('#kefu_trajectory_log').prepend(element);
        }
    },
    buildRecord: function (data, page, message_id = 'none') {
        var message = '';

        if (data.message_type == 1) {
            message = KeFu.buildChatImg(data.message, '聊天图片', 'record');
        } else if (data.message_type == 2) {
            var file_name = data.message.split('.');
            var file_suffix = file_name[file_name.length - 1];
            message = KeFu.buildChatA(data.message, '点击下载 ' + file_suffix + ' 文件', 'record');
        } else if (data.message_type == 3) {
            KeFu.buildPrompt(data.message, page);
            return;
        } else if (data.message_type == 4 || data.message_type == 5) {
            message = KeFu.buildChatCard(data.message, data.message_type);
        } else {
            message = data.message;
        }

        var status_html = '';
        if (data.sender == 'me') {
            if (message_id == 'none') {
                message_id = data.id;
                var msg_status = parseInt(data.status);
                status_html = '<span class="kefu_message_status kefu_message_' + message_id + (msg_status == 0 ? '' : ' kf-text-grey') + '">' + (msg_status == 0 ? '未读' : '已读') + '</span>';
            } else {
                status_html = '<span class="kefu_message_status kefu_message_' + message_id + '"></span>';
            }
        }

        if (page == 1) {
            $('#kefu_scroll').append('<div class="bubble ' + data.sender + '">' + message + status_html + '</div>');
        } else {
            $('#kefu_scroll').prepend('<div class="bubble ' + data.sender + '">' + message + status_html + '</div>');
        }
    },
    /**
     * 构建商品或订单卡片
     */
    buildChatCard: function (message, message_type) {

        var message = message.split('#');
        var message_arr = [];
        for (let i in message) {
            let message_temp = message[i].split('=');
            if (typeof message_temp[1] != 'undefined') {
                message_arr[message_temp[0]] = message_temp[1];
            }
        }

        var card_url = (KeFu.config.modulename == 'admin') ? "javascript:layer.msg('卡片未启用,请检查窗口工具管理');" : 'javascript:;';
        var a_class_name = 'btn-addtabs';
        if (message_type == 4 && KeFu.config.toolbar.goods && KeFu.config.modulename == 'admin') {
            card_url = KeFu.config.toolbar.goods.card_url + '?ref=addtabs&id=' + message_arr['id'];
        } else if (message_type == 5 && KeFu.config.toolbar.order && KeFu.config.modulename == 'admin') {
            card_url = KeFu.config.toolbar.order.card_url + '?ref=addtabs&id=' + message_arr['id'];
        } else {
            a_class_name = '';
        }

        return '<a class="' + a_class_name + '" href="' + card_url + '">\n' +
            '   <div class="record_card">\n' +
            '       <img src="' + message_arr['logo'] + '" />\n' +
            '       <div class="record_card_body">\n' +
            '           <div class="record_card_title">' + message_arr['subject'] + '</div>\n' +
            (message_arr['note'] ? '<div class="record_card_note">' + message_arr['note'] + '</div>\n' : '') +
            '           <div class="record_card_price">\n' +
            (message_arr['price'] ? '<span>￥' + message_arr['price'] + '</span>\n' : '') +
            (message_arr['number'] ? '<span>x' + message_arr['number'] + '</span>\n' : '') +
            '           </div>\n' +
            '       </div>\n' +
            '   </div>\n' +
            '   </a>';
    },
    buildChatA: function (url, title, class_name) {
        return '<a target="_blank" title="' + url + '" class="' + class_name + '" href="' + url + '">' + title + '</a>';
    },
    buildChatImg: function (filename, facename, class_name = 'emoji') {
        return '<img class="' + class_name + '" src="' + filename + '" />';
    },
    buildSlideLi: function (count) {

        var slider_li_el = '<ol class="carousel-indicators" style="margin-bottom:0;bottom:10px !important;">';
        for (var i = 0; i < count; i++) {
            slider_li_el += '<li data-target="#kefu_chat_slide" data-slide-to=' + i + ' class="' + (i == 0 ? 'active' : '') + '"></li>';
        }
        return slider_li_el += '</ol>';
    },
    buildSlide: function (index, img_scr) {
        return '\
            <div class="item ' + (index == 0 ? 'active' : '') + '">\
                <img src="' + img_scr + '" alt="" />\
            </div>';
    },
    /**
     * 构建各种Url，拼接参数
     */
    buildUrl: function (url, modulename, type = 'ws', wsport = 1818) {

        var protocol = window.location.protocol + '//';
        var port = window.location.port;
        port = port ? ':' + port : '';

        // 用户的身份通过 KeFu.config.kefu_token 来识别
        var token = KeFu.token_list.kefu_token ? '&token=' + KeFu.token_list.kefu_token : '';

        // 游客的token
        var kefu_tourists_token = localStorage.getItem('kefu_tourists_token');
        kefu_tourists_token = kefu_tourists_token ? '&kefu_tourists_token=' + kefu_tourists_token : '';

        var goods_and_order = () => {

            var data_api_url = KeFu.config.toolbar[type].data_api;
            var reg = new RegExp("(^https?:\/\/)", "i");
            if (data_api_url.search(reg) === -1) {
                return protocol + url + port + data_api_url + '?modulename=' + modulename + token;
            } else {
                return data_api_url + '?modulename=' + modulename + token;
            }
        }

        var buildFun = new Map([
            ['ws', () => {
                protocol = parseInt(KeFu.config.wss_switch) == 1 ? 'wss://' : 'ws://';

                return protocol + url + ':' + wsport + '?modulename=' +
                    modulename + token + kefu_tourists_token;
            }],
            ['initialize', () => {
                return protocol + url + port + '/addons/kefu/index/initialize?modulename=' +
                    modulename + '&referrer=' + document.referrer + kefu_tourists_token;
            }],
            ['upload', () => {
                return KeFu.config.upload.uploadurl + '?modulename=' + modulename + kefu_tourists_token;
            }],
            ['spark', () => {
                if (KeFu.config.__CDN__) {
                    return KeFu.config.__CDN__ + '/assets/addons/kefu/js/spark.js';
                }
                return protocol + url + port + '/assets/addons/kefu/js/spark.js';
            }],
            ['storage_notify', () => {
                return protocol + url + port + '/addons/' + modulename + '/index/notify';
            }],
            ['load_message_prompt', () => {
                if (KeFu.config.upload.cdnurl) {
                    return KeFu.config.upload.cdnurl + KeFu.config.ringing;
                }

                return protocol + url + port + '/addons/kefu/index/loadMessagePrompt?modulename=' +
                    modulename + kefu_tourists_token;
            }],
            ['pasteupload.js', () => {
                if (KeFu.config.__CDN__) {
                    return KeFu.config.__CDN__ + '/assets/addons/kefu/js/jquery.pasteupload.js';
                }
                return protocol + url + port + '/assets/addons/kefu/js/jquery.pasteupload.js';
            }],
            ['mobile', () => {
                return protocol + url + port + '/addons/kefu/index/mobile?fixed_csr=' + KeFu.fixed_csr;
            }],
            ['goods', goods_and_order],
            ['order', goods_and_order],
            ['default', () => {
                return protocol + url + port
            }]
        ]);

        let action = buildFun.get(type) || buildFun.get('default')
        return action.call(this);
    },
    edit_online_status: function (user_id, status) {

        var element = $("#session_panel [data-session_user='" + user_id + "']");

        if (status) {
            $(element).children(".person_avatar").removeClass("person_head_gray");
            element.children(".session_info_item").find(".session_user_status").eq(0).fadeOut();
        } else {
            $(element).children(".person_avatar").addClass("person_head_gray");
            element.children(".session_info_item").find(".session_user_status").eq(0).fadeIn();
        }
    },
    disableSelection: function (target) {

        if (typeof target.onselectstart != "undefined") {
            target.onselectstart = function () {
                return false
            };
        } else if (typeof target.style.MozUserSelect != "undefined") {
            target.style.MozUserSelect = "none";
        } else {
            target.οnmοusedοwn = function () {
                return false;
            }
            target.style.cursor = "default";
        }

    },
    sendMessage: function (message, message_type) {

        // 检查 websocket 是否连接
        if (!KeFu.ws.SocketTask || KeFu.ws.SocketTask.readyState != 1) {
            layer.msg('网络链接异常，请刷新重试~');
            return;
        }

        if (typeof KeFu.session_id == 'string' && KeFu.session_id.indexOf('invitation') !== -1) {
            layer.msg('该访客还未接受会话请求哦~');
            return;
        }

        if (!KeFu.session_id) {
            layer.msg('请选择一个会话~');
            return;
        }

        var message_id = new Date().getTime() + KeFu.session_id + Math.floor(Math.random() * 10000);
        var load_message = {
            c: 'Message',
            a: 'sendMessage',
            data: {
                message: message,
                message_type: message_type,
                session_id: KeFu.session_id,
                token: KeFu.token_list.kefu_token ? KeFu.token_list.kefu_token : '', // 发消息时检测用户登录态是否过期
                modulename: KeFu.config.modulename,
                message_id: message_id
            }
        };

        KeFu.ws_send(load_message);

        var data = {
            sender: 'me',
            message: (message_type == 1 || message_type == 2) ? KeFu.config.upload.cdnurl + message : message,
            message_type: message_type
        }
        KeFu.buildRecord(data, 1, message_id);

        if (message_type == 1) {
            message = '[图片]';
        } else if (message_type == 2) {
            message = '[链接]';
        } else {
            message = message.replace(/<img(.*?)src=(.*?)>/g, "[图片]");
            $('#kefu_message').html('');
        }

        if (KeFu.config.modulename == 'admin') {

            // 修改该会话的最后消息
            let session = $("#session_panel [data-session='" + KeFu.session_id + "']");
            session.children(".session_info_item").children(".time").eq(0).html(KeFu.get_format_session_time());
            session.children(".session_info_item").find(".last_message").eq(0).html(message);

            // 移动会话到会话中的第一位
            let first_session = $('#session_list_dialogue li');
            if (first_session.length) {
                // 分组中已有对话
                first_session.eq(0).before(session);
            } else {
                // 分组中没有对话
                $('#session_list_dialogue').prepend(session);

                // 清理没有会话的提示文字
                KeFu.group_session_is_none('dialogue');
            }

            // 展开分组
            if (!KeFu.group_show.dialogue) {
                $('#heading_dialogue a').click();
            }

            if ($('#kefu_scroll').children('.status').children('span').eq(0).html() == '还没有消息') {
                $('#kefu_scroll').children('.status').children('span').eq(0).html(KeFu.get_format_session_time());
            }
        }

        $('#kefu_scroll').scrollTop($('#kefu_scroll')[0].scrollHeight);
    },
    playSound: function () {
        KeFu.audio.source = KeFu.audio.context.createBufferSource();
        KeFu.audio.source.buffer = KeFu.audio.buffer;
        KeFu.audio.source.loop = false;
        KeFu.audio.source.connect(KeFu.audio.context.destination);
        KeFu.audio.source.start(0); //立即播放
    },
    loadAudioFile: function (url) {
        var xhr = new XMLHttpRequest(); //通过XHR下载音频文件
        xhr.open('GET', url, true);
        xhr.responseType = 'arraybuffer';
        xhr.onload = function (e) { //下载完成

            KeFu.audio.context.decodeAudioData(this.response,
                function (buffer) { //解码成功时的回调函数
                    KeFu.audio.buffer = buffer;
                    KeFu.playSound();
                },
                function (e) { //解码出错时的回调函数
                    console.log('音频解码失败', e);
                });
        };
        xhr.send();
    },
    new_message_prompt: function (event) {

        if (KeFu.config.is_shake) {

            $(event).addClass('kefu-shake-horizontal');
            setTimeout(function () {
                $(event).removeClass('kefu-shake-horizontal');
            }, 400);
        }

        if (KeFu.audio.buffer) {
            KeFu.playSound();
        } else {
            let url = KeFu.buildUrl(KeFu.url, 'index', 'load_message_prompt');

            KeFu.loadAudioFile(url);
        }
    },
    kefu_blacklist: function (code, session_user, blacklist = null) {
        // 修改用户黑名单状态
        // code:1=屏蔽成功,2=解除屏蔽成功,3=通过 blacklist 判断屏蔽状态

        if (code == 3) {

            $('#kefu_blacklist').html(blacklist ? '取消拉黑' : '拉黑名单');
            return;
        }

        if (session_user == KeFu.session_user) {
            if (code == 1) {
                $('#kefu_blacklist').html('取消拉黑');
            } else if (code == 2) {
                $('#kefu_blacklist').html('拉黑名单');
            }
        }

    },
    // 处理分组的没有会话提示
    group_session_is_none: function (group) {
        var element_id = '#session_list_' + group;

        if ($(element_id + ' li').length) {
            $(element_id).children('.none_session').fadeOut();
        } else {
            $(element_id).children('.none_session').fadeIn();
            KeFu.session_group_red_dot(group, false);
        }
    },
    // 移动光标到最未
    po_last: function () {
        var obj = $('#kefu_message')[0];
        if (window.getSelection) {//ie11 10 9 ff safari
            obj.focus(); //解决ff不获取焦点无法定位问题
            var range = window.getSelection();//创建range
            range.selectAllChildren(obj);//range 选择obj下所有子内容
            range.collapseToEnd();//光标移至最后
        } else if (document.selection) {//ie10 9 8 7 6 5
            var range = document.selection.createRange();//创建选择对象
            // var range = document.body.createTextRange();
            range.moveToElementText(obj);//range定位到obj
            range.collapse(false);//光标移至最后
            range.select();
        }
    },
    // 光标位置插入元素
    insertToMessage: function (obj) {
        // 获取编辑框对象
        var Message = $('#kefu_message')[0]
        // 编辑框设置焦点
        Message.focus();
        // 获取选定对象
        var selection = getSelection()
        // 判断是否有最后光标对象存在

        // 获取包含当前节点的文档片段
        var range = selection.getRangeAt(0)

        // 将创建的文档片段插入到光标处
        range.insertNode(obj)
        // 光标对象的范围界定为新建的节点
        // range.selectNodeContents(obj)
        // 光标位置定位在节点的最大长度
        // range.setStart(obj, obj.length||1)
        // 使光标开始和光标结束重叠
        range.collapse(false)
        // 清除选定对象的所有光标对象
        // selection.removeAllRanges()
        // 插入新的光标对象
        // selection.addRange(range)
    },
    /*
    * 隐藏菜单工具面板
    */
    hide_tool_panel: function (show_id = '') {
        var tool_panel_action = {
            'none': () => {
                $('#kefu_link_form,#kefu_fast_reply_list,#kefu_emoji,#goods_select_model').hide();
            },
            'kefu_link_form': () => {
                $('#kefu_fast_reply_list,#kefu_emoji,#goods_select_model').hide();
            },
            'kefu_fast_reply_list': () => {
                $('#kefu_link_form,#kefu_emoji,#goods_select_model').hide();
            },
            'kefu_emoji': () => {
                $('#kefu_link_form,#kefu_fast_reply_list,#goods_select_model').hide();
            },
            'goods_select_model': () => {
                $('#kefu_link_form,#kefu_fast_reply_list,#kefu_emoji').hide();
            }
        }

        var action = tool_panel_action[show_id] || tool_panel_action['none'];
        action.call(this);
    },
    /*
    * 统一注册事件
    */
    eventReg: function () {

        // 点击商品或订单卡片关闭会话窗口
        $(document).on('click', '.KeFu .chat .bubble .record_card', function () {
            if ($(this).parent().hasClass('btn-addtabs')) {
                $('#KeFuModal').modal('hide');
            }
        })

        // 提交留言
        $(document).on('click', '#kefu_leave_message form button', function (event) {

            var form_data = {};
            var t = $('#kefu_leave_message form').serializeArray();
            $.each(t, function () {
                form_data[this.name] = this.value;
            });

            if (!form_data['contact']) {
                layer.msg('联系方式不能为空哦~');
                return false;
            }

            var leave_message = {
                c: 'Message',
                a: 'leaveMessage',
                data: form_data
            };
            KeFu.ws_send(leave_message);
            return false;
        });

        // 窗口大小变化时，隐藏按钮提示
        $(window).on('resize', function (e) {
            if (KeFu.resize_load <= 2) {
                console.log('窗口大小发生变化，已恢复按钮至初始位置')
                $("#kefu_button").css({
                    "top": '80%',
                    "left": 'unset'
                });
                KeFu.toggle_popover('hide');
            }
            KeFu.resize_load += 1;
        });

        // 用户名片保存按钮
        $(document).on('click', '#kefu_user_card form button', function (event) {

            if (!KeFu.session_user) {
                layer.msg('用户找不到啦~');
                return;
            }

            var form_data = {};
            var t = $('#kefu_user_card form').serializeArray();
            $.each(t, function () {
                form_data[this.name] = this.value;
            });

            let user_card_done = {
                c: 'Message',
                a: 'userCard',
                data: {
                    session_user: KeFu.session_user,
                    form_data: form_data,
                    action: 'done'
                }
            };
            KeFu.ws_send(user_card_done);
            return false;
        });

        // 点击轻提示和KeFu悬浮球的事件
        $(document).on('click', '#kefu_button,.kefu_invite_box .consulting,.kefu_popover', function () {

            if (KeFu.config.modulename == 'admin' && $(this).hasClass('kefu_popover')) {
                // 打开最后发送消息的用户的会话窗口
                if (KeFu.last_sender && parseInt(KeFu.last_sender) !== KeFu.session_id) {
                    KeFu.changeSession(KeFu.last_sender);
                }
            }

            if (!KeFu.clickkefu_button) {
                KeFu.toggle_window('show');
            }
        });

        $(document).on('click', '.bubble img', function (e) {

            var img_obj = $(e.target);
            if (img_obj.hasClass('emoji')) {
                return;
            }
            img_obj = img_obj[0];

            KeFu.allowed_close_window = false;// 按下ecs不允许关闭会话窗口

            layer.photos({
                photos: {
                    "title": "聊天图片预览",
                    "id": "record",
                    data: [
                        {
                            "src": img_obj.src
                        }
                    ]
                },
                end: function () {
                    // 图片预览已关闭
                    KeFu.allowed_close_window = true;
                }, anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
            });

        });

        // 按键监听
        $(document).on('keyup', function (event) {

            // console.log('当前按钮的code:', event.keyCode);

            // 对打开会话窗口的监听
            // 打开会话窗口快捷键[ctrl + /],若需修改，请拿到对应键的keyCode替换下一行的191即可，191代表[/]键的keyCode
            if (event.keyCode == 191 && event.ctrlKey) {

                if (KeFu.last_sender) {
                    if (parseInt(KeFu.last_sender) === KeFu.session_id) {
                        // 展开分组
                        if (!KeFu.group_show.dialogue) {
                            $('#heading_dialogue a').click();
                        }
                    }

                    KeFu.changeSession(KeFu.last_sender.toString());
                    KeFu.last_sender = null;
                } else if (KeFu.window_is_show) {
                    KeFu.toggle_window('hide');
                }

                // 需先切换会话再打开窗口
                if (!KeFu.window_is_show) {
                    KeFu.toggle_window('show');
                }
                return;
            }
            // 对ecs键的监听
            else if (event.keyCode == 27 && KeFu.window_is_show) {
                if (parseInt(KeFu.config.ecs_exit) == 1 && KeFu.allowed_close_window) {
                    $('#KeFuModal').modal('hide');
                } else {
                    layer.closeAll();
                }
            }

        });

        // 拖动悬浮球的事件
        $(document).on('mousedown', '#kefu_button', function (e) {

            KeFu.toggle_popover('hide');
            KeFu.clickkefu_button = false;
            document.onselectstart = function () {
                return false;
            };//解决拖动会选中文字的问题
            document.ondragstart = function () {
                return false;
            };
            $(document).find("iframe").css("pointer-events", "none");
            KeFu.fast_move = true;
            KeFu.fast_x = e.pageX - parseInt($("#kefu_button").css("left"));
            KeFu.fast_y = e.pageY - parseInt($("#kefu_button").css("top"));

            $(document).on('mousemove', function (e) {

                if (KeFu.fast_move) {

                    var x = e.pageX - KeFu.fast_x;
                    var y = e.pageY - KeFu.fast_y;

                    if (Math.abs(parseInt($("#kefu_button").css("left")) - x) + Math.abs(parseInt($("#kefu_button").css("top")) - y) > 7) {
                        KeFu.clickkefu_button = true;
                    }

                    // 保存按钮位置
                    var kefu_button_coordinate = [y, x];
                    localStorage.setItem("kefu_button_coordinate", kefu_button_coordinate);

                    $("#kefu_button").css({"top": y, "left": x});
                }

            }).mouseup(function () {
                document.onselectstart = null;
                document.ondragstart = null;
                $(document).find("iframe").css("pointer-events", "auto");
                KeFu.fast_move = false;
            });

        });

        // 展开分组
        $(document).on('show.bs.collapse', '.dialogue_panel,.invitation_panel,.recently_panel', function (e) {

            if ($(e.currentTarget).hasClass('dialogue_panel')) {

                KeFu.group_show.dialogue = true;
                KeFu.session_group_red_dot('dialogue', false);

                $('.dialogue_panel').on('hide.bs.collapse', function () {
                    KeFu.group_show.dialogue = false;
                });
            } else if ($(e.currentTarget).hasClass('invitation_panel')) {

                KeFu.group_show.invitation = true;
                KeFu.session_group_red_dot('invitation', false);

                $('.invitation_panel').on('hide.bs.collapse', function () {
                    KeFu.group_show.invitation = false;
                });
            } else if ($(e.currentTarget).hasClass('recently_panel')) {
                KeFu.group_show.recently = true;
                KeFu.session_group_red_dot('recently', false);

                $('.recently_panel').on('hide.bs.collapse', function () {
                    KeFu.group_show.recently = false;
                });
            }

        });

        // 客服代表改变状态
        $(document).on('click', '#kefu_csr_status ul li', function (e) {

            var load_message = {
                c: 'Message',
                a: 'csrChangeStatus',
                data: {
                    status: $(e.target).data('status')
                }
            };
            KeFu.ws_send(load_message);

        });

        // 隐藏窗口时
        $(document).on('hidden.bs.modal', '#KeFuModal', function (e) {
            $('#kefu_button').fadeIn();
            KeFu.window_is_show = false;
        });

        // 显示窗口时
        $(document).on('shown.bs.modal', '#KeFuModal', function (e) {
            KeFu.window_is_show = true;
            $('#kefu_scroll').scrollTop($('#kefu_scroll')[0].scrollHeight);

            if (!localStorage.getItem('kefu_new_user')) {
                localStorage.setItem('kefu_new_user', true);
            }

            if (typeof KeFu.window_show_event == 'function') {
                KeFu.window_show_event();
                KeFu.window_show_event = null;
            }

        });

        // 显示商品和订单选择面板
        $(document).on('click', '.write_top .goods,.write_top .order', function (e) {
            var panel_name = $(e.target).hasClass('goods') ? 'goods' : 'order';
            KeFu.hide_tool_panel('goods_select_model');

            if ($('#goods_select_model').css('display') == 'block') {
                $('#goods_select_model').fadeOut();
                return;
            } else {
                $('#goods_select_model').fadeIn();
            }

            var api_url = KeFu.buildUrl(KeFu.url, 'index', panel_name);
            var index = layer.load(0, {shade: false});
            $.ajax(api_url, {
                success: res => {
                    if (res.code == 1) {
                        $('#goods_select_model .project_list').html('');
                        for (let i in res.data) {

                            var input_value = '';
                            for (let y in res.data[i]) {
                                input_value += y + '=' + res.data[i][y] + '#';
                            }

                            let project_item = '<label class="record_card">\n' +
                                '<img src="' + res.data[i].logo + '" />\n' +
                                '<div class="record_card_body">\n' +
                                '   <div class="record_card_title">' + res.data[i].subject + '</div>\n' +
                                (res.data[i].note ? '<div class="record_card_note">' + res.data[i].note + '</div>\n' : '') +
                                '   <div class="record_card_price">\n' +
                                (res.data[i].price ? '<span>￥' + res.data[i].price + '</span>\n' : '') +
                                (res.data[i].number ? '<span>x' + res.data[i].number + '</span>\n' : '') +
                                '   </div>\n' +
                                '</div>\n' +
                                '<input name="' + panel_name + '" type="radio" value="' + input_value + '" />\n' +
                                '</label>';
                            $('#goods_select_model .project_list').append(project_item);
                        }
                    } else {
                        layer.msg(res.msg)
                    }
                },
                error: res => {
                    layer.msg('加载失败,请重试~');
                },
                complete: res => {
                    layer.close(index)
                }
            });
        });

        // 选择订单或商品
        $(document).on('click', '.KeFu .modal-body #goods_select_model input', function (e) {
            KeFu.sendMessage($(e.target).val(), (e.target.name == 'goods') ? 4 : 5);
            $('#goods_select_model').hide();
            KeFu.po_last();
        })

        // 显示表情选择面板
        $(document).on('click', '.write_top .smiley', function (e) {
            KeFu.hide_tool_panel('kefu_emoji');
            $('#kefu_emoji').fadeToggle();
            // 获取焦点
            KeFu.po_last();
        });

        // 显示输入链接面板
        $(document).on('click', '.write_top .link,#kefu_link_form .btn-default', function (e) {
            KeFu.hide_tool_panel('kefu_link_form');
            $('#kefu_link_form').fadeToggle();
            $('#kefu_link_form_url').focus().select();
        });

        // 确认链接
        $(document).on('click', '#kefu_link_form .btn-success', function (e) {
            var kefu_link_form_ins = $('#kefu_link_form_ins').val()
            var kefu_link_form_url = $('#kefu_link_form_url').val()
            kefu_link_form_ins = kefu_link_form_ins ? kefu_link_form_ins : kefu_link_form_url;

            $('#kefu_link_form').fadeOut();
            $('#kefu_message').append(KeFu.buildChatA(kefu_link_form_url, kefu_link_form_ins, 'link'));
            KeFu.po_last();
        });

        // 选择表情
        $(document).on('click', '#kefu_emoji img', function (e) {
            $('#kefu_message').append($(e.target).clone());
            $('#kefu_emoji').fadeOut();
            KeFu.po_last()
        });

        // 用户点击聊天记录窗口，隐藏所有工具面板
        $(document).on('click', '#kefu_scroll', function () {
            KeFu.hide_tool_panel();
        });

        // 用户选择了文件
        $(document).on('change', '#chatfile', function (e) {
            var file = $('#chatfile')[0].files[0];

            if (!file) {
                return;
            }

            KeFu.hide_tool_panel();

            // 上传文件
            var formData = new FormData();
            formData.append("file", file);
            if (KeFu.config.upload.multipart) {
                for (let i in KeFu.config.upload.multipart) {
                    formData.append(i, KeFu.config.upload.multipart[i]);
                }
            }

            $.ajax({
                url: KeFu.config.upload.uploadurl,
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res.code == 1) {

                        // 发送notify请求
                        if (KeFu.config.upload.storage != 'local') {
                            KeFu.getFileMd5(file, function (md5) {
                                var ajax_data = {
                                    size: file.size,
                                    name: file.name,
                                    md5: md5,
                                    type: file.type,
                                    url: res.data.url
                                };

                                for (let i in KeFu.config.upload.multipart) {
                                    ajax_data[i] = KeFu.config.upload.multipart[i];
                                }

                                $.ajax({
                                    url: KeFu.buildUrl(KeFu.url, KeFu.config.upload.storage, 'storage_notify'),
                                    type: 'POST',
                                    data: ajax_data
                                });
                            })
                        }

                        var file_name = res.data.url.split('.');
                        var file_suffix = file_name[file_name.length - 1];

                        if (file_suffix == 'png' ||
                            file_suffix == 'jpg' ||
                            file_suffix == 'gif' ||
                            file_suffix == 'jpeg') {

                            KeFu.sendMessage(res.data.url, 1);
                        } else {
                            KeFu.sendMessage(res.data.url, 2);
                        }
                    } else {
                        layer.msg(res.msg);
                    }
                },
                error: function (e) {
                    layer.msg('文件上传失败,请重试！');
                },
                complete: function () {
                    $('#chatfile').val('');
                }
            })
        });

        // 禁止回车键换行
        $(document).on('keypress', '#kefu_message', function (event) {
            if (parseInt(KeFu.config.send_message_key) == 1 && event.keyCode == "13" && !event.ctrlKey) {
                event.preventDefault()
            }
        });

        if (KeFu.browserType() == "FF") {

            // 过滤输入框中的html标签
            $(document).on('paste', '#kefu_message', function (e) {
                setTimeout(function () {
                    var message = $(e.target).html();
                    $(e.target).html(message.replace("/<(?!img|div).*?>/g", ''));
                    KeFu.po_last();
                }, 100);
            });
        }

        // 按键发送消息监听
        $(document).on('keyup', '#kefu_message', function (event) {

            var message = $(event.currentTarget).html();

            if (parseInt(KeFu.config.send_message_key) == 1 && event.keyCode == "13" && !event.ctrlKey) {
                if (message) {
                    KeFu.sendMessage(message, 0);
                }
            } else if (parseInt(KeFu.config.send_message_key) == 0 && event.keyCode == "13" && event.ctrlKey) {
                if (message) {
                    KeFu.sendMessage(message, 0);
                }
            } else if (parseInt(KeFu.config.send_message_key) == 1 && event.keyCode == "13" && event.ctrlKey) {

                // Enter发送消息时，用户按下了ctrl+Enter
                if (KeFu.browserType() == "IE" || KeFu.browserType() == "Edge") {
                    $(event.currentTarget).html(message + "<div></div>");
                } else if (KeFu.browserType() == "FF") {
                    $(event.currentTarget).html(message + "<br/><br/>");
                } else {
                    $(event.currentTarget).html(message + "<div><br/></div>");
                }

                // 获得焦点-光标挪到最后
                KeFu.po_last()
            }

            event.preventDefault()
        });

        // 切换会话
        $(document).on('click', '#session_panel li,#session_list_search li', function (e) {

            if ($('.KeFu .modal-body .kefu-right').css('display') == 'none') {
                $('.KeFu .modal-body .kefu-right').fadeIn();
                $('.KeFu .modal-body .kefu-left').fadeOut();
            }

            KeFu.changeSession($(e.currentTarget).data('session'));

            if ($(e.currentTarget).data('group') == 'search') {
                $('#session_list_search').fadeOut();
            }
        });

        // 手机版聊天窗口兼容
        $(document).on('hide.bs.modal', '#KeFuModal', function (e) {
            if ($('.KeFu .modal-body .kefu-left').css('display') == 'none') {
                $('.KeFu .modal-body .kefu-left').fadeIn();
                $('.KeFu .modal-body .kefu-right').fadeOut();
                return false;
            }
        });

        // 显示轨迹
        $(document).on('click', '#kefu_view_trajectory', function (e) {
            if (!KeFu.session_id) {
                layer.msg('请选择会话~');
                return;
            }
            KeFu.changeSession(KeFu.session_id, 'trajectory');
        });

        // 显示用户名片
        $(document).on('click', '#kefu_view_user_card', function (e) {

            if (!KeFu.session_user) {
                layer.msg('请选择会话~');
                return;
            }

            // 加载用户名片
            KeFu.toggle_window_view('kefu_user_card');

            var kefu_blacklist = {
                c: 'Message',
                a: 'userCard',
                data: {
                    session_user: KeFu.session_user
                }
            };
            KeFu.ws_send(kefu_blacklist);

        });

        // 拉黑名单
        $(document).on('click', '#kefu_blacklist', function () {
            if (!KeFu.session_user) {
                layer.msg('请选择会话~');
                return;
            }
            var kefu_blacklist = {
                c: 'Message',
                a: 'blacklist',
                data: {
                    session_user: KeFu.session_user
                }
            };
            KeFu.ws_send(kefu_blacklist);
        });

        // 加载更多聊天记录和轨迹
        document.addEventListener('scroll', function (event) {

            if ($(event.target).scrollTop() == 0 && KeFu.chat_record_page != 'done') {
                if (event.target.id == 'kefu_scroll') {

                    if (!KeFu.session_id) {
                        return;
                    }

                    // 加载历史聊天记录
                    var load_message = {
                        c: 'Message',
                        a: 'chatRecord',
                        data: {
                            session_id: KeFu.session_id,
                            page: KeFu.chat_record_page
                        }
                    };
                    KeFu.record_scroll_height = $('#kefu_scroll')[0].scrollHeight;

                    KeFu.ws_send(load_message);
                } else if (event.target.id == 'kefu_trajectory') {

                    if (!KeFu.session_user) {
                        return;
                    }

                    // 加载轨迹记录
                    var load_log = {
                        c: 'Message',
                        a: 'trajectory',
                        data: {
                            session_user: KeFu.session_user,
                            page: KeFu.chat_record_page
                        }
                    };
                    KeFu.record_scroll_height = $('#kefu_trajectory')[0].scrollHeight;

                    KeFu.ws_send(load_log);
                }
            }

        }, true);

        // 右键菜单
        $(document).on('contextmenu', '.person', function (e) {

            KeFu.select_session_user = $(e.currentTarget).data('session_user');

            var popupmenu = $('#kefu_menu');
            popupmenu.fadeOut();
            $('.kefu_edit_user_nickname').remove();
            $('.kefu_transfer_session').remove();

            let l = ($(document).width() - e.clientX) < popupmenu.width() ? (e.clientX - popupmenu.width()) : e.clientX;
            let t = ($(document).height() - e.clientY) < popupmenu.height() ? (e.clientY - popupmenu.height()) : e.clientY;
            popupmenu.css({left: l, top: t}).fadeIn();

            if ($(e.currentTarget).data('group') == 'invitation') {
                $('#kefu_menu .invitation').fadeIn();
                $('#kefu_menu .transfer').fadeOut();
            } else {
                $('#kefu_menu .invitation').fadeOut();
                $('#kefu_menu .transfer').fadeIn();
            }

            e.preventDefault();
        }).click(function () {
            // 菜单的隐藏
            $('#kefu_menu').fadeOut();
        });

        // 会话右键菜单 删除会话 & 邀请对话 & 转接会话 & 修改昵称
        $(document).on('click', '.kefu_menu_item', function (e) {
            var action = $(e.currentTarget).data('action');

            if (KeFu.select_session_user) {

                if (action == 'del') {
                    $("#session_panel [data-session_user='" + KeFu.select_session_user + "']").remove();
                }

                if (action == 'edit_nickname') {

                    var session = $("#session_panel [data-session_user='" + KeFu.select_session_user + "']");
                    var nickname = session.children(".session_info_item").children(".name").eq(0).html();

                    // 显示修改昵称的操作面板
                    var tpl = '\
                    <div class="kefu_edit_user_nickname" data-edit_nickname_user="' + KeFu.select_session_user + '">\
                        <div class="form-group">\
                            <label>修改游客昵称</label>\
                            <input type="text" id="new_nickname" class="form-control" value="' + nickname + '" />\
                            <div class="transfer_session_buttons">\
                                <button type="button" id="edit_user_nickname_cancel" class="btn btn-default btn-sm">取消</button>\
                                <button type="button" id="edit_user_nickname_ok" class="btn btn-success btn-sm">确定</button>\
                            </div>\
                        </div>\
                    </div>';

                    session.after(tpl);

                    $(document).on('click', '#edit_user_nickname_cancel', function (e) {
                        $('.kefu_edit_user_nickname').remove();
                    });

                    $(document).on('click', '#edit_user_nickname_ok', function (e) {

                        var edit_nickname_user = $('.kefu_edit_user_nickname').data('edit_nickname_user');
                        var new_nickname = $('#new_nickname').val();

                        if (edit_nickname_user && new_nickname) {
                            var action_session = {
                                c: 'Message',
                                a: 'actionSession',
                                data: {
                                    action: 'edit_nickname',
                                    session_user: edit_nickname_user,
                                    new_nickname: new_nickname
                                }
                            };
                            KeFu.ws_send(action_session);
                        }

                        $('.kefu_edit_user_nickname').remove();
                    });
                    return;
                }

                var action_session = {
                    c: 'Message',
                    a: 'actionSession',
                    data: {
                        action: action,
                        session_user: KeFu.select_session_user
                    }
                };
                KeFu.ws_send(action_session);

            } else {
                layer.msg('会话找不到啦~');
            }
        });

        // 搜索会话
        $(document).on('keyup', '#kefu_search_input', function (event) {

            if (KeFu.config.modulename != 'admin') {
                return;
            }

            if (event.keyCode == "13") {

                var search_key = $('#kefu_search_input').val();

                if (search_key.length <= 0) {

                    // 无搜索词，且预选框已显示，隐藏预选框
                    if ($('#session_list_search').css('display') != 'none') {
                        $('#session_list_search').fadeOut();
                        KeFu.search_select_id = '';
                    } else {
                        layer.msg('请输入要查找的用户！');
                    }

                    return;
                } else {

                    // 有预选人,选中该会话
                    if ($('#session_list_search').css('display') != 'none' && KeFu.search_select_id) {
                        KeFu.changeSession(KeFu.search_select_id);
                        $('#session_list_search').fadeOut();
                        KeFu.search_select_id = '';
                        return;
                    }
                }

                var load_message = {
                    c: 'Message',
                    a: 'searchUser',
                    data: search_key
                };

                KeFu.ws_send(load_message);
            } else if (event.keyCode == '38') {
                KeFu.search_primary--;
                KeFu.setSelectedItem();
            } else if (event.keyCode == '40') {
                KeFu.search_primary++;
                KeFu.setSelectedItem();
            }

            event.preventDefault();

            // 搜索框失去焦点
            $(document).on('blur', '#kefu_search_input', function () {
                setTimeout(function () {
                    // 等待点击事件冒泡-防止搜索结果中的蓝色聊天文字不能被点击
                    $('#session_list_search').fadeOut();
                    KeFu.search_select_id = 0;
                }, 250);
            });
        });

        /*快捷回复面板*/
        $(document).on('click', '.kefu_fast_reply', function () {
            KeFu.hide_tool_panel('kefu_fast_reply_list');
            $('#kefu_fast_reply_list').fadeToggle();
        });

        /*选择快捷回复*/
        $(document).on('click', '.kefu_fast_reply_list tr', function (e) {
            var reply_id = $(e.currentTarget).data('id');
            var content = KeFu.fast_reply[reply_id].content;
            KeFu.sendMessage(content, 0);
            KeFu.po_last();
            $('#kefu_fast_reply_list').fadeOut();
        });

        KeFu.messageInputEventReg();
    },
    messageInputEventReg: function () {

        var input_status_display = parseInt(KeFu.config.input_status_display);
        if (input_status_display == 0) {
            return;
        } else if (input_status_display == 2 && KeFu.config.modulename != 'index') {
            return;
        }

        $(document).on('input', '#kefu_message', function (e) {
            var kefu_message_input = {
                c: 'Message',
                a: 'messageInput',
                data: {
                    session_id: KeFu.session_id,
                    session_user: KeFu.csr || KeFu.session_user,
                    type: 'input'
                }
            };
            KeFu.ws_send(kefu_message_input);
        });

        $(document).on('blur', '#kefu_message', function (e) {
            var kefu_message_input = {
                c: 'Message',
                a: 'messageInput',
                data: {
                    session_id: KeFu.session_id,
                    session_user: KeFu.csr || KeFu.session_user,
                    type: 'blur'
                }
            };
            KeFu.ws_send(kefu_message_input);
        });
    },
    setSelectedItem: function () {

        if (KeFu.search_primary < 0) {
            KeFu.search_primary = $('#session_list_search').find('li').length - 1;
        } else if (KeFu.search_primary > $('#session_list_search').find('li').length - 1) {
            KeFu.search_primary = 0;
        }

        $('#session_list_search').find('li').removeClass('select_item')
        .eq(KeFu.search_primary).addClass('select_item');

        // 将预选词放入输入框
        $('#kefu_search_input').val($('#session_list_search').find('li').eq(KeFu.search_primary).data('nickname'));
        KeFu.search_select_id = $('#session_list_search').find('li').eq(KeFu.search_primary).data('session');
    },
    browserType: function () {
        var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
        var isOpera = false;
        if (userAgent.indexOf('Edge') > -1) {
            return "Edge";
        }
        if (userAgent.indexOf('.NET') > -1) {
            return "IE";
        }
        if (userAgent.indexOf("Opera") > -1 || userAgent.indexOf("OPR") > -1) {
            isOpera = true;
            return "Opera"
        }
        ; //判断是否Opera浏览器
        if (userAgent.indexOf("Firefox") > -1) {
            return "FF";
        } //判断是否Firefox浏览器
        if (userAgent.indexOf("Chrome") > -1) {
            return "Chrome";
        }
        if (userAgent.indexOf("Safari") > -1) {
            return "Safari";
        } //判断是否Safari浏览器
        if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera) {
            return "IE";
        }
        ; //判断是否IE浏览器
    },
    isPc: function () {
        var is_touch = !!("ontouchstart" in window);
        if (!is_touch) {
            return true;
        }
        var userAgentInfo = navigator.userAgent;
        var Agents = [
            "Android",
            "iPhone",
            "SymbianOS",
            "Windows Phone",
            "iPad",
            "iPod"
        ];
        var flag = true;
        for (var v = 0; v < Agents.length; v++) {
            if (userAgentInfo.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    }
}