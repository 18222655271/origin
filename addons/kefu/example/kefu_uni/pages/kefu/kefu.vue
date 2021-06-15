<!-- FastAdmin在线客服插件不是开源产品，所有文字、图片、样式、风格等版权归在线客服作者所有，如有复制、仿冒、抄袭、盗用，FastAdmin和在线客服作者将追究法律责任 -->
<template>
	<view>
		<view class="kefu_container">
			<view v-if="errorTips" class="error_tips">{{errorTips}}</view>
			<view v-if="config.announcement && errorTips == ''" class="notice">{{config.announcement}}<text @tap="close_notice"
				 class="close_notice">不再显示</text></view>

			<!--留言板-->
			<view v-if="showLeaveMessage" class="leave_message">
				<form method="get" action="">
					<view class="form-group">
						<label for="c-name" class="control-label">姓名</label>
						<input type="text" @input="leave_message" name="name" :value="leaveMessage.name" class="form-control" id="c-name"
						 placeholder="请输入您的姓名">
					</view>
					<view class="form-group">
						<label for="c-contact" class="control-label">联系方式</label>
						<input type="text" @input="leave_message" name="contact" :value="leaveMessage.contact" class="form-control" id="c-contact"
						 placeholder="请输入手机/QQ/微信号">
					</view>
					<view class="form-group">
						<label for="c-message" class="control-label">留言内容</label>
						<textarea rows="5" @input="leave_message" name="message" :value="leaveMessage.message" class="form-control" id="c-message"
						 placeholder="遇到的问题、所需服务、产品等，我们将尽快与您取得联系"></textarea>
					</view>
					<button @tap="post_leave_message" type="button" class="leave_success">确认留言</button>
				</form>
			</view>
			<!-- 留言板end -->

			<!-- 主界面 -->
			<!-- #ifdef H5 -->
			<view class="content_wrapper" v-if="!showLeaveMessage" :style="{height:'calc(100vh - var(--window-top) - ' + writeBottom + 'px)'}">
				<!-- #endif -->
				<!-- #ifndef H5 -->
				<view class="content_wrapper" v-if="!showLeaveMessage" :style="{height:'calc(100vh - ' + writeBottom + 'px)'}">
					<!-- #endif -->

					<view :style="{height:'calc(100% - ' + wrapperHeight + 'px)'}" class="chat_wrapper">
						<scroll-view @scrolltoupper="wrapper_scrolltoupper" id="kefu_scroll" @tap="tap_scroll_view" :scroll-y="true"
						 :scroll-with-animation="kefu_scroll_with_animation" :scroll-top="kefu_scroll_top">

							<block v-for="(item, index) in messageList" :key="index">
								<view class="status"><text>{{item.datetime}}</text></view>
								<block v-for="message in item.data" :key="message.id">

									<view v-if="message.message_type == 3" class="status"><text>{{message.message}}</text></view>

									<view v-if="message.message_type != 3" class="bubble" :class="message.sender == 'me' ? 'me':'you'">

										<!-- 除了商品/订单卡片和图片，其他的都使用富文本 -->
										<jyf-parser @imgtap="message_img_preview" :tag-style="{img:'width:60px;height:60px;'}" v-if="message.message_type == 0"
										 :html="message.message"></jyf-parser>

										<jyf-parser v-if="message.message_type == 2" :html="'<a target=_blank href=' + message.message + '>' + message.message + '</a>'"></jyf-parser>

										<image @tap="preview_img(message.message)" v-if="message.message_type == 1" :src="message.message" mode="widthFix"></image>

										<view v-if="message.message_type == 4 || message.message_type == 5" class="project_item">
											<image :src="message.message.logo"></image>
											<view class="project_item_body">
												<view class="project_item_title">{{message.message.subject}}</view>
												<view v-if="message.message.note" class="project_item_note">{{message.message.note}}</view>
												<view class="project_item_price">
													<text v-if="message.message.price">￥{{message.message.price}}</text>
													<text v-if="message.message.number">x{{message.message.number}}</text>
												</view>
											</view>
										</view>

										<view v-if="message.sender == 'me' && message.status <= 1" class="kefu_message_status" :class="(message.status == 0 ? '':' kf-text-grey')">{{message.status == 0 ? '未读':'已读'}}</view>
										<view v-if="message.sender == 'me' && message.status == 3" class="kefu_message_status kf-text-red">失败</view>

									</view>
								</block>
							</block>

						</scroll-view>
					</view>

					<!-- 消息输入 -->
					<view class="write" :style="{bottom: writeBottom + 'px'}">
						<view class="widget_textarea">
							<textarea :adjust-position="false" :show-confirm-bar="false" :fixed="true" :focus="kefuMessageFocus"
							 :auto-height="true" maxlength="-1" @blur="kefu_message_blur" @input="textarea_input" :cursor-spacing="14"
							 @focus="textarea_focus" :value="kefuMessage" class="kefu_message"></textarea>
						</view>
						<view class="write_right" :style="{flex:showSendButton ? 3:2}">
							<i v-if="config.toolbar && config.toolbar.expression" :style="{background: 'url(' + config.toolbar.expression.icon_image + ') no-repeat','background-size':'100% 100%'}"
							 class="toolbar_icon smiley" @tap="show_tool('smiley')"></i>
							<button class="send_btn" @tap="send_message(kefuMessage, 0)" hover-class="send_btn_hover" v-if="showSendButton">发送</button>
							<i class="toolbar_icon attach" :style="{background: 'url(' + attachBackground + ') no-repeat','background-size':'100% 100%'}"
							 @tap="show_tool('more')" v-if="!showSendButton && attachBackground"></i>
						</view>
					</view>
					<view v-if="showTool" class="footer_div">
						<!-- 表情 -->
						<block v-if="showTool == 'smiley'" v-for="(item, index) in expressionData" :key="index">
							<image :src="item.src" @tap="select_expression(item.title)"></image>
						</block>
						<!-- 表情end -->
						<!-- 更多 -->
						<view v-if="showTool == 'more'" class="toolbar">
							<view @tap="show_select_model('order')" v-if="config.toolbar && config.toolbar.order" class="toolbar_item">
								<image :src="config.toolbar.order.icon_image"></image>
								<view>{{config.toolbar.order.title}}</view>
							</view>
							<view @tap="show_select_model('goods')" v-if="config.toolbar && config.toolbar.goods" class="toolbar_item">
								<image :src="config.toolbar.goods.icon_image"></image>
								<view>{{config.toolbar.goods.title}}</view>
							</view>
							<view @tap="upload_file" v-if="config.toolbar && config.toolbar.file" class="toolbar_item">
								<image :src="config.toolbar.file.icon_image"></image>
								<view>{{config.toolbar.file.title}}</view>
							</view>
						</view>
						<!-- 更多end -->
					</view>
				</view>

				<!-- 订单和商品选择 -->
				<view v-if="selectModel" class="select_model">
					<view @tap="show_select_model(false)" class="close_select">关闭</view>
					<view class="project_list">
						<radio-group v-if="selectModelData.length > 0" @change="select_done">
							<label v-for="(item, index) in selectModelData" :key="index" class="project_item">
								<image :src="item.logo"></image>
								<view class="project_item_body">
									<view class="project_item_title">{{item.subject}}</view>
									<view v-if="item.note" class="project_item_note">{{item.note}}</view>
									<view class="project_item_price">
										<text v-if="item.price">￥{{item.price}}</text>
										<text v-if="item.number">x{{item.number}}</text>
									</view>
								</view>
								<radio :value="index.toString()" :checked="false" />
							</label>
						</radio-group>
						<view class="select_model_no_data" v-if="selectModelData.length <= 0"><text>没有更多记录了...</text></view>
					</view>
				</view>

			</view>
		</view>
</template>

<script>
	import Config from "./config.js"; // 本地配置数据
	import jyfParser from "@/components/jyf-parser/jyf-parser"; // 富文本解析
	var token = ''; // 用户身份标识
	var fixedCsr = ''; // 指定客服
	var innerAudioContext = null;
	export default {
		components: {
			jyfParser
		},
		data() {
			return {
				leaveMessage: {
					name: '',
					contact: '',
					message: ''
				},
				attachBackground: '',
				showSendButton: false,
				showTool: false,
				showLeaveMessage: false,
				expressionData: [],
				config: [],
				tokenList: [],
				kefuMessage: '',
				wrapperHeight: 46,
				ws: {
					SocketTask: null,
					Timer: null, // 计时器
					ErrorMsg: [], // 发送失败的消息
					MaxRetryCount: 3, // 最大重连次数
					CurrentRetryCount: 0,
					socketOpen: false,
					pageHideCloseWs: true, // 是否在页面hide时关闭链接，兼容预览图片的情况
					unloadCloseWs: false // ws关闭状态码兼容性不好，手动标记页面卸载关闭ws链接
				},
				csr: '', // 当前用户的客服代表ID
				sessionId: 0, // 会话ID
				sessionUser: "", // 带身份标识的用户ID
				kefuMessageFocus: false,
				messageList: [],
				chatRecordPage: 1,
				errorTips: '链接中...',
				selectModel: false,
				selectModelData: [],
				writeBottom: 0,
				kefu_scroll_top: 0,
				kefu_scroll_with_animation: true,
				record_scroll_height: 0,
				navigation_bar_title: ''
			}
		},
		onLoad(opt) {

			token = opt.token ? opt.token : token
			fixedCsr = opt.fixed_csr ? opt.fixed_csr : fixedCsr

			// 微信小程序端onshow时再链接，并在onhide时关闭链接
			// #ifndef MP-WEIXIN
			this.load()
			// #endif
		},
		onShow() {
			if (!this.ws.pageHideCloseWs) {
				this.ws.pageHideCloseWs = true;
			}
			// #ifdef MP-WEIXIN
			this.load()
			// #endif
		},
		onHide() {
			// #ifdef MP-WEIXIN
			if (this.ws.SocketTask && this.ws.socketOpen && this.ws.pageHideCloseWs) {
				console.log('微信小程序页面hide主动关闭链接')
				uni.closeSocket();
			}
			// #endif
		},
		onUnload() {
			if (this.ws.SocketTask && this.ws.socketOpen) {
				console.log('页面卸载主动关闭链接')
				this.ws.unloadCloseWs = true
				uni.closeSocket();
			}
		},
		methods: {
			load: function() {
				var that = this
				var kefu_tourists_token = '';

				// 初始化请求
				try {
					var kefu_tourists_token = uni.getStorageSync('kefu_tourists_token');
					if (!kefu_tourists_token) {
						kefu_tourists_token = ''
					}
				} catch (e) {
					console.error(e)
				}

				uni.request({
					url: that.build_url('initialize'),
					data: {
						token: token,
						kefu_tourists_token: kefu_tourists_token,
						fixed_csr: fixedCsr
					},
					success: (res) => {
						if (res.data.code == 1) {

							// 保存游客token
							if (res.data.data.token_list.kefu_tourists_token) {
								try {
									uni.setStorageSync('kefu_tourists_token', res.data.data.token_list.kefu_tourists_token);
								} catch (e) {
									console.error(e)
								}
							}

							// 公告
							var kefu_notice = ''
							try {
								kefu_notice = uni.getStorageSync('kefu_notice');
							} catch (e) {
								console.log(e)
							}
							if (kefu_notice == res.data.data.config.announcement) {
								res.data.data.config.announcement = '';
							}

							// 配置
							that.config = res.data.data.config;
							that.tokenList = res.data.data.token_list;

							// 来信提示音初始化
							innerAudioContext = uni.createInnerAudioContext();
							innerAudioContext.src = that.build_url('message_prompt');

							// 新消息提示
							if (res.data.data.new_msg) {
								that.new_message_prompt()
							}

							// 初始化表情
							var protocol = Config.https_switch ? 'https://' : 'http://';
							var expression = {};
							for (let i in Config.expression) {
								expression[i] = {
									'src': protocol + Config.baseURL + '/assets/addons/kefu/img' + Config.expression[i].src,
									'title': Config.expression[i].title
								};
							}
							that.expressionData = expression;

							// 杂项资源
							that.attachBackground = that.build_url('res', '/img/more.png');

							// 链接ws
							that.connect_socket();
						} else {
							uni.showModal({
								content: res.data.msg,
								showCancel: false,
								success: res => {
									if (res.confirm) {
										uni.navigateBack({
											delta: 1
										});
									}
								}
							})
						}
					},
					fail: res => {
						uni.showModal({
							title: '温馨提示',
							content: '在线客服初始化失败,请重试~',
							showCancel: false
						})
						that.errorTips = '初始化失败';
					}
				});
			},
			switch_show_tool: function(value) {
				if (!value) {
					this.showTool = false;
					this.writeBottom = 0;
				} else {
					this.showTool = value;
					this.writeBottom = 170;
					this.scroll_into_footer();
				}
			},
			set_navigation_bar_title: function(title, back_title = true) {
				if (back_title) {
					this.navigation_bar_title = title
				}
				uni.setNavigationBarTitle({
					title: title
				});
			},
			close_notice: function() {
				uni.setStorageSync('kefu_notice', this.config.announcement);
				this.config.announcement = '';
			},
			select_done: function(e) {
				let index = e.detail.value;
				var message = '';
				for (let i in this.selectModelData[index]) {
					message += i + '=' + this.selectModelData[index][i] + '#';
				}
				this.send_message(message, (this.selectModel == 'goods') ? 4 : 5);
				this.selectModel = false
				this.switch_show_tool(false)
			},
			show_select_model: function(name) {
				this.selectModel = name
				if (!name) {
					return;
				}

				uni.showLoading({
					title: '请稍后...'
				})

				var data_api_url = this.config.toolbar[name].data_api
				var reg = new RegExp("(^https?:\/\/)", "i");
				if (data_api_url.search(reg) === -1) {
					data_api_url = (Config.https_switch ? 'https://' : 'http://') + Config.baseURL + data_api_url
				}

				this.selectModelData = [];

				uni.request({
					url: data_api_url,
					data: {
						token: token
					},
					success: res => {
						if (res.data.code == 1) {
							this.selectModelData = res.data.data
						} else {
							uni.showModal({
								title: '温馨提示',
								content: res.data.msg,
								showCancel: false
							})
						}
					},
					fail: res => {
						uni.showModal({
							title: '温馨提示',
							content: '网络请求失败，请重试！',
							showCancel: false
						})
					},
					complete: res => {
						uni.hideLoading()
					}
				})
			},
			parseUrl: function(data) {
				var reg = new RegExp("(https?:\/\/)([0-9a-z.]+)([\?0-9a-z&=]+)?(#[0-9-a-z]+)?", "g");
				return data.replace(reg, '<a target="_blank" title="$&" class="link" href="$&">$&</a>');
			},
			message_img_preview: function(e) {
				this.ws.pageHideCloseWs = false; // 页面hide不关闭链接
			},
			preview_img: function(src) {
				this.ws.pageHideCloseWs = false; // 页面hide不关闭链接
				uni.previewImage({
					current: src,
					urls: [src]
				});
			},
			upload_file: function() {
				var that = this
				that.ws.pageHideCloseWs = false; // 页面hide不关闭链接
				
				that.ws_send({
					c: 'Message',
					a: 'getUploadMultipart'
				});
				
				uni.chooseImage({
					success: res => {
						const tempFilePaths = res.tempFilePaths;

						uni.showLoading({
							title: '正在上传...'
						})

						var formData = new Object();
						if (that.config.upload.multipart) {
							for (let i in that.config.upload.multipart) {
								formData[i] = that.config.upload.multipart[i]
							}
						}

						const uploadTask = uni.uploadFile({
							url: that.build_url('upload'),
							// #ifdef APP-PLUS || H5
							file: res.tempFiles[0],
							// #endif
							filePath: tempFilePaths[0],
							name: 'file',
							formData: formData,
							success: (res) => {
								uni.hideLoading()
								that.ws.pageHideCloseWs = true;
								res = JSON.parse(res.data);
								if (res.code == 1) {
									that.send_message(res.data.url, 1)
									that.switch_show_tool(false)
									that.kefuMessageFocus = true;
								} else {
									uni.showModal({
										title: '温馨提示',
										content: res.msg,
										showCancel: false
									})
								}
							},
							complete: res => {
								uni.hideLoading()
							}
						});

						// #ifndef APP-PLUS
						uploadTask.onProgressUpdate((res) => {
							uni.showLoading({
								title: res.progress + '%'
							})
						});
						// #endif
					}
				})
			},
			connect_socket: function() {
				var that = this

				if (this.ws.SocketTask && this.ws.socketOpen) {
					console.log('无需链接')
					return;
				}

				// 开始链接
				that.ws.SocketTask = uni.connectSocket({
					url: this.build_url('ws'),
					header: {
						'content-type': 'application/json'
					},
					complete: res => {}
				});

				// 链接打开
				uni.onSocketOpen(function(res) {
					console.log('链接已打开')
					that.ws.socketOpen = true
					that.ws.CurrentRetryCount = 0;
					// 重新发送所有出错的消息
					for (let i in that.ws.ErrorMsg) {
						that.ws_send(that.ws.ErrorMsg[i]);
					}
					that.ws.ErrorMsg = [];
					that.errorTips = '';

					if (that.ws.Timer != null) {
						clearInterval(that.ws.Timer);
					}

					that.ws.Timer = setInterval(that.ws_send, 28000); //定时发送心跳
				});

				// 收到消息
				uni.onSocketMessage(function(res) {
					let msg = JSON.parse(res.data)
					let actions = that.domsg()
					let action = actions[msg.msgtype] || actions['default']
					action.call(this, msg)
				})

				// 出错
				uni.onSocketError(function(res) {
					that.ws.socketOpen = false;
					that.errorTips = 'WebSocket 发生错误!'
					console.log('链接出错', res)
				});

				// 链接关闭
				uni.onSocketClose(function(res) {
					// 工具上是1000，真机上测试是10000
					console.log('链接已关闭', res)
					that.ws.socketOpen = false;

					if (that.ws.Timer != null) {
						clearInterval(that.ws.Timer);
					}

					if (that.errorTips.indexOf('重连') === -1) {
						that.errorTips = '网络链接已断开!';
					}

					if (res.code == 1000 || res.code == 10000 || that.ws.unloadCloseWs) {
						return;
					}

					if (that.ws.MaxRetryCount) {
						that.ws.Timer = setInterval(that.retry_websocket, 3000); //每3秒重新连接一次
					}
				});
			},
			// 重连ws
			retry_websocket: function() {
				var that = this
				if (that.ws.CurrentRetryCount < that.ws.MaxRetryCount) {
					that.ws.CurrentRetryCount++;
					that.connect_socket();
					that.errorTips = '重连WebSocket第' + that.ws.CurrentRetryCount + '次';
				} else {
					if (that.ws.Timer != null) {
						clearInterval(that.ws.Timer);
					}

					that.errorTips = '每隔10秒将再次尝试重连 WebSocket';
					that.ws.Timer = setInterval(that.connect_socket, 10000); //每10秒重新连接一次
				}
			},
			// 发送ws消息
			ws_send: function(message) {
				var that = this
				if (!message) {
					message = {
						c: 'Message',
						a: 'ping'
					};
				}

				if (that.ws.SocketTask && that.ws.socketOpen) {
					uni.sendSocketMessage({
						data: JSON.stringify(message)
					});
				} else {
					console.log('消息发送出错', message, that.ws.SocketTask, that.ws.socketOpen)
					that.ws.ErrorMsg.push(message);
				}
			},
			send_message: function(message, message_type) {
				var that = this
				if (message == '') {
					uni.showToast({
						title: '请输入消息内容',
						icon: 'none'
					})
					return;
				}
				// 检查 websocket 是否连接
				if (!that.ws.SocketTask || !that.ws.socketOpen) {
					uni.showToast({
						title: '网络链接异常，请稍后重试~',
						icon: 'none'
					})
					return;
				}

				if (!that.sessionId) {
					uni.showToast({
						title: '初始化未完成(会话获取失败)~',
						icon: 'none'
					})
					return;
				}

				if (message_type == 0) {

					// 处理链接
					message = that.parseUrl(message);

					// 处理表情
					var reg = /\[(.+?)\]/g; // [] 中括号
					var reg_match = message.match(reg);

					if (reg_match) {

						for (let i in reg_match) {
							var emoji_item = that.find_emoji(reg_match[i]);
							message = message.replace(emoji_item.title, that.build_chat_img(emoji_item.src, '', 'emoji'));
						}
					}

				}

				let message_data = that.messageList[that.messageList.length - 1].data;
				var message_id = parseInt(message_data[message_data.length - 1].id) + 1;
				// var message_id = new Date().getTime() + that.sessionId + Math.floor(Math.random() * 10000);
				var load_message = {
					c: 'Message',
					a: 'sendMessage',
					data: {
						message: message,
						message_type: message_type,
						session_id: that.sessionId,
						modulename: 'index',
						message_id: message_id
					}
				};

				that.ws_send(load_message);

				var data = {
					id: message_id,
					status: 2, // 标记待发送状态
					sender: 'me',
					message: (message_type == 1 || message_type == 2) ? that.config.upload.cdnurl + message : message,
					message_type: message_type
				}

				var messageListIndex = that.messageList.length - 1
				if (that.messageList[messageListIndex] && that.messageList[messageListIndex].datetime == '刚刚') {
					that.messageList[messageListIndex].data = that.messageList[messageListIndex].data.concat(that.format_message([
						data
					]))
				} else {
					that.messageList = that.messageList.concat({
						datetime: '刚刚',
						data: that.format_message([data])
					});
				}

				that.kefuMessage = ''
				that.kefu_message_change();
				that.scroll_into_footer(200, 99992)
			},
			find_emoji: function(emoji_title) {
				for (let i in this.expressionData) {
					if (this.expressionData[i].title == emoji_title) {
						return this.expressionData[i];
					}
				}

				return false;
			},
			build_chat_img: function(filename, facename, class_name = 'emoji') {
				var protocol = Config.https_switch ? 'https://' : 'http://';
				if (class_name == 'emoji') {
					return '<img class="emoji" src="' + filename + '" />';
				} else {
					return '<img class="' + class_name + '" src="' + filename + '" />';
				}
			},
			// 记录用户填写的留言数据，方便后续清空输入等处理
			leave_message: function(e) {
				if (e.currentTarget.id == 'c-name') {
					this.leaveMessage.name = e.detail.value
				} else if (e.currentTarget.id == 'c-contact') {
					this.leaveMessage.contact = e.detail.value
				} else if (e.currentTarget.id == 'c-message') {
					this.leaveMessage.message = e.detail.value
				}
			},
			post_leave_message: function() {
				if (!this.leaveMessage.contact) {
					uni.showToast({
						title: '联系方式不能为空哦~',
						icon: 'none'
					})
					return false;
				}

				var leave_message = {
					c: 'Message',
					a: 'leaveMessage',
					data: this.leaveMessage
				};
				this.ws_send(leave_message);
			},
			domsg: function(msg) {
				var that = this
				return {
					default: (msg) => {
						// console.log('default', msg);
					},
					initialize: (msg) => {
						if (msg.data.new_msg) {
							that.new_message_prompt()
						}

						// 分配/获取客服->获取聊天记录
						var user_initialize = {
							c: 'Message',
							a: 'userInitialize',
							data: {
								fixed_csr: that.fixedCsr
							}
						};
						that.ws_send(user_initialize);
					},
					user_initialize: (msg) => {
						// 用户客服分配结束
						if (msg.code == 1) {

							if (msg.data.session.user_tourists) {
								that.send_message = function() {
									uni.showToast({
										title: '为保护您的隐私请,请登录后发送消息~',
										icon: 'none'
									})
								}
							}

							that.csr = msg.data.session.csr;
							that.sessionId = msg.data.session.id;
							that.showLeaveMessage = false
							that.set_navigation_bar_title('客服 ' + msg.data.session.nickname + ' 为您服务')
						} else if (msg.code == 302) {

							if (!that.csr) {

								// 打开留言板
								that.csr = 'none';
								that.showLeaveMessage = true
								that.set_navigation_bar_title('当前无客服在线哦~')
							} else {
								uni.showToast({
									title: '当前客服暂时离开,您可以直接发送离线消息~',
									icon: 'none'
								})
							}

						}
					},
					show_msg: (msg) => {
						uni.showToast({
							title: msg.msg,
							icon: 'none'
						})
					},
					leave_message: (msg) => {
						uni.showToast({
							title: msg.msg,
							icon: 'none'
						})
						that.leaveMessage = {
							name: '',
							contact: '',
							message: ''
						}
					},
					clear: (msg) => {
						if (msg.msg) {
							uni.showToast({
								title: msg.msg,
								icon: 'none'
							})
						}

						var clear = {
							c: 'Message',
							a: 'clear'
						};
						that.ws_send(clear);

						that.ws.unloadCloseWs = true;
					},
					offline: (msg) => {
						if (msg.user_id == that.csr) {
							this.errorTips = '客服离线~'
						}
					},
					online: (msg) => {
						// 来自 admin 的用户上线
						if (msg.modulename == 'admin') {

							if (msg.user_id == that.csr) {
								that.errorTips = '';
							} else if (that.csr == 'none') {
								// 重新为用户分配客服代表
								var user_initialize = {
									c: 'Message',
									a: 'userInitialize'
								};
								that.ws_send(user_initialize);
							}
						}
					},
					csr_change_status: (msg) => {
						if (that.csr == msg.data.csr) {
							if (msg.data.csr_status != 3) {
								that.errorTips = '客服' + that.get_csr_status(msg.data.csr_status) + '~'
							} else {
								that.errorTips = '';
							}
						}
					},
					transfer_done: (msg) => {
						that.csr = msg.data.csr;
						that.set_navigation_bar_title('客服 ' + msg.data.nickname + ' 为您服务')
					},
					new_message: (msg) => {
						that.new_message_prompt();

						if (msg.data.session_id == that.sessionId) {

							that.set_navigation_bar_title(that.navigation_bar_title, false)

							var messageListIndex = that.messageList.length - 1
							msg.data.id = msg.data.record_id
							if (that.messageList[messageListIndex].datetime == '刚刚') {
								that.messageList[messageListIndex].data = that.messageList[messageListIndex].data.concat(that.format_message(
									[
										msg.data
									]))
							} else {
								that.messageList = that.messageList.concat({
									datetime: '刚刚',
									data: that.format_message([msg.data])
								});
							}

							var load_message = {
								c: 'Message',
								a: 'readMessage',
								data: {
									record_id: msg.data.record_id,
									session_id: that.sessionId
								}
							};

							that.ws_send(load_message);

							that.scroll_into_footer(800);
						}
					},
					chat_record: (msg) => {
						// 下一页
						that.chatRecordPage = msg.data.next_page;
						for (let i in msg.data.chat_record) {
							msg.data.chat_record[i].data = that.format_message(msg.data.chat_record[i].data)
						}
						if (msg.data.page == 1) {
							that.messageList = msg.data.chat_record;
							// 滑动到最后一条消息
							that.scroll_into_footer(200, 99991);
						} else {
							for (let i = msg.data.chat_record.length - 1; i >= 0; i--) {
								msg.data.chat_record[i].data.reverse()
								that.messageList.unshift(msg.data.chat_record[i]);
							}

							setTimeout(function() {
								let kefu_scroll = uni.createSelectorQuery().select('#kefu_scroll');
								kefu_scroll.fields({
									scrollOffset: true
								}, data => {
									that.kefu_scroll_top = data.scrollHeight - that.record_scroll_height
									that.kefu_scroll_with_animation = true
								}).exec()
							}, 200)
						}
					},
					send_message: (msg) => {
						if (!msg.data.message_id) {
							return;
						}

						var messageListIndex = that.messageList.length - 1
						for (let i in that.messageList[messageListIndex].data) {
							if (that.messageList[messageListIndex].data[i].id == msg.data.message_id) {
								that.messageList[messageListIndex].data[i].status = (msg.code == 1) ? 0 : 3;
								that.messageList[messageListIndex].data[i].id = msg.data.id
							}
						}

						if (msg.code == 0) {
							uni.showToast({
								title: msg.data.msg,
								icon: 'none'
							})
						}
					},
					read_message_done: (msg) => {
						if (that.sessionId == msg.data.session_id) {
							if (msg.data.record_id == 'all') {
								for (let messageListIndex in that.messageList) {
									for (let i in that.messageList[messageListIndex].data) {
										that.messageList[messageListIndex].data[i].status = 1;
									}
								}
							} else {
								for (let messageListIndex in that.messageList) {
									for (let i in that.messageList[messageListIndex].data) {
										if (that.messageList[messageListIndex].data[i].id == msg.data.record_id) {
											that.messageList[messageListIndex].data[i].status = 1;
										}
									}
								}
							}
						}
					},
					message_input: (msg) => {
						var that = this
						var input_status_display = parseInt(that.config.input_status_display);
						if (input_status_display == 0 || input_status_display == 2) {
							return;
						}

						if (msg.data.type == 'input') {
							that.set_navigation_bar_title('客服正在输入...', false)
						} else {
							that.set_navigation_bar_title(that.navigation_bar_title, false)
						}
					},
					upload_multipart: (msg) => {
						if (msg.data.upload_multipart) {
							that.config.upload.multipart = msg.data.upload_multipart
						}
					}
				};
			},
			get_csr_status: function(status_id) {

				const states = {
					0: '离线',
					1: '繁忙',
					2: '离开',
					3: '在线',
					99: '断链',
					'default': '未知'
				}

				return states[parseInt(status_id)] || states['default'];
			},
			build_url: function(type = 'ws', res_path) {
				var that = this
				var protocol = Config.https_switch ? 'https://' : 'http://';
				var token = that.tokenList.kefu_token ? '&token=' + that.tokenList.kefu_token : '';
				var kefu_tourists_token = that.tokenList.kefu_tourists_token ? '&kefu_tourists_token=' + that.tokenList.kefu_tourists_token :
					'';

				var buildObj = {
					ws: () => {
						protocol = parseInt(that.config.wss_switch) == 1 ? 'wss://' : 'ws://';
						return (protocol + Config.baseURL + ':' + that.config.websocket_port + '/?modulename=index' + token +
							kefu_tourists_token).replace(
							/\|/g, '~');
					},
					initialize: () => {
						return protocol + Config.baseURL + '/addons/kefu/index/initialize?modulename=index';
					},
					upload: () => {
						return that.config.upload.uploadurl + '?modulename=index' + token + kefu_tourists_token;
					},
					storage_notify: () => {
						// 云存储notify，未实现，能获取的文件信息太少。
						return protocol + Config.baseURL + '/addons/' + type + '/index/notify';
					},
					message_prompt: () => {
						if (that.config.upload.cdnurl) {
							return that.config.upload.cdnurl + that.config.ringing;
						}
						return protocol + Config.baseURL + that.config.ringing;
					},
					res: () => {
						if (that.config.__CDN__) {
							return that.config.__CDN__ + '/assets/addons/kefu' + res_path;
						}
						return protocol + Config.baseURL + '/assets/addons/kefu' + res_path;
					},
					default: () => {
						return protocol + Config.baseURL;
					}
				};

				let action = buildObj[type] || buildObj['default']
				return action.call(this)
			},
			new_message_prompt: function() {
				if (innerAudioContext) {
					innerAudioContext.play();
					setTimeout(function() {
						innerAudioContext.stop();
					}, 1000)
				} else {
					console.error('来信提示音播放失败！');
				}
			},
			scroll_into_footer: function(timeout = 0, kefu_scroll_top = 0) {
				var that = this
				if (kefu_scroll_top) {
					setTimeout(function() {
						that.kefu_scroll_top = (that.kefu_scroll_top > 99990) ? that.kefu_scroll_top + 200 : kefu_scroll_top
					}, timeout)
					return;
				}
				let kefu_scroll = uni.createSelectorQuery().select('#kefu_scroll');
				kefu_scroll.fields({
					scrollOffset: true
				}, data => {
					setTimeout(function() {
						that.kefu_scroll_top = data.scrollHeight
					}, timeout)
				}).exec()
			},
			wrapper_scrolltoupper: function() {
				var that = this
				if (!this.sessionId || this.chatRecordPage == 'done') {
					return;
				}

				// 加载历史聊天记录
				var load_message = {
					c: 'Message',
					a: 'chatRecord',
					data: {
						session_id: this.sessionId,
						page: this.chatRecordPage
					}
				};

				that.kefu_scroll_with_animation = false
				let kefu_scroll = uni.createSelectorQuery().select('#kefu_scroll');
				kefu_scroll.fields({
					scrollOffset: true
				}, data => {
					that.record_scroll_height = data.scrollHeight
				}).exec()

				this.ws_send(load_message);
			},
			tap_scroll_view: function() {
				this.switch_show_tool(false)
			},
			show_tool: function(name = 'smiley') {

				this.switch_show_tool((this.showTool == name) ? false : name)

				if (this.showTool) {
					this.scroll_into_footer()
				}
			},
			// 输入框聚焦
			textarea_focus: function(e) {
				var that = this
				this.showTool = false;
				that.scroll_into_footer(0, 99993)
				that.writeBottom = e.detail.height ? e.detail.height : 0;
			},
			// 输入框输入
			textarea_input: function(e) {
				var that = this
				that.kefuMessage = e.detail.value
				that.kefu_message_change()

				if (parseInt(that.config.input_status_display) == 0) {
					return;
				}

				var kefu_message_input = {
					c: 'Message',
					a: 'messageInput',
					data: {
						session_id: that.sessionId,
						session_user: that.csr || that.sessionUser,
						type: 'input'
					}
				};
				that.ws_send(kefu_message_input);
			},
			// 显示/隐藏发送按钮-调整消息记录框高度
			kefu_message_change: function() {
				this.showSendButton = (this.kefuMessage == '') ? false : true;
				let write = uni.createSelectorQuery().select('.write');
				write.fields({
					size: true
				}, data => {
					if (data.height != this.wrapperHeight) {
						this.wrapperHeight = data.height
					}
				}).exec()
			},
			// 消息框焦点
			kefu_message_blur: function() {
				var that = this
				that.kefuMessageFocus = false;
				if (!that.showTool) {
					that.writeBottom = 0;
				}

				if (parseInt(that.config.input_status_display) == 0) {
					return;
				}

				var kefu_message_input = {
					c: 'Message',
					a: 'messageInput',
					data: {
						session_id: that.sessionId,
						session_user: that.csr || that.sessionUser,
						type: 'blur'
					}
				};
				that.ws_send(kefu_message_input);
			},
			select_expression: function(title) {
				this.kefuMessage = this.kefuMessage + title;
				this.kefu_message_change();
			},
			format_message: function(message) {
				for (let i in message) {
					if (message[i].message_type == 4 || message[i].message_type == 5) {
						var message_arr = message[i].message.split('#');
						var message_obj = {};

						for (let y in message_arr) {
							let message_temp = message_arr[y].split('=');
							if (typeof message_temp[1] != 'undefined') {
								message_obj[message_temp[0]] = message_temp[1];
							}
						}
						message[i].message = message_obj;
					}
				}
				return message;
			}
		}
	}
</script>

<style>
	* {
		padding: 0;
		margin: 0;
	}

	page {
		overflow: hidden;
	}

	.kefu_container {
		height: calc(100vh - var(--window-top));
		overflow: hidden;
		background-color: #F8F8F8;
	}

	.error_tips {
		position: fixed;
		top: var(--window-top);
		width: 100%;
		height: 80rpx;
		font-size: 30rpx;
		line-height: 80rpx;
		background: rgba(231, 76, 60, 0.7);
		z-index: 999;
		color: #FFFFFF;
		text-align: center;
	}

	.notice {
		position: fixed;
		top: var(--window-top);
		width: 100%;
		font-size: 30rpx;
		line-height: 36rpx;
		padding: 16rpx;
		box-sizing: border-box;
		background: rgba(252, 248, 227, 1);
		color: #c09853;
		z-index: 999;
	}

	.close_notice {
		color: #999;
		font-size: 26rpx;
		text-decoration: underline;
	}

	.select_model {
		background-color: #f2f2f2;
		position: fixed;
		bottom: 0rpx;
		z-index: 998;
		width: 100%;
		border-top-left-radius: 32rpx;
		border-top-right-radius: 32rpx;
	}

	.close_select {
		box-sizing: border-box;
		font-size: 28rpx;
		padding: 16rpx 22rpx;
		color: #999;
		width: 100%;
		text-align: right;
	}

	.project_list {
		overflow-y: auto;
		overflow-x: hidden;
		height: 362rpx;
	}

	.project_item {
		background-color: #FFFFFF;
		display: flex;
		align-items: center;
		padding: 16rpx 8rpx 16rpx 16rpx;
		margin-bottom: 16rpx;
	}
	
	.bubble .project_item {
		margin-bottom: 0rpx;
	}

	.project_item:last-child {
		margin-bottom: 0;
	}

	.project_item image {
		width: 140rpx;
		height: 140rpx;
		min-width: 140rpx;
	}

	.project_item_body {
		height: 130rpx;
		width: 96%;
		margin: 0 16rpx;
	}

	.project_item_title {
		font-size: 28rpx;
		display: block;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		line-height: 30rpx;
		height: 60rpx;
		color: #181818;
	}

	.project_item_note {
		font-size: 26rpx;
		display: block;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		line-height: 30rpx;
		color: #999999;
		margin-top: 4rpx;
	}

	.project_item_price text:last-child {
		margin-left: 16rpx;
		color: #999999;
		font-size: 24rpx;
	}

	.project_item_price text:first-child {
		margin-left: -6rpx;
		font-size: 30rpx;
		color: rgba(231, 76, 60, 1);
	}

	.select_model_no_data {
		font-size: 30rpx;
		height: 100%;
		background: #FFFFFF;
		color: #999999;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.content_wrapper {}

	.chat_wrapper {
		background-color: #F8F8F8;
		width: 100%;
		box-sizing: border-box;
		color: #181818;
	}

	.chat_wrapper scroll-view {
		height: 100%;
	}

	.chat_wrapper .status {
		position: relative;
		float: right;
		width: 100%;
		margin-bottom: 20rpx;
		text-align: center;
		height: 60rpx;
		line-height: 60rpx;
	}

	.chat_wrapper .status text {
		font-size: 24rpx;
		display: inline-block;
		background: #ccc;
		color: #fff;
		border-radius: 10rpx;
		padding: 6rpx 20rpx;
		line-height: 24rpx;
	}

	.chat_wrapper .bubble {
		font-size: 32rpx;
		position: relative;
		display: inline-block;
		clear: both;
		margin-bottom: 16rpx;
		padding: 24rpx 24rpx;
		vertical-align: top;
		border-radius: 10rpx;
		max-width: 76%;
	}

	.chat_wrapper .bubble.me .kefu_message_status {
		position: absolute;
		bottom: 0;
		left: -30px;
		color: #00b0ff;
		font-size: 12px;
	}

	.chat_wrapper .kf-text-grey {
		color: #999 !important;
	}

	.chat_wrapper .kf-text-red {
		color: #e64340 !important;
	}

	.chat_wrapper .bubble.me {
		float: right;
		margin-right: 28rpx;
		margin-left: 20rpx;
		color: #fff;
		background-color: #00b0ff;
		word-wrap: break-word;
		word-break: break-all;
	}

	.chat_wrapper .bubble.you {
		float: left;
		margin-right: 20rpx;
		margin-left: 28rpx;
		color: #181818;
		background-color: #fff;
		word-wrap: break-word;
		word-break: break-all;
	}

	.chat_wrapper .bubble:before {
		position: absolute;
		top: 38rpx;
		display: block;
		width: 16rpx;
		height: 12rpx;
		content: '\00a0';
		-webkit-transform: rotate(29deg) skew(-35deg);
		transform: rotate(29deg) skew(-35deg);
	}

	.chat_wrapper .bubble.you:before {
		left: -6rpx;
		background-color: #fff;
	}

	.chat_wrapper .bubble.me:before {
		right: -6rpx;
		background-color: #00b0ff;
	}

	.chat_wrapper .bubble image {
		max-height: 400rpx !important;
		width: 200rpx;
		vertical-align: bottom;
	}

	.chat_wrapper .bubble .emoji {
		display: inline-block;
		width: 120rpx;
		height: 120rpx;
	}

	.content_wrapper .write {
		box-sizing: border-box;
		background-color: #f7f7f7;
		box-shadow: 0 -2rpx 0 #e5e5e5;
		width: 100%;
		padding: 8rpx 12rpx;
		display: flex;
		align-items: center;
		position: fixed;
		bottom: 0rpx;
	}

	.write_right {
		flex: 2;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.widget_textarea {
		flex: 8;
		background-color: #FFFFFF;
	}

	.content_wrapper .toolbar_icon {
		display: inline-block;
		cursor: pointer;
		vertical-align: middle;
		width: 56rpx;
		height: 56rpx;
		content: '';
		margin-left: 16rpx;
	}

	.kefu_message {
		max-height: 150rpx;
		width: calc(100% - 36rpx);
		outline: none;
		border: none;
		resize: none;
		border-radius: 8rpx;
		padding: 18rpx;
		-webkit-user-select: text !important;
		-moz-user-select: text !important;
		-ms-user-select: text !important;
		user-select: text !important;
		line-height: 40rpx;
		font-size: 32rpx;
		color: #181818;
	}

	.kefu_message::-webkit-scrollbar {
		width: 4rpx;
	}

	.kefu_message::-webkit-scrollbar-track {
		background-color: #fff;
		-webkit-border-radius: 2em;
		-moz-border-radius: 2em;
		border-radius: 2em;
	}

	.kefu_message::-webkit-scrollbar-thumb {
		background-color: #e6e6e6;
		-webkit-border-radius: 2em;
		-moz-border-radius: 2em;
		border-radius: 2em;
	}

	.footer_div {
		position: fixed;
		bottom: 0rpx;
		background-color: #fff;
		box-shadow: 0 8rpx 10rpx rgba(0, 0, 0, .1);
		width: 100%;
		animation: show_footer_div .1s;
		animation-fill-mode: forwards;
		padding-top: 12rpx;
		box-sizing: border-box;
		height: 170px;
		overflow-y: auto;
		overflow-x: hidden;
	}

	@keyframes show_footer_div {
		from {
			height: 0;
		}

		to {
			height: 170px;
		}
	}

	.footer_div image {
		height: 51rpx;
		width: 51rpx;
		padding: 12rpx;
	}

	.send_btn {
		margin-left: 28rpx;
		background: #00b0ff;
		color: #fff;
		border-color: #00b0ff;
		outline: none;
		padding: 10rpx 20rpx;
		font-size: 24rpx;
		line-height: 1.5;
		border-radius: 6rpx;
	}

	.send_btn::after {
		border: none;
	}

	.send_btn_hover {
		background-color: #19baff;
	}

	.toolbar {
		display: flex;
		flex-wrap: wrap;
	}

	.toolbar_item {
		margin-top: 20rpx;
		width: 25%;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: center;
	}

	.toolbar_item image {
		width: 60rpx;
		height: 60rpx;
	}

	.toolbar_item view {
		display: block;
		width: 100%;
		font-size: 26rpx;
		line-height: 34rpx;
		text-align: center;
	}

	.leave_message {
		width: 90%;
		display: block;
		margin: 40rpx auto;
		font-size: 32rpx;
	}

	.form-control {
		margin: 20rpx 0;
		width: 90%;
		display: block;
		padding: 12rpx 24rpx;
		font-size: 28rpx;
		color: #555;
		background-color: #fff;
		background-image: none;
		border: 1px solid #ccc;
		border-radius: 8rpx;
		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
		-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
		-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
		transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	}

	.leave_success {
		color: #fff;
		background: #5cb85c;
		border-color: #4cae4c;
		margin: 0 auto;
		display: inline-block;
		padding: 12rpx 24rpx;
		margin-bottom: 0;
		font-size: 28rpx;
		font-weight: 400;
		line-height: 1.42;
		text-align: center;
		white-space: nowrap;
		cursor: pointer;
		border: 1px solid transparent;
		border-radius: 8rpx;
	}
</style>
