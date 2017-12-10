<?php

/**
 * This class handles the modification of a task object
 */
class Task {

    public $TaskId;
    public $TaskName;
    public $TaskDescription;
    protected $TaskDataSource;

    public function __construct($Id = null) {
        $this->TaskDataSource = file_get_contents('Task_Data.txt');
        if (strlen($this->TaskDataSource) > 0)
            $this->TaskDataSource = json_decode($this->TaskDataSource); // Should decode to an array of Task objects
        else
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array

        if (!$this->TaskDataSource)
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
        if (!$this->LoadFromId($Id))
            $this->Create();
    }

    protected function Create() {
        // This function needs to generate a new unique ID for the task
        // Assignment: Generate unique id for the new task
        $this->TaskId = $this->getUniqueId();
        $this->TaskName = 'New Task';
        $this->TaskDescription = 'New Description';
    }

    public function getUniqueId() {
        // Assignment: Code to get new unique ID
        $taskArray = json_decode(file_get_contents('Task_Data.txt'), TRUE);  //read and decode the file,keep the data as associative array
        
        //if the file is empty then unique id=1 else find task id of the last element in the array and increment by one 
        $uniqueID = 1;                                                       
        if (count($taskArray) > 0) {
            $uniqueID = ($taskArray[count($taskArray) - 1]['TaskId']) + 1;
        }
        return $uniqueID;
    }

    protected function LoadFromId($Id = null) {
        if ($Id) {
            // Assignment: Code to load details here...
            
            
            $taskArray = json_decode(file_get_contents('Task_Data.txt')); //read and decode the file,keep the data as associative array
             //Find the task index through searching task id value e.g if TaskId = 2 then $taskIndex = Search(2)
            //then use the task index to find task name and task description of the corresponding task id
            $taskIndex = Search($Id);
            $this->TaskName = $taskArray[$taskIndex]['TaskName'];
            $this->TaskDescription = $taskArray[$taskIndex]['TaskDescription'];
        } else
            return null;
    }

    public function Save($taskData, $filename) {
        //$taskData = associative array e.g ["TaskId":1,"TaskName":"Test","TaskDescription":"Test"]
        //Assignment: Code to save task here  
        $taskArray = json_decode(file_get_contents('Task_Data.txt')); //READ file and convert to array
         //add $taskData to array of tasks
        //if the file is empty then write to file else append the current data to the one already on the file
        if(count($taskArray)==0){   
        echo file_put_contents($filename, "[".json_encode($taskData)."]"); //WRITE to file
        }
        else{
           array_push($taskArray, $taskData);  
        echo file_put_contents($filename, json_encode($taskArray)); //WRITE to file
        }
    }

    public function Delete($taskIndex) {
        //Assignment: Code to delete task here
        $taskArray = json_decode(file_get_contents('Task_Data.txt')); //READ file and convert to array
        //$taskIndex is the index in the array of the chosen task
        unset($taskArray[$taskIndex]); //removes the task at $taskIndex from the array
        $newTasks = array_values($taskArray);//resizes the array after deleting 
        echo file_put_contents('Task_Data.txt', json_encode($newTasks)); //WRITE to file
    }

    public function Edit($taskIndex, $editTask) {
        //Assignment: Code to delete task here
        //$editTask is the new  task name or task description or both 
        //task index is the position of the task to edit
        $taskArray = json_decode(file_get_contents('Task_Data.txt'), TRUE); //READ file and convert to array
        $taskArray[$taskIndex] = $editTask;

        echo file_put_contents('Task_Data.txt', json_encode($taskArray)); //WRITE to file
    }

    public function Search($token) {
        
        //$token is the task ID . e.g TaskId = 2 then $token =2
        //if the file is empty, the task index will be -1 else the index is a natural number
        //$index is the position of the task in the array
        $taskArray = json_decode(file_get_contents('Task_Data.txt')); //READ file and convert to array
        $index = 0;
        foreach ($taskArray as $task) {
            if ($task->TaskId == $token) {
                return $index;
            } else {
                $index++;
            }
        }
        return -1;
    }

}

?>