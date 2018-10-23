@extends('theme.default') @section('content')
@section('title', 'Projects')

<link rel="stylesheet" href="{!! asset('css/chat.css') !!}"> 


<style>
[v-cloak] {display: none}
</style>
<section id="chat-app" v-cloak>
		<div class="container-fluid">
		<div class="row">
		  <div class="chat">
				  <div class="chatContactHolder">
					<div class="chatHeader">
						<h2 class="pageTitle">My Messages</h2>
					</div>
					<div class="chatUserList">
						<div v-for="(users, index) in userList" v-bind:class="{ user: true, active: index==0 }" v-bind:id="'userblock'+users.id"  v-on:click="chatUserClick( users.id );">
						<div class="profile" :style="{ backgroundImage: 'url(' + urlPrefix + 'uploads/avatar/' +users.photo + ')' }"> 
					
				 </div>
							<div>
								<p class="username"><% users.first_name %> <% users.last_name %> <span v-bind:class="{online: onlineStatusCheck(users.last_activity), offline: (!onlineStatusCheck(users.last_activity))}"  class="fa fa-circle "></span></p>
								<p v-show="users.deleteforid != currentUserId" v-if="users.message=='staticspy'" class="message" > You are now connected </p>
								<p v-show="users.deleteforid != currentUserId" v-else class="message" > <% users.message %> </p>
								<p v-show="users.deleteforid != currentUserId" class="time"><% users.ChatDate|diff-time %></p>
							</div>
							
							<div v-if="users.newmsgcount != 0">
								<span class="messageCount"> <% users.newmsgcount %></span>
							</div>
							
						</div>
					</div>
				  </div>
			<div class="chatDetails">
				<div class="chatProfileHolder">'
					<p class="pageTitle"> <span class="topUserName"></span>  <i v-show="messageList.length > 0"  v-on:click="$('.deletePanel').toggleClass('hide')" class="fa fa-trash cursor" style="color:#636363"></i></p>
					<div class="panel panel-default hide deletePanel">
						<div class="panel-heading">
							Are you sure you want to delete conversation?
						</div>
						<div class="panel-body">
							<button class="btn btn-default confirmDelete" v-on:click="deleteMessage(event)">Yes</button>
							<button class="btn btn-default"  v-on:click="$('.deletePanel').toggleClass('hide')">No</button>
						</div>
					</div>
					<button type="button" class="chatBack btn-w4y">Back</button>
				</div>
				<div class="chatHistory" id="chatHistory" v-on:scroll="onChatScoll()">
					<div class="scrollTime" ><% chatTopDate %></div> 
					<ul>
						<v-container v-for="(message,index) in messageList"  >
							<li class="day chatTopDate" v-if="testOngoingDate(message,index)">
								<% message.datecroll %>
							</li>
							<li v-if="message.message != 'staticspy' && message.msgtype == 'text'" v-bind:class="{to: message.fromid == currentUserId, from: message.fromid == chatBlockId}">
								<div class="time"><% message.created_at|take-time %></div>
								<div class="chatProfile" :style="{ backgroundImage: 'url(' + urlPrefix + 'uploads/avatar/' +message.photo + ')' }">  </div> 
								<div class="text"><% message.message %></div>
							</li>
							<li v-if="message.msgtype == 'file'" v-bind:class="{to: message.fromid == currentUserId, from: message.fromid == chatBlockId}">
								<div class="time"><% message.created_at|take-time %></div>
								<div class="chatProfile" :style="{ backgroundImage: 'url(' + urlPrefix + 'uploads/avatar/' +message.photo + ')' }">  </div> 
								<div class="text attachement">
									<p class="attachementName"><a v-bind:href="urlPrefix + message.file_url"><% message.message %></a> <i class="fa fa-file-image-o"></i></p>
									<p class="attachementSize"><% message.file_size|calc-mem %></p>
								</div>
							</li>
							<li class="day" v-if="message.message == 'staticspy'">
								<b>Just Connected</b>
							</li>
						</v-container>
						
						
						<li class="from" v-if="isTyping">
							<div class="chatProfile"  style="background-image:url('images/user.jpg')">  </div> 
							<div class="text"> 
								<span class="fa fa-circle typing _1"></span>  
								<span class="fa fa-circle typing _2"></span>  
								<span class="fa fa-circle typing _3"></span>  
							</div>
						</li>
					</ul>
				</div>
				<div class="chatInput">
					<input type="hidden" value="{{ Auth::user()->id }}" id="currentUserId">
				  <input type="text" id="chatMessage" v-model="messageText" v-on:keyup.enter="sendMessage()" name="message" placeholder="Write something" v-on:focus="sendTypeEvent(true)" v-on:blur="sendTypeEvent(false)"> 
					<div class="chatBtn" id="send" v-on:click="sendMessage()"><i class="fa fa-paper-plane"></i></div>
					<div class="chatBtn"><i class="fa fa-paperclip attachementBtn" v-on:click="$('#fileUpload').click()"></i></div>
					<input type="file" v-show="false" id="fileUpload" v-model="selectedFile" v-on:change="uploadFile()" 
					accept="application/zip, application/octet-stream, application/x-zip-compressed, multipart/x-zip">
					
				</div>
			</div>
			
		  </div>
		  </div>
		</div>
  </section>

  <script src="https://js.pusher.com/4.0/pusher.min.js"></script>
  <script src="{!! asset('js/chats/vue.js') !!}"></script>
  <script src="{!! asset('js/chats/vue-resource.js') !!}"></script>
  <script src="{!! asset('js/chats/chat-app.js') !!}"></script>

<script>
	
$(function () {
	
	$('.chatBack').click(function(){
		$('.chatContactHolder').show();
		$('.chatDetails').hide();
	})
	
});

</script>
@endsection