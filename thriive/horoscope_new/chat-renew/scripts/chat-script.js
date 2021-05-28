globalThis.dialogue_status = 0;
globalThis.dialog_id = 0;
globalThis.default_size = [];
globalThis.default_size_opp = [];
globalThis.prev_dialog_stat = "";
globalThis.prev_dialog_id;
globalThis.subscribed = false;
globalThis.sessionDuration = "";
globalThis.sessionStartedAt = "";
globalThis.addedSecs = 0;
globalThis.totalAddedSecs = 0;
globalThis.OC_ID = "";
globalThis.networkStatus = "ONLINE";


let firestoreDB = firebase.firestore();
let storage = firebase.storage();
let storageRef = storage.ref();
let chatUploadsRef = storageRef.child("chat-uploads");

const BASE_URL = window.location;
let fileUrl =
  BASE_URL + "/wp-content/themes/thriive/horoscope_new/chat-renew/scripts";

function create_dialog(to, from, mail, msg) {
  var dialogue = '<div class="main-messages"></div>';
  //alert(from +'  '+ to + '  '+ mail);
  if (dialogue_status == 0) {
    document.body.innerHTML += dialogue;
  }
  var log_name = "";

  document.querySelector(".main-messages").innerHTML = `
			<div class="messages-header">
				<p style="color:#fff;margin:0;">${log_name}</p>
				<div class='chat-actions'>
					<div class='minimize' style="cursor: pointer;" 
						onclick="hide_chat_box()" id="hide_chat_box">
						<i style='color: #fff' class='fa fa-window-minimize'></i>
					</div>
					<div class='more-options' style="cursor: pointer;" onclick="div_options()">
						<i style='color: #fff' class='fa fa-ellipsis-v'></i>
					</div>
					<div class='close-chat' style="color: #fff;margin: 0;cursor: pointer;"   onClick="clear_chatbox(1)">
						<i style='color: #fff' class='fa fa-close'></i>
					</div>
				</div>
			</div>
      <div id='chat-timer'></div>
			<div class="chat_options">
				<table id="chat_options_table">
					<tbody>
						<tr><td data-from='${from}' data-to='${to}' onClick="block_user(this.dataset.from, this.dataset.to)" style="cursor:pointer" id="nUser">Block</td></tr>
						<tr><td><a href="https://www.thriive.in/faq-chat" style="cursor:pointer">FAQ</a></td></tr>
						<tr>
              <td class='complete-chat' data-from='${from}' data-to='${to}' onClick="complete_chat(this.dataset.from, this.dataset.to)"  style="cursor:pointer" id="nUser">
                Complete
              </td>
            </tr>
						<tr><td onClick="incomplete()" style="cursor:pointer" id="nUser">User Not Available</td></tr>
					</tbody>
				</table>
			</div>
			<div class="messages">
				<p id='in-conversation-with' style="font-size:12px;padding: 10px;color: #1565c0;">
				
				</p>
			</div>
			<div class="emoji_container" style="z-index:9999999999999">
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😊</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😊</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😄</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😁</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😆</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😅</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😂</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">🤣</p>	
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">☺️</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😇</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">🙂</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">🙃</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😉</p>	
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😌</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😍</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">🥰</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😘</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😋</p>
				<p class="emojis" onClick = "insert_emoji(this.innerHTML)">😛</p>
			</div>
			</div>
      <div class='error-mssg'></div>
			<div class="msg">
				<div class="input_div">
				<button class="emoji_icon" id="emoji_icon" onClick="hide_emoji()">😊</button>
				<input type="text" name="ind-message" id="ind-message">
				<input id='add-doc' type="file" name="file" class="file_attatch">
				<button type="button" id="click_attatch" onclick="click_attatch()">
					<i class='fa fa-paperclip'></i>
				</button>
			</div>
			<button type="button" id="send" onclick="write_to_file()">
				<i class="fa fa-paper-plane" aria-hidden="true" style="margin: 0 auto;"></i>
			</button>
			</div>
      <div id='show-img-preview'>
        <div class='action-bar'>
          <i class='fa fa-close' onclick='closeImgPreview()'></i> 
          <span>Preview</span>
        </div>
        <div id='img-container'>
        </div>
      </div>
			<div class="scripts" style="display:none"></div>`;

  var action = "create";
  globalThis.mail_to = mail;

  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "tname", to: to, from: from },
    function (data) {
      if (data) {
        document.querySelector(".messages-header").children[0].innerText = data;
        document.getElementById(
          "in-conversation-with"
        ).innerHTML = `	We have connected you with ${data} . This conversation is completely private and confidential.`;
      } else {
        document.querySelector(".messages-header").children[0].innerText =
          mail.split("@")[0];
        log_name = mail.split("@")[0];
        document.getElementById(
          "in-conversation-with"
        ).innerHTML = `	We have connected you with ${log_name} . This conversation is completely private and confidential.`;
      }
    }
  );

  // $.post(
  // 	'wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php?data=1 ',
  // 	{from: from, to: to, mail:mail, action:action},
  // 	function(data) {
  // 		console.log('data after dialog created : ', data)
  // 		$('.messages').append(data);
  // 		var elem = document.querySelector('.messages');
  // 		elem.scrollTop = elem.scrollHeight;
  // 	}
  // );

  //auto_read();
  //alert(this.innerText);
  globalThis.from = from;
  globalThis.to = to;
  globalThis.mail = mail;
  globalThis.msg = msg;
  dialog_id = to;

  if (dialogue_status == 1) {
    //clearInterval(interval_read);
  }

  //globalThis.interval_read = setInterval('read()', 1000);

  if (typeof arr_count == "undefined") {
    globalThis.read_interval = setInterval("read()", 10000);
  }
  if (dialogue_status == 0) {
    setTimeout("read()", 3000);
  }

  dialogue_status = 1;

  globalThis.all_dialogues = document.querySelectorAll(".start_chat");
  globalThis.dialogues_array = [];
  if (prev_dialog_stat.length > 0) {
    all_dialogues[prev_dialog_id].parentElement.nextSibling.innerText =
      prev_dialog_stat;
  }
  for (i = 0; i < all_dialogues.length / 2; i++) {
    dialogues_array[i] = all_dialogues[i].dataset.touserid;
    if (dialog_id == dialogues_array[i]) {
      prev_dialog_stat = all_dialogues[i].parentElement.nextSibling.innerText;
      prev_dialog_id = i;
      all_dialogues[i].parentElement.nextSibling.innerText = "Active";
    }
  }
  var input = document.getElementById("ind-message");
  input.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      // alert(1);
      document.getElementById("send").click();
    }
  });

  //
  // GET CHAT HISTORY
  //

  let documentId = getId();
  firestoreDB
    .collection("chats")
    .doc(documentId)
    .collection("discussion")
    .get()
    .then((querySnapshot) => {
      let chatHistory = "";
      querySnapshot.forEach((doc) => {
        let chatBubble = generateChatBubble(doc.data(), doc.id);
        chatHistory += chatBubble;
      });
      $(".messages").append(chatHistory);
    });

  subscribeOnce();
  subscribeToFileUpload();
  setChatTimer();
}

function clear_chatbox(num) {
  if (num == 0) {
    document.querySelector(".main-messages").style.display = "block";
  } else {
    dialogue_status = 0;
    document.querySelector(".main-messages").style.display = "none";
  }
}

var attatch_status = 0;
function write_to_file() {
  // alert(2);
  try {
    var form_data = new FormData();
    var action = "write";
    form_data.append("img", document.querySelector(".file_attatch").files[0]);
    form_data.append("msg", document.querySelector("#ind-message").value);
    form_data.append("action", action);
    form_data.append("from", from);
    form_data.append("to", to);
    form_data.append("mail", mail);
    // form_data.append('filename', filename);
    form_data.append("text_msg", msg);
    let message = document.querySelector("#ind-message").value;

    if (document.querySelector(".file_attatch").files.length > 0) {
      attatch_status = 1;
    }
    if (
      document.querySelector("#ind-message").value.trim().length == 0 &&
      attatch_status == 0
    ) {
      alert("please type in a message");
    } else {
      let id;
      if (from < to) {
        id = `${from}-${to}`;
      } else {
        id = `${to}-${from}`;
      }

      let payload = {
        sender: from,
        message: message,
        timestamp: dayjs().toISOString(),
        serverTimestamp: firebase.firestore.FieldValue.serverTimestamp(),
        oc_id: globalThis.OC_ID,
      };

      let documentId = Date.now().toString();

      let uploadedFile = document.querySelector(".file_attatch").files[0];
      if (uploadedFile !== undefined) {
        let filePathToStorage = uploadFileToFirebaseStorage(
          uploadedFile,
          documentId
        );
        let imgContainerElement = document.getElementById("img-container");
        payload.img = imgContainerElement.children[0].src;
      }

      firestoreDB.collection("chats").doc(id).set({ v: 1 }); // firebase should have atleast one field

      firestoreDB
        .collection("chats")
        .doc(id)
        .collection("discussion")
        .doc(documentId)
        .set(payload)
        .then(() => {
          console.log("Document successfully written!");
        })
        .catch((error) => {
          console.error("Error writing document: ", error);
        });
    }  

    document.querySelector("#ind-message").value = "";
    document.querySelector(".file_attatch").value = "";
    document.querySelector(".emoji_container").style.display = "none";
    attatch_status = 0;
    if (!subscribed) {
      subscribeOnce();
      subscribed = true;
    }
  } catch (err) {
    console.log("err : ", err);
  }
}

function getId() {
  let id;
  if (from < to) {
    id = `${from}-${to}`;
  } else {
    id = `${to}-${from}`;
  }
  return id;
}

function subscribeOnce() {
  let id = getId();

  // Create the query to load the last 12 messages and listen for new ones.
  try {
    var query = firestoreDB
      .collection("chats/" + id + "/discussion")
      .orderBy("serverTimestamp", "desc")
      .limit(1);

    // Start listening to the query.
    query.onSnapshot({ includeMetadataChanges: false }, function (snapshot) {
      snapshot.docChanges().forEach(function (change) {
        if (change.type === "removed") {
          // deleteMessage(change.doc.id);
        } else {
          var message = change.doc.data();
          let checkIfExist = document.getElementById(change.doc.id);
          if (checkIfExist !== null) {
            // update chat bubble
            if (document.getElementById("img-" + change.doc.id) !== null) {
              document.getElementById("img-" + change.doc.id).src = message.img;
            }
          } else {
            // new chat bubble
            let chatBubble = generateChatBubble(message, change.doc.id);
            let lastMessage = $(".messages").children().last();
            if (lastMessage[0].id !== change.doc.id) {
              $(".messages").append(chatBubble);
            }
          }

          let messageContainer = document.getElementsByClassName("messages")[0];
          messageContainer.scrollTop = messageContainer.scrollHeight;
        }
      });
    });
  } catch (e) {
    console.log("Error subscribing : ", e);
  }
}

function generateChatBubble(message, id) {
  let formattedDate = dayjs(message.timestamp).format("DD-MMM HH:mm");

  let className = message.sender === from ? "message-right" : "message-left";

  let response;
  if (message.img === undefined || message.img === "") {
    response = `
      <div id='${id}'>
        <div class="${className}">
          ${message.message}
          <p class="right_date"> ${formattedDate} </p>
        </div>
      </div>`;
  } else {
    response = `
      <div id='${id}'>
        <div class="${className}">
          <img id='img-${id}' src='${message.img}' />
          ${message.message}
          <p class="right_date"> ${formattedDate} </p>
        </div>
      </div>`;
  }

  let lastMessage = $(".messages").children().last();

  return response;
}

function read() {
  //console.log('script');
  // console.log({from:from, to:to, action:action, filename:filename, arr_count:arr_count,mail_to:mail_to});
  // if (typeof arr_count !== 'undefined') {
  // 	clearInterval(read_interval);
  // }
  // if(arr_count || arr_count == 0) {
  // 	var action = 'read';
  // 	$.post('wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php',
  // 		{from:from, to:to, action:action, filename:filename, arr_count:arr_count,mail_to:mail_to},
  // 		function(data) {
  // 			$('.messages').append(data);
  // 			if(data) {
  // 				setTimeout(
  // 					'run_read();'
  // 					,1000);
  // 			}
  // 		});
  // } else{
  // 	console.log('else');
  // }
}

function run_read() {
  // read();
}

function click_attatch() {
  document
    .querySelector(".file_attatch")
    .dispatchEvent(new MouseEvent("click"));
}

var hide_status = 1;

function hide_chat_box() {
  if (hide_status == 1) {
    if (document.body.clientWidth < 700) {
      document.querySelector(".main-messages").style.bottom = "-95%";
      //document.querySelector('.msg').style.display = "none";
      hide_status = 0;
    } else {
      document.querySelector(".main-messages").style.bottom = "-55%";
      //document.querySelector('.msg').style.display = "none";
      hide_status = 0;
    }
  } else {
    document.querySelector(".main-messages").style.bottom = "0%";
    //document.querySelector('.msg').style.display = "block";
    hide_status = 1;
  }
}

//setInterval('check_all_chats()', 5000);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

var waiting_user = 0;
var curr_user;

function check_all_chats() {
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "get_curr_user" },
    function (data) {
      setUserType(data);
      if (data) {
        let ret_data = data.split(",");
        if (ret_data[0] == "t0") {
          document.querySelector(".accept_modal_table").innerHTML =
            '<table class="acc_table"><tr><td style="color:#fff;font-size:14px">Please Accept chat request from ' +
            ret_data[4] +
            '</td><td><button onclick="accept_chat(' +
            ret_data[2] +
            "," +
            ret_data[1] +
            "," +
            ret_data[5] +
            ');" style="background-color:#fec031" class="session-btn">ACCEPT</button></td></tr></table;';
          document.querySelector(".accept_modal").style.display = "block";
        } else if (ret_data[0] == "t1") {
          document.querySelector(".accept_modal_table").innerHTML =
            '<table class="acc_table"><tr><td style="color:#fff">Please Wait for User to Start Chat</td><td></td></tr></table>';
          document.querySelector(".accept_modal").style.display = "block";
        } else if (
          ret_data[0] == "t2" &&
          totalAddedSecs !== parseInt(ret_data[10])
        ) {
          globalThis.addedSecs = parseInt(ret_data[9]);
          globalThis.totalAddedSecs = parseInt(ret_data[10]);
        } else if (ret_data[0] == "t2" && dialogue_status == 0) {
          // alert(2);
          if (document.querySelector(".main-messages")) {
            if (
              document.querySelector(".main-messages").style.display == "none"
            ) {
              location.reload();
            }
          }
          globalThis.OC_ID = ret_data[5];
          globalThis.sessionStartedAt = ret_data[7];
          globalThis.sessionDuration = ret_data[8];
          create_dialog(ret_data[2], ret_data[1], ret_data[6], "test");
          document.querySelector(".accept_modal").style.display = "none";
        } else if (ret_data[0] == "t3" && dialogue_status == 0) {
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal_timer").style.display = "none";
          document.querySelector(".timer_img").style.display = "none";
        } else if (ret_data[0] == "t4") {
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal_timer").style.display = "none";
          document.querySelector(".timer_img").style.display = "none";
        } else if (ret_data[0] == "t5") {
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal_timer").style.display = "none";
          document.querySelector(".timer_img").style.display = "none";
          if (ret_data[6] == 0) {
            document.querySelector(".user_exit").style.display = "block";
            setTimeout(function () {
              document.querySelector(".user_exit").style.display = "none";
            }, 10000);
          }
          setTimeout(function () {
            def_warning(ret_data[5]);
          }, 2000);

          if (dialogue_status == 1) {
            clear_chatbox(1);
          }
        } else if (ret_data[0] == "u0") {
          document.querySelector(".wait_btn").dataset.ocid = ret_data[5];
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelectorAll(".browse_btn")[0].dataset.ocid =
            ret_data[5];
          document.querySelectorAll(".browse_btn")[1].dataset.ocid =
            ret_data[5];
          document.querySelector(".accept_modal_timer").style.display = "block";
          document.querySelector(".accept_modal_timer").style.fontSize = "24px";

          if (waiting_user == 0) {
            run_timer(ret_data[5]);
          }
        } else if (ret_data[0] == "u1") {
          document.querySelector(".accept_modal").innerHTML =
            '<div class="accept_modal_table"><table class="acc_table"><tr><td><p style="margin:0;text-align:left;color:#fff;">Therapist has accepted the session. Please click to start Chat</p><p style="margin:0;text-align:left;color:#D4A74D;font-weight:600;">' +
            ret_data[3] +
            '</p></td><td><button onclick="accept_chat(' +
            ret_data[1] +
            "," +
            ret_data[2] +
            "," +
            ret_data[5] +
            ');" style="background-color:#fec031" class="session-btn">ACCEPT</button></td></tr></table></div>';
          document.querySelector(".accept_modal").style.display = "block";
          document.querySelector(".accept_modal_timer").style.display = "none";
          document.querySelector(".timer_img").style.display = "none";
          waiting_user = 5;
        } else if (
          ret_data[0] == "u2" &&
          totalAddedSecs !== parseInt(ret_data[9])
        ) {
          // alert("added secs", ret_data[8]);
          globalThis.addedSecs = parseInt(ret_data[8]);
          globalThis.totalAddedSecs = parseInt(ret_data[9]);
        } else if (ret_data[0] == "u2" && dialogue_status == 0) {
          if (document.querySelector(".main-messages")) {
            if (
              document.querySelector(".main-messages").style.display == "none"
            ) {
              // location.reload();
            }
          }
          waiting_user = 5;
          globalThis.OC_ID = ret_data[5];
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal_timer").style.display = "none";
          document.querySelector(".timer_img").style.display = "none";
          let therapistEmail = "";
          globalThis.sessionStartedAt = ret_data[6];
          globalThis.sessionDuration = ret_data[7];
          create_dialog(ret_data[1], ret_data[2], therapistEmail, "test");
        } else if (ret_data[0] == "u3") {
          if (
            dialogue_status == 1 &&
            document.querySelectorAll(".start_chat1").length < 3
          ) {

            disableChat();
            let chatTimerELement = document.getElementById("chat-timer");
            chatTimerELement.remove();
            dialogue_status = 0;
          }
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal_timer").style.display = "none";
        } else if (ret_data[0] == "u4") {
          if (document.querySelector(".main-messages")) {
            if (
              document.querySelector(".main-messages").style.display == "none"
            ) {

              location.reload();
            }
          }
          if (dialogue_status == 1) {
            clear_chatbox(1);
          }
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal_timer").style.display = "none";
          document.querySelector(".timer_img").style.display = "none";
        } else if (ret_data[0] == "u5") {
          if (dialogue_status == 1) {
            clear_chatbox(1);
          }
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".timer_text").style.display = "none";
          document.querySelectorAll(".browse_btn")[0].disabled = false;
          document.querySelectorAll(".browse_btn")[1].disabled = false;
          document.querySelectorAll(".browse_btn")[0].dataset.ocid =
            ret_data[5];
          document.querySelectorAll(".browse_btn")[1].dataset.ocid =
            ret_data[5];
          document.querySelectorAll(".browse_btn")[0].dataset.action = 1;
          document.querySelectorAll(".browse_btn")[1].dataset.action = 1;
          document.querySelector(".accept_modal_timer").style.display = "block";
          document.querySelector(".accept_timer").style.width = "175px";
          document.querySelector(".accept_timer").style.fontSize = "18px";
          document.querySelectorAll(".browse_btn")[0].style.backgroundColor =
            "#fec031";
          document.querySelectorAll(".browse_btn")[1].style.backgroundColor =
            "#fec031";
          document.querySelector(".accept_timer").innerText =
            "Please Select any other Therapist and take a session";
          document.querySelector(".timer_img").style.display = "none";
        }
      }
      if (data) {
        init_check_all_chats();
      }
    }
  );
}
check_all_chats();

function init_check_all_chats() {
  setTimeout("check_all_chats();", 2000);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function def_warning(ocid) {
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "warn", ocid: ocid },
    function (data) {}
  );
}

function run_timer(ocid) {
  if (waiting_user == 5) {
    /*alert('test');*/
  } else {
    let full_time = document.querySelector(".accept_timer").innerText;
    let rem_time = full_time.split(":");
    if (rem_time[0] == 0 && rem_time[1] == 0) {
      document.querySelector(".accept_timer").innerText =
        "Therapist is not Responding";
      document.querySelector(".accept_timer").style.fontSize = "14px";
      document.querySelector("#accept_timer").style.width = "100%";
      document.querySelector(".timer_img").style.display = "none";
      document.querySelector(".timer_text").style.display = "none";
      document.querySelector(".wait_btn").disabled = false;
      document.querySelectorAll(".browse_btn")[0].disabled = false;
      document.querySelectorAll(".browse_btn")[1].disabled = false;
      document.querySelectorAll(".browse_btn")[0].style.backgroundColor =
        "#fec031";
      document.querySelectorAll(".browse_btn")[1].style.backgroundColor =
        "#fec031";
      document.querySelector(".wait_btn").style.backgroundColor = "#fec031";
      document.querySelector(".wait_btn").style.color = "#251a2b";
      document.querySelector(".browse_btn").style.color = "#251a2b";
      reject_chat("-", "-", ocid);

      waiting_user = 2;
    } else if (rem_time[1] == 0) {
      let min = rem_time[0] - 1;
      let sec = 59;
      let time_string = min + ":" + sec;
      document.querySelector(".accept_timer").innerText = time_string;
      waiting_user = 1;
    } else {
      let min = rem_time[0];
      let sec = rem_time[1] - 1;
      if (
        sec == 9 ||
        sec == 8 ||
        sec == 7 ||
        sec == 6 ||
        sec == 5 ||
        sec == 4 ||
        sec == 3 ||
        sec == 2 ||
        sec == 1 ||
        sec == 0
      ) {
        sec = "0" + sec;
      }
      let time_string = min + ":" + sec;
      document.querySelector(".accept_timer").innerText = time_string;
      if (sec == 30 || sec == 0 || sec == 59 || sec == 58) {
        $.post(
          "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
          { action: "set_time", curr_time: time_string, ocid: ocid },
          function (data) {
            console.log(data);
            if (data) {
              console.log(data);
            }
          }
        );
      }
      waiting_user = 1;
    }

    init_run_timer(ocid);
  }
}

function init_run_timer(ocid) {
  if (user_waiting == 5) {
  }
  if (waiting_user == 1) {
    setTimeout(function () {
      run_timer(ocid);
    }, 1000);
  }
}

//setInterval('check_all_chats();', 1000);

function user_waiting(ocid) {
  document.querySelector(".accept_timer").innerText = "4:59";
  document.querySelector("#accept_timer").style.fontSize = "24px";
  document.querySelector(".timer_text").style.display = "block";
  document.querySelector(".timer_img").style.display = "block";
  document.querySelector("#accept_timer").style.width = "25px";
  run_timer(ocid);
  revive_therapist(ocid);
  document.querySelector(".wait_btn").disabled = true;
  document.querySelectorAll(".browse_btn")[0].disabled = true;
  document.querySelectorAll(".browse_btn")[1].disabled = true;
  document.querySelectorAll(".browse_btn")[0].style.backgroundColor = "#fff";
  document.querySelectorAll(".browse_btn")[1].style.backgroundColor = "#fff";
  document.querySelector(".wait_btn").style.backgroundColor = "#fff";
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "busy_ther", ocid: ocid },
    function (data) {
      console.log(data);
    }
  );
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "set_time", curr_time: time_string, ocid: ocid },
    function (data) {
      console.log(data);
      if (data) {
        console.log(data);
      }
    }
  );
}

function revive_therapist(ocid) {
  //$.post('wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php',{action:'revive',ocid:ocid},function(data){console.log(data)});
}

function user_browsing(ocid, act) {
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "browse", ocid: ocid, act: act },
    function (data) {
      console.log(data);
      if (data) {
        if ((data = 1)) {
          window.location.replace(
            "https://www.thriive.in/talk-to-best-tarot-card-reader-online"
          );
        } else {
          alert("please try later");
        }
      }
    }
  );
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "set_time", curr_time: "reset" },
    function (data) {
      if (data) {
      }
    }
  );
}

function hide_emoji() {
  if (
    document.querySelector(".emoji_container").style.display == "" ||
    document.querySelector(".emoji_container").style.display == "none"
  ) {
    document.querySelector(".emoji_container").style.display = "block";
  } else {
    document.querySelector(".emoji_container").style.display = "none";
  }
  //console.log();
}

function insert_emoji(EL) {
  document.querySelector("#ind-message").value += EL;
}

var default_title = document.title;
var num = 0;
function title() {
  document.tile = default_title;
  if (document.title == "Therapist account dashboard") {
    document.title = "New Message";
  } else {
    document.title = default_title;
  }
}
/*
	function new_message(){
		var check_int = setInterval(function(){num = num+1;console.log('num');}, 1000);
		if(num<6){
			title();
			console.log('if');
		}else{
			clearInterval(check_int);
		}
	}
*/

function div_options() {
  if (
    document.querySelector(".chat_options").style.display == "none" ||
    document.querySelector(".chat_options").style.display == ""
  ) {
    document.querySelector(".chat_options").style.display = "block";
  } else {
    document.querySelector(".chat_options").style.display = "none";
  }
}

function feed_data() {
  var test = document.querySelectorAll(".start_chat");
  var test_length = test.length / 2;
  var test_array = [];
  var id_string = "";
  let action = "feed";
  for (i = 0; i < test_length; i++) {
    id_string +=
      test[i].dataset.fromuserid + "_" + test[i].dataset.touserid + "|";
  }
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: action, id_string: id_string },
    function (data) {
      console.log(data);
    }
  );
}

async function complete_chat(from, to) {
  if (
    confirm(
      "Completing Chat means you will no longer be able to chat within this session are You sure?"
    )
  ) {
    let action = "complete";
    let data = await getCurrentSessionMessages();
    let statusAfterPush = await pushDataToSQL(data);

    if (globalThis.userType === "t") {
      location.reload();
    }
  }
}

function incomplete() {
  let action = "incomplete";
  if (confirm("Mark This Chat as Incomplete?")) {
    $.post(
      "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
      { action: action, to: to, from: from },
      function (data) {
        if (data == 1) {
          location.reload();
        } else {
          location.reload();
        }
      }
    );
  }
}

function accept_chat(to, from, oc_id) {

  waiting_user = 5;
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "accept_chat", to: to, from: from, oc_id },
    function (data) {
      if (data) {
        let data_arr1 = data.split(",");
        if (data_arr1[1] == "therapist") {
          //document.querySelector('.accept_modal_table').innerHTML = '<table class="acc_table"><tr><td>Please Wait for the user</td><td><button onclick="reject_chat('+to+','+from+','+oc_id+')">REJECT</button></td></tr></table>';
        } else {
          let msg = "test";
          // create_dialog(to, from, data, msg);
          check_all_chats();
          document.querySelector(".accept_modal").style.display = "none";
          document.querySelector(".accept_modal_timer").style.display = "none";
        }
      }
    }
  );
}

function reject_chat(to, from, ocid) {
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "reject_chat", to: to, from: from, ocid: ocid },
    function (data) {
      if (data) {
        if (data == "tr") {
          document.querySelector(".accept_modal").style.display = "none";
        } else if (data == "error") {
          console.log("please try later");
        }
      }
    }
  );
}

function take_later(ocid, act) {
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "browse", ocid: ocid, act: 2 },
    function (data) {
      if (data) {
        document.querySelector(".exit_text").innerHTML =
          "Your session is saved in your account.<br> You can log back on to use it at any convenient time.<br><button class='browse_btn session-btn' onclick='document.querySelector(`.user_exit`).style.display=`none`'>OK</button>";
        document.querySelector(".user_exit").style.display = "block";
        document.querySelector(".accept_modal_timer").style.display = "none";

        if ((data = 1)) {
        } else {
          alert("please try later");
        }
      }
    }
  );
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    { action: "set_time", curr_time: "reset" },
    function (data) {
      if (data) {
      }
    }
  );
}

function subscribeToFileUpload() {
  let imgInput = document.getElementById("add-doc");
  imgInput.onchange = (evt) => {
    const [file] = imgInput.files;
    let validatedFile = validateUploadedFile(file);
    if (file && validatedFile) {
      let localURL = URL.createObjectURL(file);
      let previewElement = document.getElementById("show-img-preview");
      let imgContainerElement = document.getElementById("img-container");
      imgContainerElement.innerHTML = ""; // remove previous appended img
      previewElement.style.display = "block";
      let imgElement = document.createElement("img");
      imgElement.src = localURL;
      imgContainerElement.appendChild(imgElement);
      return localURL.toString();
    } else {
      document.querySelector(".file_attatch").value = "";
      alert("Invalid File Type or file size exceeds 1MB");
    }
  };
}

function closeImgPreview() {
  document.getElementById("show-img-preview").style.display = "none";
  document.querySelector(".file_attatch").value = "";
}

function uploadFileToFirebaseStorage(file, timestamp) {
  // Validations goes here
  // File size and extension check
  let id = getId();
  let type = file.type;
  let extension = type.split("/")[1];
  let fileName = `${id}-${timestamp}.${extension}`;

  // Uploading Image to Firebase
  var imgRef = chatUploadsRef.child(fileName);
  imgRef.put(file).then((snapshot) => {
    closeImgPreview();
    snapshot.ref.getDownloadURL().then((downloadURL) => {
      firestoreDB
        .doc(`chats/${id}/discussion/${timestamp}`)
        .update({ img: downloadURL })
        .then((res) => {
          // replacing temp Blob URL with firebase storage URL
          document.getElementById("img-" + id).src = downloadURL;
        });
    });
  });
  return fileName;
}

function validateUploadedFile(file) {
  let validImageTypes = ["image/png", "image/jpeg", "image/jpg"];
  let maxFileSizeAllowed = 1 * 1000000; // 1 MB
  if (
    validImageTypes.indexOf(file.type) !== -1 &&
    file.size < maxFileSizeAllowed
  ) {
    return true;
  } else {
    return false;
  }
}

function setChatTimer() {
  let targetElement = document.getElementById("chat-timer");
  let currentTime = dayjs();
  let sessionStartTime = dayjs(sessionStartedAt);
  let timeDiff = currentTime.diff(sessionStartTime, "m"); // time diff in mins
  if (timeDiff > sessionDuration) {
    disableChat();
    targetElement.innerHTML = `
      <span>Your ${sessionDuration} mins are completed.</span>
      <span>(${globalThis.networkStatus})</span>
    `;
  } else {
    let customInterval = setInterval(() => {
      let tdiff = dayjs().diff(sessionStartTime, "s");
      let min = parseInt(tdiff / 60);
      let secs = parseInt(tdiff % 60);
      let reverseMin =
        parseInt(globalThis.totalAddedSecs / 60) +
        parseInt(sessionDuration) -
        (min + 1);
      if (reverseMin < 0) {
        // stop session
        
        // if user is a therapist,
        // complete chat on his side
        // and refresh page
        disableChat();
        // let completeChat = document.getElementsByClassName("complete-chat");
        if (globalThis.userType === "t") {
          clearInterval(customInterval);
          complete_chat(from, to);
        }
      }
      let reverseSecs = 60 - (secs + 1);
      if (tdiff > sessionDuration * 60) {
        targetElement.innerHTML = `
          <span>Your ${sessionDuration} mins are completed.</span>
          <span>(${globalThis.networkStatus})</span>
        `;
        clearInterval(customInterval);
      } else {
        targetElement.innerHTML = `
        <span> ${sessionDuration} mins session (${
          globalThis.networkStatus
        })</span>
        <span> 
          ${reverseMin >= 0 ? reverseMin : "--"}m 
          ${reverseMin >= 0 ? reverseSecs : "--"}s 
        </span>`;
      }
    }, 1000);
  }
}

async function getCurrentSessionMessages() {
  let id = getId();
  let sessionData = await firestoreDB
    .collection("chats/" + id + "/discussion")
    .where("oc_id", "==", globalThis.OC_ID)
    .get()
    .then((querySnapshot) => {
      let data = [];
      querySnapshot.forEach((doc) => {
        // doc.data() is never undefined for query doc snapshots
        data.push(doc.data());
      });
      return data;
    })
    .catch((error) => {
      console.log("Error getting documents: ", error);
    });
  return sessionData;
}

function disableChat() {
  let inputBox = document.getElementById("ind-message");
  let emojiIcon = document.getElementById("emoji_icon");
  let attachBtn = document.getElementById("click_attatch");
  let sendBtn = document.getElementById("send");

  inputBox.disabled = true;
  emojiIcon.disabled = true;
  attachBtn.disabled = true;
  sendBtn.disabled = true;

  inputBox.style.cursor = "not-allowed";
  emojiIcon.style.cursor = "not-allowed";
  attachBtn.style.cursor = "not-allowed";
  sendBtn.style.cursor = "not-allowed";
}

function setUserType(data) {
  let dataArr = data.split(",");
  const typeOne = ["t0", "t1", "t2", "t3", "t4", "t5"];
  const typeTwo = ["u0", "u1", "u2", "u3", "u4", "u5"];
  if (typeOne.indexOf(dataArr[0]) !== -1) {
    globalThis.userType = "t";
  }
  if (typeTwo.indexOf(dataArr[0]) !== -1) {
    globalThis.userType = "u";
  }
}

async function pushDataToSQL(data) {

  const action = "complete_and_write_to_sql";
  // alert("pushing data to SQL");

  let formData = new FormData();
  formData.append("data", JSON.stringify(data));
  formData.append("action", action);
  formData.append("to", to);
  formData.append("from", from);

  let response = await fetch(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    {
      method: "POST",
      body: formData,
    }
  );
  return response;
}

var offlineTimer = 0;
let offlineInterval;
const offlineColor = "linear-gradient(45deg, #039be5, #e91e63)";
const onlineColor = "#039be5";

window.addEventListener("offline", (event) => {
  globalThis.networkStatus = "OFFLINE";
  let chatTimerELement = document.getElementById("chat-timer");
  chatTimerELement.style.background = offlineColor;

  offlineInterval = setInterval(() => {
    offlineTimer++;
  }, 1000);
});

window.addEventListener("online", (event) => {
  
  globalThis.networkStatus = "ONLINE";
  let chatTimerELement = document.getElementById("chat-timer");
  chatTimerELement.style.background = onlineColor;
  // XHR call to add duration
  $.post(
    "wp-content/themes/thriive/horoscope_new/chat-renew/php/chat_functions.php",
    {
      action: "add_offline_duration",
      secs: offlineTimer,
      oc_id: globalThis.OC_ID,
    },
    function (data) {
      jsonRes = JSON.parse(data);
      globalThis.addedSecs = parseInt(jsonRes.added_seconds);
    }
  );
  // increase duration
  clearInterval(offlineInterval);
});
