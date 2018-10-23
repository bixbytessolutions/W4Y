let token = document.head.querySelector('meta[name="csrf-token"]');
	
if (token) {
    Vue.http.interceptors.push(function(request, next) {
        request.headers.set('X-CSRF-TOKEN', token.content)
        next()
      })
} 
var app = new Vue({
    el: '#chat-app',
    data: {
      urlPrefix: "/",
      userList: [],
      requestToId: undefined,
      messageText: "",
      selectedFile: null,
      chatTopDate: "Today",
      messageList: [],
      currentPage: 1,
      totalItem: 0,
      currentPage: 1,
      pageBlock: false,
      pageHold: true,
      prevScrollHeight: 0,
      currentScollHeight: 0,
      isTyping: false,
      currentUserId: null,
      chatBlockId: null,
      pusher: undefined,
      channel: null,
      sendObject:{
          id : null,
          fromid: "",
          toid: "",
          message: "",
          msgon: "",
          msgtype: "",
          fileurl: "",
          seen: "",
          deleteforid: "",
          created_at: ""
      }
    },
    created: function(){
    
        
        this.getUserList();
        this.chechScroll();
    },
    mounted: function(){

     
        this.currentUserId = $('#currentUserId').val();
        Pusher.logToConsole = true;
		  //Remember to replace key and cluster with your credentials.
		this.pusher = new Pusher('ae5583b78c1a12b5d783', {
			cluster: 'ap2',
			encrypted: true
        });    
        
        this.requestToId = this.getRequestParams();

        localchannel = this.pusher.subscribe('reload_dom_'+this.currentUserId);
        self = this;
        localchannel.bind('reload-event', function(data) {
            self.getUserList();
        });
        
    },
    methods: {

        getUserList: function(){
           
            this.$http.get(this.urlPrefix+'chat/users').then(function(response){
                this.userList=response.data;
                if(this.chatBlockId ==null){
                    if(this.userList.length >0){
                        this.chatBlockId=this.userList[0].id;
                        this.fetchMessages();
                        this.listenToEvent();
                       
                    }
                }
            });
        },
        sendMessage: function(){
            this.sendObject.fromid = this.currentUserId;
            this.sendObject.toid = this.chatBlockId;
            this.sendObject.message = this.messageText;
            this.sendObject.msgtype = "text";
            if(this.messageText != ""){
                this.$http.post(this.urlPrefix+'chat/send-message',{ message_text: this.messageText, to_user_id: this.chatBlockId, message_type: "text" }).then(
                    function(response){
                        this.messageText="";
                        this.messageList.push(response.data.chat);
                        this.getUserList();
                    }
                )
            }            
        },
        uploadFile: function(){
            // var files = event.target.files || event.dataTransfer.files;
            // if (!files.length)
            //     return;
            // this.createImage(files[0]);
            this.createImage($('#fileUpload')[0].files[0])
        },
        createImage: function(file){
            let formData = new FormData();
            formData.append('uploadFile', file);
            formData.append('to_user_id', this.chatBlockId);
            this.$http.post(this.urlPrefix+'chat/send-file',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }).then(
                function(response){
                    this.messageText="";
                    this.messageList.push(response.data.chat);
                    this.getUserList();
                }
            )
            
        },
        sendTypeEvent: function(data){
            if(data)
                this.chechScroll();
            this.$http.post(this.urlPrefix+'chat/typing-event',{ to_user_id: this.chatBlockId, status: data }).then(
                function(response){
                    //status triggered
                }
            )
        },
        fetchMessages: function(){
            this.prevScrollHeight=$("#chatHistory").prop("scrollHeight");
            this.$http.get(this.urlPrefix+'chat/message/'+this.chatBlockId+"?page_no="+this.currentPage).then(function(response){
                this.messageList=response.data.data.reverse();
                this.perPage = response.data.per_page;
                this.totalItem = response.data.total;
                $('.deletePanel').addClass('hide')
                $('.chatProfileHolder .topUserName').text($('#userblock'+this.chatBlockId).find('.username').text());
                
            });
        },
        deleteMessage:function(data){
            this.$http.post(this.urlPrefix+'chat/delete-message',{ to_user_id: this.chatBlockId, status: data }).then(
                function(response){
                    this.getUserList();
                    this.fetchMessages();
                }
            )
        },
        listenToEvent: function(){
            this.channel = this.pusher.subscribe('notify_'+this.chatBlockId+"_to_"+this.currentUserId);
            self = this;

			this.channel.bind('notify-event', function(data) {
                self.getUserList();
               if('notify_'+self.chatBlockId+"_to_"+self.currentUserId == 'notify_'+data.fromid+"_to_"+data.toid){
                    self.fetchMessages();
                }
            });
            this.channel.bind('typing-event', function(data) {
                if('notify_'+self.chatBlockId+"_to_"+self.currentUserId == data.chanel){
                    self.isTyping = data.status;
                }
             });
        },
        chatUserClick: function(chatblock){
            this.currentPage=1;
            this.pageBlock=false;
            this.pageHold=true;
            this.perPage=0;
            this.totalItem=0;
            this.chechScroll();
            this.chatBlockId = chatblock;
            this.messageList=[];
            this.isTyping=false;
            this.chatTopDate="Today";
            $('.chatUserList .user').removeClass('active');
            $('#userblock'+chatblock).addClass('active');
            this.fetchMessages();
           this.getUserList();
            if( /Android|webOS|iPhone|iPad|iPod|Opera Mini/i.test(navigator.userAgent) && $(window).width()<=780) {
                $('.chatContactHolder').hide();
                $('.chatDetails').show();
            }
            this.listenToEvent();
      
        },
        testOngoingDate(date,index){
            if(index == 0){
                return true;
            }else if(index>0){
                if(this.messageList[index-1].msgon != date.msgon){
                    return true;
                }
            }
            return false;
        },
        chechScroll: function(){
            $("#chatHistory").scrollTop($("#chatHistory").prop("scrollHeight"));
        },
        getRequestParams: function(name="to-id"){
            if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
                return decodeURIComponent(name[1]);
        },
        onChatScoll: function(){
            this.pageHold=false;
            self=this;
           
            $('#chatHistory').find('.chatTopDate').each(function(){
            
                var $window = $('#chatHistory');
    
                var docViewTop = $window.scrollTop();
                var docViewBottom = docViewTop + $window.height();
    
                var elemTop = $(this).offset().top;
                var elemBottom = elemTop + $(this).height();

                if(docViewTop==0 ){
                    self.chatTopDate= $('#chatHistory').find('.chatTopDate:eq(0)').text();
                }
                if((elemBottom <= docViewBottom) && (elemTop >= docViewTop)){
                   this.pageBlock=true;
                    self.chatTopDate=$(this).text();
                }
                
            })
            
            var scroll = $('#chatHistory').scrollTop();
            
            if(scroll <= 0){
              //  this.pageBlock=true;
                $val = parseInt(this.totalItem) / parseInt(this.perPage);

                if(this.currentPage < $val){
                    this.pageBlock=true;
                    this.currentPage++;
                    this.fetchMessages();
                }
            }
            
        },
        onlineStatusCheck: function(value){
            if(value===null)
                return false;

            var theDate = new Date(value * 1000);
            //dateString = theDate.toGMTString();
            start = theDate;

            var end = new Date();
            var timeDiff = Math.abs(end.getTime() - start.getTime());
            if(parseFloat(timeDiff) <= 120000)
                return true;
            else
                return false;
        }
    },
    updated: function(){
        if(this.pageHold){
            this.chechScroll();
        }
        if(this.pageBlock){
            this.currentScollHeight=$("#chatHistory").prop("scrollHeight");
            $scrollTop = this.currentScollHeight - this.prevScrollHeight;
            this.pageBlock=false;
            $("#chatHistory").scrollTop($scrollTop);
        }    
        if(this.requestToId != undefined){
            this.chatBlockId=this.requestToId;
            $('.chatUserList .user').removeClass('active');
            $('#userblock'+this.requestToId).addClass('active');
            this.fetchMessages();
            this.requestToId=undefined;
        }
        if(!($('#userblock'+this.chatBlockId).hasClass('active'))){
            $('.chatUserList .user').removeClass('active');
            $('#userblock'+this.chatBlockId).addClass('active');
        }
    },
    delimiters: ["<%","%>"]
  })

  Vue.filter('take-time', function (value) {
    if (!value) return ''
    value = value.split(" ");
    time=value[1];
    time = time.split(":");
    var d = new Date(); // for now
    if(parseInt(d.getMinutes()) == parseInt(time[1])){
        return "Just Now";
    }else
        return time[0] + ":"+ time[1];
  })

  Vue.filter('diff-time', function (value) {
    if (!value) return 'No Record'

    var date1 = new Date(value);
    var date2 = new Date();
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.floor(timeDiff / (1000 * 3600 * 24)); 
    if(diffDays < 1){
        value = value.split(" ");
        time=value[1];
        time = time.split(":");
        if(parseInt(date2.getMinutes()) == parseInt(time[1])){
            return "Just Now";
        }else{
            $time = Math.floor((timeDiff/1000)/60);
            if($time < 60){
                return $time+" Minutes ago";
            }else{
                return Math.floor($time/60)+" Hours ago";
            }
        }
    }else if(diffDays = 1)
        return diffDays+ " Day ago";
    else if(diffDays > 1)
        return diffDays+ " Days ago";
  })

  
  Vue.filter('calc-mem', function (value) {
    if (!value) return '0 bytes';

    if(value <= 1024)
        return Math.floor(value) + "bytes";
    else{
        kbytes=parseFloat(value)/1024;
        if(kbytes <= 1024)
            return Math.floor(kbytes) + "KB";
        else{
            mbytes=kbytes/1024;
            return Math.floor(mbytes) + "MB"
        }
    }
    
  });