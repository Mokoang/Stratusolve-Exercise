<?php
/**
 * Created by PhpStorm.
 * User: johangriesel
 * Date: 13052016
 * Time: 08:48
 * @package    ${NAMESPACE}
 * @subpackage ${NAME}
 * @author     johangriesel <info@stratusolve.com>
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Basic Task Manager</title>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    </head>
    <body>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                  
                        <div class="modal-body">
                         <form action="update_task.php" method="post"> 
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 5px;;">
                                    <input id="InputTaskName" type="text" placeholder="Task Name" class="form-control" required="">
                                </div>
                                <div class="col-md-12">
                                    <textarea id="InputTaskDescription" placeholder="Description" class="form-control" required=""></textarea>
                                </div>
                            </div>
                           </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button id="deleteTask" type="button" class="btn btn-danger">Delete Task</button>
                            <button id="saveTask" type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-6">
                    <h2 class="page-header">Task List</h2>
                    <!-- Button trigger modal -->
                    <button id="newTask" type="button" class="btn btn-primary btn-lg" style="width:100%;margin-bottom: 5px;" data-toggle="modal" data-target="#myModal">
                        Add Task
                    </button>
                    <!--ADDED #### -->
               <!-- <div id="taskSuccess" class="list-group">
                        SUCCESS
                    </div> -->
                    <div id="TaskList" class="list-group">
                        <!-- Assignment: These are simply dummy tasks to show how it should look and work. You need to dynamically update this list with actual tasks -->
                    </div>
                </div>
                <div class="col-md-3">

                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var currentTaskId = -1;
        var taskDesID = "";
        $('#myModal').on('show.bs.modal', function (event) {
            var triggerElement = $(event.relatedTarget); // Element that triggered the modal
            var modal = $(this);
            if (triggerElement.attr("id") == 'newTask') {
                modal.find('.modal-title').text('New Task');
                $('#deleteTask').hide();
                currentTaskId = -1;
                //set input task name and text area decription to Null when addiing a new task
                $('#InputTaskName').val(""); 
                $('#InputTaskDescription').val("");
            } else {
                modal.find('.modal-title').text('Task details');
                $('#deleteTask').show();
                currentTaskId = triggerElement.attr("id");
                /*var h4 = "#"+currentTaskId+" h4";
                var p = "#"+currentTaskId+" p";
                var h4text =  $("#"+currentTaskId+" h4").text();
                var ptext =  $("#"+currentTaskId+" p").text();*/
            
                 //get task name and task description and set to input task name and text area decription respectfully
                $('#InputTaskName').val($("#"+currentTaskId+" h4").text());
                $('#InputTaskDescription').val($("#"+currentTaskId+" p").text());
                console.log('Task ID: ' + triggerElement.attr("id"));
            }
        });
       
        $('#saveTask').click(function () {
            //Assignment: Implement this functionality
            //var taskName = $('#InputTaskName').val().trim();
            //var taskDescription = $('#InputTaskDescription').val().trim();
            
            //get task name and task description and convert to json object 
            var taskData = { "TaskName": $('#InputTaskName').val().trim(), "TaskDescription": $('#InputTaskDescription').val().trim()};
            var jsonTaskData = JSON.stringify(taskData);
            
            //send data to update_task.php.
            //when current task id=-1 then add a new task else edit or delete
            //oper = edit. oper is used to indicate weather we delete or edit a task
            $.ajax({
                url: 'update_task.php',
                type: 'POST',
                data: {taskId: JSON.stringify(currentTaskId) ,oper:JSON.stringify('EDIT') , jsonTaskData: jsonTaskData},
                success: function (response) {
                  //  $('#taskSuccess').html(response);
                }
            });
            //
            alert('Save... Id:' + currentTaskId);
            $('#myModal').modal('hide');
            updateTaskList();
        });
        $('#deleteTask').click(function () {
            //Assignment: Implement this functionality
            
            
            //send data to update_task.php.
            //when current task id=-1 then add a new task else edit or delete
            //oper = delete. oper is used to indicate weather we delete or edit a task
            $.ajax({
                url: 'update_task.php',
                type: 'POST',
                data: {taskId:JSON.stringify(currentTaskId) ,oper:JSON.stringify('DELETE')},
                success: function (response) {
                  //  $('#taskSuccess').html(response);
                }
            });
            alert('Delete... Id:' + currentTaskId);
            $('#myModal').modal('hide');
            updateTaskList();
        });
        function updateTaskList() {
            $.post("list_tasks.php", function (data) {
                $("#TaskList").html(data);
            });
        }
        updateTaskList();
    </script>
</html>