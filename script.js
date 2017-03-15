/*
 * Sets up the modal to display the correct text etc.
 * modal: The modal to set up.
 * userID: The ID of the user being added.
 * userName: The full name of the user being added.
 */
function setUpModalForAddFriend(modal, userID, userName) {
  // Update the modal title
  modal.find('.modal-title').text('Send friend request');

  // Update the modal body
  modal.find('.modal-body').html("Are you sure you want to add " + userName + " as a friend?");

  // Update the button name
  modal.find('#modal-confirm-button').text('Add friend');

  // Update the button action
  modal.find('#modal-confirm-button').attr('onclick', 'sendFriendRequest(' + userID + ')');
}

/*
 * Sets up the modal to display the correct text etc.
 * modal: The modal to set up.
 * userID: The ID of the user being unfriended.
 * userName: The full name of the user being unfriended.
 */
function setUpModalForDeleteFriend(modal, userID, userName) {
  // Update the modal title
  modal.find('.modal-title').text('End friendship');

  // Update the modal body
  modal.find('.modal-body').text('Are you sure you want to remove ' + userName + ' from your friends?');

  // Update the button name
  modal.find('#modal-confirm-button').text('Remove friend');

  // Update the button action
  modal.find('#modal-confirm-button').attr('onclick', 'deleteFriend(' + userID + ')');
}

function respondToFriendRequest(button, requesterID, isAccept) {
  // Get the div containing the button clicked
  var container = $(button).parent().closest('div');
  // Send request to the database
  $.ajax({url: "ajax/respondToFriendRequest.php",
          data: {
            userID: requesterID,
            isAccept: isAccept
          },
          type: "POST",
          dataType : "json",
          // Update the friend request by replacing the buttons with text that says "accepted" or "declined"
          success: function(result){
            if (result == "Accepted") {
              container.html("<span class=\"text-success\">Accepted</span>");
            } else {
              container.html("<span class=\"text-danger\">Declined</span>");
            }
          }
  });
}

function sendFriendRequest(userID) {
  // Find element object to update
  elementToUpdate = $(document).find("[class='friend-action'][data-user-id='" + userID + "']")
  // Send request to the database
  $.ajax({url: "ajax/sendFriendRequest.php",
          data: {
            userID: userID
          },
          type: "POST",
          dataType : "json",
          // Update the button with text that says "request sent"
          success: function(result){
            elementToUpdate.html(result);
          }
  });
}

function deleteFriend(userID) {
  // Find element object to update
  elementToUpdate = $(document).find("[class='friend-action'][data-user-id='" + userID + "']")
  // Send request to the database
  $.ajax({url: "ajax/deleteFriendship.php",
          data: {
            userID: userID
          },
          type: "POST",
          dataType : "json",
          // Update the friend request with text that says "request sent"
          success: function(result){
            elementToUpdate.html(result);
          }
  });
}

/*
 * Either annotates or unannotates the specified photo.
 * button: The annotate button that was clicked.
 * photoID: The ID of the photo being annotated.
 */
function togglePhotoAnnotation(button, photoID) {
  // Get the span containing the list of people who have annotated the photo
  var list = $(button).parent().parent().find("[class='annotation-list']");
  // Either add or remove 'You' from the start of it.
  // Changing the html here looks more responsive, since the DB is slooow.
  var listHtml = list.html();
  // CASE: Starting with no annotations
  if (listHtml.trim() == "No acknowledgments yet.") {
    list.html("Acknowledged by you.");
  }
  // CASE: Starting with annotation(s), one of which was you
  else if (listHtml.trim().substr(16, 3) == "you") {
    if (listHtml.trim().substr(19, 1) == ".") {
      list.html("No acknowledgments yet.");
    } else {
      list.html("Acknowledged by " + listHtml.trim().substr(20));
    }
  }
  // CASE: Starting with annotation(s), none of which are you
  else {
    list.html("Acknowledged by you, " + listHtml.trim().substr(16));
  }
  // Send request to the database
  $.ajax({url: "ajax/togglePhotoAnnotation.php",
          data: {
            photoID: photoID
          },
          type: "POST",
          dataType : "html",
          // no success function needed
  });
}

//script for new circle creation
var NewCircleScript = function (){
//Creating New Circle functions
//get color of the button
var color ="";
var color_click = function(){
    color = this.id;
}

 function createCircle(){
   var name = $('#circle-name').val();
   if (name.length==0||color.length==0){
      document.getElementById("colorinfo").innerHTML = "create a name and select a color";
     }else{

      document.getElementById("colorinfo").innerHTML = "";

  var xhr = new XMLHttpRequest();
  xhr.open('POST','ajax/createNewCircle.php', true);
  xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xhr.onreadystatechange = function (){

    if (xhr.readyState == 4 && xhr.status ==200){
          var target = document.getElementById("circles");
          target.innerHTML = xhr.responseText;
          //location.reload();
    }
}
xhr.send("name="+name+"&color="+color);
}

}
//assigning colors to buttons
document.getElementById('blue').onclick = color_click;
document.getElementById('aqua').onclick = color_click;
document.getElementById('green').onclick = color_click;
document.getElementById('orange').onclick = color_click;
document.getElementById('red').onclick = color_click;

//create new circle button
var create = document.getElementById("create");
create.addEventListener("click", createCircle);
}
