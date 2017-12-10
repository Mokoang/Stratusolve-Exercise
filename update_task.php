<?php

/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require('task.class.php');
// Assignment: Implement this script
$taskId = json_decode(filter_input(INPUT_POST, "taskId")); //$taskId is either -1 or the natural number
$taskData = json_decode(filter_input(INPUT_POST, "jsonTaskData"), TRUE);//decodes task data
$oper = json_decode(filter_input(INPUT_POST, "oper"));//$oper means either delete or edit operation

$task = new Task();

//if $taskId=-1 then add a new task, else delete or edit
if ($taskId == -1) {
    
 if ( ($taskData["TaskName"] == NULL) || ($taskData["TaskDescription"] == NULL) ) { //if either takname or task description or both are empty then use default task name and task description 
        $taskDefault = array("TaskId" => $task->TaskId, "TaskName" => $task->TaskName, "TaskDescription" => $task->TaskDescription);
       $task->Save($taskDefault, "Task_Data.txt");//save new task to file
      
    }else{
      $uniqueID = $task->getUniqueId();
      $taskArrayID = array("TaskId"=>$uniqueID);
      $taskData = array_merge($taskArrayID, $taskData);
      $task->Save($taskData, "Task_Data.txt"); 
    }
} else {
    
    //if operation is edit then edit the task, else delete it.
    if ($oper == "EDIT") {
       // $taskIdasArray = array("TaskId"=>$taskId);
       $taskData = array_merge(array("TaskId"=>$taskId), $taskData);
        $task->Edit($task->Search($taskId), $taskData);
    } else {
        $task->Delete($task->Search($taskId));
    }
}
?>