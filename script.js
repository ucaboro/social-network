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
