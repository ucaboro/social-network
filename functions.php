<?php

include "db.php";
include "record.php";
include "templates.php";

/* =============================
 * GET INFO ABOUT THIS SESSION
 * ============================= */

/*
 * Returns the ID of the current user, or NULL if there is no active session.
 */
function getUserID() {
  if (!isLoggedIn()) { return NULL; }
  return $_SESSION['login_user'];
}

/*
 * Returns the name of the current user, or NULL if there is no active session.
 */
function getUserName() {
  return getManagers()[getUserID()];
}

/* =============================
 * LOGGING IN AND OUT
 * ============================= */

/*
 * Makes sure that the user is logged in, and redirects them to the login page if not.
 */
function ensureLoggedIn() {
  // Go to login screen if not logged in
  if(!isLoggedIn()) {
    header("location: login.php");
  }
}

/*
 * Returns true if a user is logged in and false otherwise.
 */
function isLoggedIn() {
  return isset($_SESSION['login_user']);
}

function logIn($email, $password) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // Get this manager from the database
  $stmt = $db->prepare("SELECT * FROM manager WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  $count = $result->num_rows;

  // Check the email exists
  if ($count == 0) {
    return false;
  }

  // Get the first row of results (there should only be one row)
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $hashedPasswordDb = $row["password"];
  $archived = $row["archived"];

  // Check the user hasn't been archived
  if ($archived) {
    return false;
  }

  // Check the password
  if (!password_verify($password, $hashedPasswordDb)) {
    return false;
  }

  // Set the session to contain the user's id and redirect to home
  $_SESSION['login_user'] = $row["id"];
  return true;
}


/* =============================
 * WRITE INFO TO THE DATABASE
 * ============================= */

 $GLOBALS["error"] = NULL;

/*
 * Returns the last error message that was recorded.
 */
function getError() {
  return $GLOBALS["error"];
}

/*
 * Sets the latest recorded error message.
 */
function setError($error) {
  $GLOBALS["error"] = $error;
}

/*
 * Adds an array of records to the database.
 * $records: an array where each value is a single record object (the keys are ignored).
 * Returns true if all operations were successful. If one of the operations fails, no further operations are processed.
 */
function addRecords($records) {
  // If array is empty, just return true
  if (count($records) <= 0) {
    return true;
  }

  // Connect to the database
  $db = new db();
  $db->connect();

  // Prepare the SQL statement
  $stmt = $db->prepare("INSERT INTO record (trainee_id, activity_id, animal_id, level, assessor_id, comment, date_completed) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("iiiiiss", $traineeID, $activityID, $animalID, $level, $assessorID, $comment, $dateCompleted);

  // Loop through each record in the array
  foreach ($records as $record) {
    // Get the details
    $traineeID = $record->traineeID;
    $activityID = $record->activityID;
    $animalID = empty($record->animalID) ? NULL : $record->animalID;
    $level = $record->level;
    $assessorID = $record->assessorID;
    $comment = empty($record->comment) ? NULL : $record->comment;
    $dateCompleted = $record->dateCompleted;

    // Insert the new record
    $stmt->execute();
    $result = $stmt->get_result();

    // Check the result. If the operation failed, set the error message and stop looping
    if ($stmt->errno != 0) {
      setError("Adding the record for activity '$record->activityID', animal '$record->animalID' failed: " . $stmt->error);
      return false;
    }
  }
  return true;

}

/*
 * Updates one or more records in the database.
 * $records: an array of records where the key is the record ID to update, and the value is a record object containing the new information for this record.
 * Returns true if all operations were successful. If one of the operations fails, no further operations are processed.
 */
function updateRecords($records) {
  // If array is empty, just return true
  if (count($records) <= 0) {
    return true;
  }
  // Construct an array to hold the errors
  $errors = array();

  // Connect to the database
  $db = new db();
  $db->connect();

  // Prepare the SQL statement
  $stmt = $db->prepare("UPDATE record SET trainee_id = ?, activity_id = ?, animal_id = ?, level = ?, assessor_id = ?, comment = ?, date_completed = ? WHERE id = ?");
  $stmt->bind_param("iiiiissi", $traineeID, $activityID, $animalID, $level, $assessorID, $comment, $dateCompleted, $recordID);

  // Loop through each record in the array
  foreach ($records as $recordID => $record) {
    // Get the details
    $traineeID = $record->traineeID;
    $activityID = $record->activityID;
    $animalID = empty($record->animalID) ? NULL : $record->animalID;
    $level = $record->level;
    $assessorID = $record->assessorID;
    $comment = empty($record->comment) ? NULL : $record->comment;
    $dateCompleted = $record->dateCompleted;

    // Update the record
    $stmt->execute();
    $result = $stmt->get_result();

    // Check the result. If the operation failed, set the error message and stop looping
    if ($stmt->errno != 0) {
      setError("Updating the record (ID: $recordID) for activity '$record->activityID', animal '$record->animalID' failed: " . $stmt->error);
      return false;
    }
  }
  return true;
}

/*
 * Removes records from the database.
 * $recordIDs: an array of indexes of the records to remove, where they key is the recordID (the value is ignored).
 * Returns true if all operations were successful. If one of the operations fails, no further operations are processed.
 */
function deleteRecords($recordIDs) {
  // If array is empty, just return true
  if (count($recordIDs) <= 0) {
    return true;
  }

  // Connect to the database
  $db = new db();
  $db->connect();

  // Prepare the SQL statement
  $stmt = $db->prepare("DELETE FROM record WHERE id = ?");
  $stmt->bind_param("i", $recordID);

  // Loop through each record in the array
  foreach ($recordIDs as $recordID => $value) {
    // Delete the record
    $stmt->execute();
    $result = $stmt->get_result();

    // Check the result. If the operation failed, set the error message and stop looping
    if ($stmt->errno != 0) {
      setError("Deleting record $recordID failed: " . $stmt->error);
      return false;
    }
  }
  return true;
}

/*
 * Adds a trainee to the database.
 * $name: the full name of the trainee.
 * $email: the trainee's UCL email address. This will be checked to ensure it is a valid email address.
 * $department: the department of the trainee as a string.
 * $managerID: the ID of the manager who this trainee should be assigned to.
 * Returns true if all operations were successful, or false otherwise.
 */
function addTrainee($name, $email, $department, $managerID) {
  // Check for blanks
  if (empty($name)) {
    setError("The name must not be blank.");
    return false;
  }
  // Check email is valid
  if (!isValidEmail($email)) {
    setError("The email '$email' is not valid.");
    return false;
  }

  // Connect to the database
  $db = new db();
  $db->connect();

  // Check that this username doesn't already exist
  $stmt = $db->prepare("SELECT email FROM trainee WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $num_rows = $result->num_rows;
  if ($num_rows > 0) {
    setError("Adding the trainee '$name' failed: A trainee with this email already exists.");
    return false;
  }

  // Insert the new trainee
  $stmt = $db->prepare("INSERT INTO trainee (name, department, email, manager_id, archived) VALUES (?, ?, ?, ?, 0)");
  $stmt->bind_param("sssi", $name, $department, $email, $managerID);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check the result. If the operation failed, find the error message and return it.
  if ($stmt->errno != 0) {
    setError("Adding the trainee '$name' failed: " . $stmt->error);
    return false;
  }

  return true;
}

 /*
  * Updates the information for a trainee in the database.
  * $traineeID: the ID of the trainee to update.
  * $name: the full name of the trainee, or NULL if the name should not be changed.
  * $email: the trainee's UCL email address, or NULL if the email should not be changed.
  * $department: the department of the trainee as a string, or NULL if the department should not be changed.
  * $managerID: the ID of the manager who this trainee should be assigned to, or NULL if the manager should not be changed.
  * Returns true if the operation was successful, or false otherwise.
  */
 function updateTrainee($traineeID, $name, $email, $department, $managerID) {
   // Check for blanks
   if (empty($name)) {
     setError("The name must not be blank.");
     return false;
   }
   // Check email is valid
   if (!isValidEmail($email)) {
     setError("The email '$email' is not valid.");
     return false;
   }

   // Connect to the database
   $db = new db();
   $db->connect();

   // If the name was changed, update it
   if ($name != NULL) {
     // Update the database
     $stmt = $db->prepare("UPDATE trainee SET name = ? WHERE id = ?");
     $stmt->bind_param("si", $name, $traineeID);
     $stmt->execute();
     $result = $stmt->get_result();

     // Check the result. If the operation failed, find the error message and return it.
     if ($stmt->errno != 0) {
       setError("Updating the name failed: " . $stmt->error);
       return false;
     }
   }

   // If the email was changed, update it
   if ($email != NULL) {
     // Update the database
     $stmt = $db->prepare("UPDATE trainee SET email = ? WHERE id = ?");
     $stmt->bind_param("si", $email, $traineeID);
     $stmt->execute();
     $result = $stmt->get_result();

     // Check the result. If the operation failed, find the error message and return it.
     if ($stmt->errno != 0) {
       setError("Updating the email failed: " . $stmt->error);
       return false;
     }
   }

   // If the department was changed, update it
   if ($department != NULL) {
     // Update the database
     $stmt = $db->prepare("UPDATE trainee SET department = ? WHERE id = ?");
     $stmt->bind_param("si", $department, $traineeID);
     $stmt->execute();
     $result = $stmt->get_result();

     // Check the result. If the operation failed, find the error message and return it.
     if ($stmt->errno != 0) {
       setError("Updating the department failed: " . $stmt->error);
       return false;
     }
   }

   // If the manager ID was changed, update it
   if ($managerID != NULL) {
     // Update the database
     $stmt = $db->prepare("UPDATE trainee SET manager_id = ? WHERE id = ?");
     $stmt->bind_param("si", $managerID, $traineeID);
     $stmt->execute();
     $result = $stmt->get_result();

     // Check the result. If the operation failed, find the error message and return it.
     if ($stmt->errno != 0) {
       setError("Updating the manager ID failed: " . $stmt->error);
       return false;
     }
   }

   return true;
 }

 /*
  * Archives a particular trainee in the database.
  * $traineeID: the ID of the trainee to archive.
  * Returns true if the operation was successful, or false otherwise.
  */
 function archiveTrainee($traineeID) {
   // Connect to the database
   $db = new db();
   $db->connect();

   // Check that this trainee isn't already archived
   $stmt = $db->prepare("SELECT archived FROM trainee WHERE id = ?");
   $stmt->bind_param("i", $traineeID);
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_array();
   $archived = $row["archived"];
   if ($archived == 1) {
     setError("Archiving trainee $traineeID failed: This trainee is already archived.");
     return false;
   }

   // Update the archived field
   $stmt = $db->prepare("UPDATE trainee SET archived = 1 WHERE id = ?");
   $stmt->bind_param("i", $traineeID);
   $stmt->execute();
   $result = $stmt->get_result();

   // Check the result. If the operation failed, find the error message and return it.
   if ($stmt->errno != 0) {
     setError("Archiving trainee $traineeID failed: " . $stmt->error);
     return false;
   }

   return true;
 }

 /*
  * Removes the archived status from an archived trainee in the database.
  * $traineeID: the ID of the archived trainee to unarchive.
  * Returns true if the operation was successful, or false otherwise.
  */
 function unarchiveTrainee($traineeID) {
   // Connect to the database
   $db = new db();
   $db->connect();

   // Check that this trainee is currently archived
   $stmt = $db->prepare("SELECT archived FROM trainee WHERE id = ?");
   $stmt->bind_param("i", $traineeID);
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_array();
   $archived = $row["archived"];
   if ($archived == 0) {
     setError("Unarchiving trainee $traineeID failed: This trainee is not archived.");
     return false;
   }

   // Update the archived field
   $stmt = $db->prepare("UPDATE trainee SET archived = 0 WHERE id = ?");
   $stmt->bind_param("i", $traineeID);
   $stmt->execute();
   $result = $stmt->get_result();

   // Check the result. If the operation failed, find the error message and return it.
   if ($stmt->errno != 0) {
     setError("Unarchiving trainee $traineeID failed: " . $stmt->error);
     return false;
   }

   return true;
 }

/*
 * Adds a manager to the database.
 * $name: the full name of the manager.
 * $username: the manager's username.
 * $email: the manager's UCL email address. This will be checked to ensure it is a valid email address.
 * $password: the plain text password for this manager's account (will be salted and hashed prior to being stored in the database).
 * Returns true if the operation was successful, or false otherwise.
 */
function addManager($name, $email, $password) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // Encrypt the password
  $hashed = password_hash($password, PASSWORD_BCRYPT);

  // Insert the new manager
  $stmt = $db->prepare("INSERT INTO manager (password, name, email, archived) VALUES (?, ?, ?, 0)");
  $stmt->bind_param("sss", $hashed, $name, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check the result. If the operation failed, find the error message and return it.
  if ($stmt->errno != 0) {
    setError("Adding the manager '$name' failed: " . $stmt->error);
    return false;
  }

  return true;
}

/*
 * Updates the information for a manager in the database.
 * $managerID: the ID of the manager to update.
 * $name: the full name of the manager, or NULL if the name should not be changed.
 * $email: the manager's UCL email address, or NULL if the email should not be changed.
 * $password: the plain text password for this manager's account, or NULL if the password should not be changed. The password will be salted and hashed prior to being stored in the database.
 * Returns true if the operation was successful, or false otherwise.
 */
function updateManager($managerID, $name, $email, $password) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // If the password was changed, update it
  if ($password != NULL) {
    // Encrypt the password
    if ($password != NULL) {
      $hashed = password_hash($password, PASSWORD_BCRYPT);
    }

    // Update the database
    $stmt = $db->prepare("UPDATE manager SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed, $managerID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check the result. If the operation failed, find the error message and return it.
    if ($stmt->errno != 0) {
      setError("Updating the password failed: " . $stmt->error);
      return false;
    }
  }

  // If the name was changed, update it
  if ($name != NULL) {
    // Update the database
    $stmt = $db->prepare("UPDATE manager SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $managerID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check the result. If the operation failed, find the error message and return it.
    if ($stmt->errno != 0) {
      setError("Updating the name failed: " . $stmt->error);
      return false;
    }
  }

  // If the email was changed, update it
  if ($email != NULL) {
    // Update the database
    $stmt = $db->prepare("UPDATE manager SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $email, $managerID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check the result. If the operation failed, find the error message and return it.
    if ($stmt->errno != 0) {
      setError("Updating the email failed: " . $stmt->error);
      return false;
    }
  }

  return true;
}

/*
 * Archives a particular manager in the database.
 * $managerID: the ID of the manager to archive.
 * Returns true if the operation was successful, or false otherwise.
 */
function archiveManager($managerID) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // Check that this manager isn't already archived
  $stmt = $db->prepare("SELECT archived FROM manager WHERE id = ?");
  $stmt->bind_param("i", $managerID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_array();
  $archived = $row["archived"];
  if ($archived == 1) {
    setError("Archiving manager $managerID failed: This manager is already archived.");
    return false;
  }

  // Update the archived field
  $stmt = $db->prepare("UPDATE manager SET archived = 1 WHERE id = ?");
  $stmt->bind_param("i", $managerID);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check the result. If the operation failed, find the error message and return it.
  if ($stmt->errno != 0) {
    setError("Archiving manager $managerID failed: " . $stmt->error);
    return false;
  }

  return true;
}

 /*
  * Removes the archived status from an archived manager in the database.
  * $managerID: the ID of the archived manager to unarchive.
  * Returns true if the operation was successful, or false otherwise.
  */
 function unarchiveManager($managerID) {
   // Connect to the database
   $db = new db();
   $db->connect();

   // Check that this manager is currently archived
   $stmt = $db->prepare("SELECT archived FROM manager WHERE id = ?");
   $stmt->bind_param("i", $managerID);
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_array();
   $archived = $row["archived"];
   if ($archived == 0) {
     setError("Unarchiving manager $managerID failed: This manager is not archived.");
     return false;
   }

   // Update the archived field
   $stmt = $db->prepare("UPDATE manager SET archived = 0 WHERE id = ?");
   $stmt->bind_param("i", $managerID);
   $stmt->execute();
   $result = $stmt->get_result();

   // Check the result. If the operation failed, find the error message and return it.
   if ($stmt->errno != 0) {
     setError("Unarchiving manager $managerID failed: " . $stmt->error);
     return false;
   }

   return true;
 }

/*
 * Adds an assessor to the database.
 * $name: the name of the new assessor.
 * Returns true if the operation was successful, or false otherwise.
 */
function addAssessor($name) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // Check that this assessor doesn't already exist
  $stmt = $db->prepare("SELECT name FROM assessor WHERE name = ?");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = $stmt->get_result();
  $num_rows = $result->num_rows;
  if ($num_rows > 0) {
    setError("Adding the assessor '$name' failed: The assessor already exists. Assessor names must be unique.");
    return false;
  }

  // Insert the new assessor
  $stmt = $db->prepare("INSERT INTO assessor (name) VALUES (?)");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check the result. If the operation failed, set the error message.
  if ($stmt->errno != 0) {
    setError("Adding the assessor '$name' failed: " . $stmt->error);
    return false;
  }

  return true;
}

/*
 * Adds an activity to the database.
 * $name: the name of the new activity.
 * Returns true if the operation was successful, or false otherwise.
 */
function addActivity($name) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // Check that this activity doesn't already exist
  $stmt = $db->prepare("SELECT name FROM activity WHERE name = ?");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = $stmt->get_result();
  $num_rows = $result->num_rows;
  if ($num_rows > 0) {
    setError("Adding the activity '$name' failed: The activity already exists. Activity names must be unique.");
    return false;
  }

  // Insert the new activity
  $stmt = $db->prepare("INSERT INTO activity (name) VALUES (?)");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check the result. If the operation failed, set the error message.
  if ($stmt->errno != 0) {
    setError("Adding the activity '$name' failed: " . $stmt->error);
    return false;
  }

  return true;
}

/*
 * Adds a group to the database.
 * $name: the name of the new group.
 * Returns true if the operation was successful, or false otherwise.
 */
function addGroup($name) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // Check that this group doesn't already exist
  $stmt = $db->prepare("SELECT name FROM group_ WHERE name = ?");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = $stmt->get_result();
  $num_rows = $result->num_rows;
  if ($num_rows > 0) {
    setError("Adding the group '$name' failed: The group already exists. Group names must be unique.");
    return false;
  }

  // Insert the new group
  $stmt = $db->prepare("INSERT INTO group_ (name) VALUES (?)");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check the result. If the operation failed, find the error message and return it.
  if ($stmt->errno != 0) {
    setError("Adding the group '$name' failed: " . $stmt->error);
    return false;
  }

  return true;
}

/*
 * Assigns an activity to a group in the database.
 * $activityID: the ID of the activity to assign.
 * $groupID: the ID of the group to assign to.
 * Returns true if the operation was successful, or false otherwise.
 */
function assignActivityToGroup($activityID, $groupID) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // Check that this activity/group combination doesn't already exist
  $stmt = $db->prepare("SELECT * FROM activity_group WHERE activity_id = ? AND group_id = ?");
  $stmt->bind_param("ii", $activityID, $groupID);
  $stmt->execute();
  $result = $stmt->get_result();
  $num_rows = $result->num_rows;
  if ($num_rows > 0) {
    setError("Assigning activity to group failed: This activity is already assigned to this group.");
    return false;
  }

  // Insert the new group/activity assignment
  $stmt = $db->prepare("INSERT INTO activity_group (activity_id, group_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $activityID, $groupID);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check the result. If the operation failed, find the error message and return it.
  if ($stmt->errno != 0) {
    setError("Assigning activity to group failed: " . $stmt->error);
    return false;
  }

  return true;
}

/*
 * Unassigns an activity from a group in the database.
 * $activityID: the ID of the activity to unassign.
 * $groupID: the ID of the group to unassign from.
 * Returns true if the operation was successful, or false otherwise.
 */
function unassignActivityFromGroup($activityID, $groupID) {
  // Connect to the database
  $db = new db();
  $db->connect();

  // Check that this activity/group combination exists
  $stmt = $db->prepare("SELECT * FROM activity_group WHERE activity_id = ? AND group_id = ?");
  $stmt->bind_param("ii", $activityID, $groupID);
  $stmt->execute();
  $result = $stmt->get_result();
  $num_rows = $result->num_rows;
  if ($num_rows == 0) {
    setError("Unassigning activity from group failed: This activity and group weren't assigned in the first place.");
    return false;
  }

  // Remove this group/activity assignment
  $stmt = $db->prepare("DELETE FROM activity_group WHERE activity_id = ? AND group_id = ?");
  $stmt->bind_param("ii", $activityID, $groupID);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check the result. If the operation failed, find the error message and return it.
  if ($stmt->errno != 0) {
    setError("Unassigning activity from group failed: " . $stmt->error);
    return false;
  }

  return true;
}

/* =============================
 * GET INFO FROM THE DATABASE
 * ============================= */

/*
 * Returns an array of records from the database. Key is id, value is a record object.
 * $traineeID: the ID of the trainee whose records should be returned.
 * $groupID: the ID of the group containing the activities which should be the only such records returned. Set $groupID to NULL to get the records for all groups.
 */
function getRecords($traineeID, $groupID) {
  $db = new db();
  $db->connect();
  $result;
  // Query the database, either filtered by the specified group or not filtered by any group.
  if (is_null($groupID)) {
    $stmt = $db->prepare("SELECT * FROM record WHERE trainee_id = ?");
    $stmt->bind_param("i", $traineeID);
  } else {
    $stmt = $db->prepare("SELECT * FROM record, activity_group WHERE activity_group.activity_id = record.activity_id AND trainee_id = ? AND group_id = ?");
    $stmt->bind_param("ii", $traineeID, $groupID);
  }

  $stmt->execute();
  $result = $stmt->get_result();

  // Loop through each row, create a record object, and put it into an array
  $array = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $comment = empty($row["comment"]) ? NULL : $row["comment"];
    $record = new record($row["trainee_id"], $row["activity_id"], $row["animal_id"], $row["level"], $row["assessor_id"], $comment, $row["date_completed"]);
    $array[$row["id"]] = $record;
  }
  return $array;
}

/*
 * Returns an array of unarchived managers from the database. Key is id, value is manager name.
 */
function getManagers() {
  $db = new db();
  $db->connect();
  $result = $db->query("SELECT id, name, archived FROM manager WHERE archived = 0");
  return getArrayFromResult($result, "id", "name");
}

/*
 * Returns the trimmed email address (without @ucl.ac.uk) for the manager with the specified ID.
 */
function getManagerEmail($managerID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT id, LEFT(email, LENGTH(email)-10) FROM manager WHERE id = ?");
  $stmt->bind_param("i", $managerID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_array(MYSQLI_ASSOC);
  return $row["LEFT(email, LENGTH(email)-10)"];
}

/*
* Returns an array of all archived managers in the database. Key is id, value is trainee name.
*/
function getArchivedManagers() {
 $db = new db();
 $db->connect();
 $result = $db->query("SELECT id, name, archived FROM manager WHERE archived = 1");
 return getArrayFromResult($result, "id", "name");
}

/*
 * Returns an array of groups from the database. Key is id, value is group name.
 */
function getGroups() {
  $db = new db();
  $db->connect();
  $result = $db->query("SELECT id, name FROM group_");
  return getArrayFromResult($result, "id", "name");
}

/*
 * Returns an array of activities from the database, filtered by the specified group ID. Key is activity id, value is activity name.
 * $groupID: the ID of the group containing the activities to be returned.
 */
function getActivitiesInGroup($groupID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT activity.id, activity.name, activity_group.activity_id, activity_group.group_id FROM activity, activity_group WHERE activity.id = activity_group.activity_id AND activity_group.group_id = ?");
  $stmt->bind_param("i", $groupID);
  $stmt->execute();
  $result = $stmt->get_result();
  return getArrayFromResult($result, "id", "name");
}

/*
 * Returns an array of activities from the database. Key is id, value is activity name.
 */
function getActivities() {
  $db = new db();
  $db->connect();
  $result = $db->query("SELECT id, name FROM activity");
  return getArrayFromResult($result, "id", "name");
}

/*
 * Returns an array of animals from the database. Key is id, value is animal name.
 */
function getAnimals() {
  // Get the animals from the DB into an array
  $db = new db();
  $db->connect();
  $result = $db->query("SELECT id, name FROM animal");
  $array = getArrayFromResult($result, "id", "name");

  // Add a n/a option which will be taken as NULL
  $array[NULL] = "n/a";

  // Return the final array
  return $array;
}

/*
 * Returns an array of the possible levels, where both the key and value are the level number.
 */
function getLevels() {
  return array(1 => 1, 2 => 2, 3 => 3);
}

/*
 * Returns an array of trainers/assesors from the database. Key is id, value is trainer/assesor name.
 */
function getAssessors() {
  $db = new db();
  $db->connect();
  $result = $db->query("SELECT id, name FROM assessor");
  return getArrayFromResult($result, "id", "name");
}

/*
 * Returns an array of all unarchived trainees associated with the specified manager. Key of returned array is trainee id, value is trainee name.
 */
function getTraineesForManager($managerID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT id, name, manager_id, archived FROM trainee WHERE archived = 0 AND manager_id = ?");
  $stmt->bind_param("i", $managerID);
  $stmt->execute();
  $result = $stmt->get_result();
  return getArrayFromResult($result, "id", "name");
}


/*
 * Returns an array of all archived trainees associated with the specified manager. Key of returned array is trainee id, value is trainee name.
 */
function getArchivedTraineesForManager($managerID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT id, name, manager_id, archived FROM trainee WHERE archived = 1 AND manager_id = ?");
  $stmt->bind_param("i", $managerID);
  $stmt->execute();
  $result = $stmt->get_result();
  return getArrayFromResult($result, "id", "name");
}

/*
 * Returns an array of all unarchived trainees from the database. Key is id, value is trainee name.
 */
function getTrainees() {
  $db = new db();
  $db->connect();
  $result = $db->query("SELECT id, name, archived FROM trainee WHERE archived = 0");
  return getArrayFromResult($result, "id", "name");
}

/*
 * Returns the email address for the trainee with the specified ID.
 */
function getTraineeEmail($traineeID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT id, email FROM trainee WHERE id = ?");
  $stmt->bind_param("i", $traineeID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_array(MYSQLI_ASSOC);
  return $row["email"];
}

/*
 * Returns the department for the trainee with the specified ID.
 */
function getTraineeDepartment($traineeID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT id, department FROM trainee WHERE id = ?");
  $stmt->bind_param("i", $traineeID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_array(MYSQLI_ASSOC);
  return $row["department"];
}

/*
 * Returns the manager ID of the manager for the trainee with the specified trainee ID.
 */
function getTraineeManager($traineeID) {
  $db = new db();
  $db->connect();
  $stmt = $db->prepare("SELECT id, manager_id FROM trainee WHERE id = ?");
  $stmt->bind_param("i", $traineeID);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_array(MYSQLI_ASSOC);
  return $row["manager_id"];
}

/*
 * Returns an array of all archived trainees in the database. Key is id, value is trainee name.
 */
function getArchivedTrainees() {
 $db = new db();
 $db->connect();
 $result = $db->query("SELECT id, name, archived FROM trainee WHERE archived = 1");
 return getArrayFromResult($result, "id", "name");
}

/*
 * Returns an array of all trainees in the database. Key is id, value is trainee name.
 */
function getAllTrainees() {
 $db = new db();
 $db->connect();
 $result = $db->query("SELECT id, name, archived FROM trainee");
 return getArrayFromResult($result, "id", "name");
}

/*
 * Returns the mysqli_result object as an array.
 * $result: the mysqli_result object.
 * $keyColumn: the name of the column to use as the key in the array.
 * $valueColumn: the name of the column to use as the value in the array.
 * Returns an array containing the result.
 */
function getArrayFromResult($result, $keyColumn, $valueColumn) {
  // Loop through the result and return an array
  $array = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $array[$row[$keyColumn]] = $row[$valueColumn];
  }
  return $array;
}

/*
 * Returns true if the string is a valid email address, false otherwise.
 */
function isValidEmail($email) {
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return true;
  } else {
    return false;
  }
}


/*
 * Destroys session. Implemented for the log out function.
 */
 function destroySession()  {    $_SESSION=array();
  if (session_id() != "" || isset($_COOKIE[session_name()]))
  setcookie(session_name(), '', time()-2592000, '/');
  session_destroy();  }


?>
