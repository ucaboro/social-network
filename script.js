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
