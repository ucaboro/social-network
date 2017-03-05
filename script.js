function respondToFriendRequest(button, isAccept) {
  // Get the div containing the button clicked
  var container = $(button).parent().closest('div');
  //TODO: do stuff on the database

  // Change its html to say 'accepted', removing the buttons in the process
  if (isAccept) {
    container.html("<span class=\"text-success\">Accepted</span>");
  } else {
    container.html("<span class=\"text-danger\">Declined</span>");
  }

}
