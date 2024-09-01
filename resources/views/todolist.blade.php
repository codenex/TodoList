<!DOCTYPE html>
<html lang="en">
<head>
  <title>To Do List</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h2>PHP - Simple To Do List App</h2>
    <div class="row">
        <div class="col-sm-4">
            <input type="text" name="task" id="task" class="form-control">
        </div>
        <div class="col-sm-3">
            <a class="btn btn-primary" id="add_task">Add Task</a>
        </div>
        <div class="col-sm-3">
            <a class="btn btn-info" id="show_all_tasks">Show All</a>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-12">    
            <table class="table" id="task_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTask as $key=>$value)
                    <tr id="{{ $value->id }}">
                        <td>{{$value->id}}</td>
                        <td>{{$value->name}}</td>
                        <td>{{($value->is_active == 1) ? 'Done' : 'Not Done'}}</td>
                        <td>
                            @if($value->is_active != 1)
                            <a class="btn btn-success" onclick="markComplete({{ $value->id }})" data-id="{{ $value->id }}"><span class="glyphicon glyphicon-ok"></span></a>
                            @endif
                            <!-- <a class="btn btn-danger"  onclick="deleteTask({{ $value->id }})" data-id="{{ $value->id }}"><span class="glyphicon glyphicon-remove"></span></a> -->
                            <a class="btn btn-danger delBtn"  data-toggle="modal" data-target="#deleteModal" data-id="{{ $value->id }}"><span class="glyphicon glyphicon-remove"></span></a>

                        </td>
                    </tr>
                    @empty
                        <tr id="no_task">
                            <td colspan="4" class="text-center">
                                No tasks found!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure to delete this task?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="delId" data-dismiss="modal">Delete</button>
      </div>
    </div>

  </div>
</div>

<script>
    $('#add_task').click(function() {
        $.ajax({
            url: "{{ route('add_task') }}",
            method: "POST",
            data: { name: $('#task').val(), _token: "{{ csrf_token() }}" },
            success: function(result) {
                if (result.status == 200) {
                    if(result) {
                        $('#no_task').empty()
                        $('#task_table tbody').append(`
                            <tr id="${result.tasks.id}">
                                <td>${result.tasks.id}</td>
                                <td>${result.tasks.name}</td>
                                <td>${result.tasks.is_active == 1 ? 'Done' : 'Not Done'}</td>
                                <td>
                                    <a class="btn btn-success" onclick="markComplete(${result.tasks.id})" data-id="${result.tasks.id}"><span class="glyphicon glyphicon-ok"></span></a>
                                    <a class="btn btn-danger delBtn"  data-toggle="modal" data-target="#deleteModal" data-id="${result.tasks.id}"><span class="glyphicon glyphicon-remove"></span></a>
                                </td>
                            </tr>
                        `);
                    }
                } else if (result.status == 404) {
                    alert(result.message)
                } else {
                    alert('Something went wrong!')
                }
            }
        })
    })

    function markComplete(id) {
        $.ajax({
            url: "{{ route('mark_task_complete') }}",
            method: "POST",
            data: {
                taskId: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {

                $('tr[id="' + id + '"]').remove();
                if ($('#task_table tbody tr').length === 0) {
                    $('#task_table tbody').append('<tr id="no_task"><td colspan="4" class="text-center">No tasks found!</td></tr>');
                }
            }
        });
    }


    $('#show_all_tasks').click(function() {
        $.ajax({
            url: "{{ route('get_all_tasks') }}",
            method: "GET",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (result){
                $('#task_table tbody').empty()

                let html = ''

                if (result.tasks && result.tasks.length > 0) {
                    $.each(result.tasks, function(index, task) {
                        if(task.is_active != 1) {
                            html = `<tr id="${task.id}">
                                <td>${task.id}</td>
                                <td>${task.name}</td>
                                <td>${task.is_active == 1 ? 'Done' : 'Not Done'}</td>
                                <td>
                                    <a class="btn btn-success" onclick="markComplete(${task.id})" data-id="${task.id}">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </a>
                                    <a class="btn btn-danger delBtn"  data-toggle="modal" data-target="#deleteModal" data-id="${task.id}">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </td>
                            </tr>`
                        } else {
                            html = `<tr id="${task.id}">
                                <td>${task.id}</td>
                                <td>${task.name}</td>
                                <td>${task.is_active == 1 ? 'Done' : 'Not Done'}</td>
                                <td>
                                    <a class="btn btn-danger delBtn"  data-toggle="modal" data-target="#deleteModal" data-id="${task.id}">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </td>
                            </tr>`
                        }
                        

                        $('#task_table tbody').append(html)
                    })
                } else {
                    
                    $('#task_table tbody').append(`
                        <tr id="no_task">
                            <td colspan="4" class="text-center">No tasks found!</td>
                        </tr>
                    `)
                }
            }
        })
    })

    
    $('.delBtn').click(function() {
        // alert($(this).data('id'))
        $('#delId').attr('onclick', 'deleteTask('+$(this).data('id')+')')
    })


    function deleteTask(id) {
        $.ajax({
            url: "{{ route('dalete_task') }}",
            method: "POST",
            data: {
                taskId: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                if (result.status == 200) {

                    $('tr[id="' + id + '"]').remove();
                    if ($('#task_table tbody tr').length === 0) {
                        $('#task_table tbody').append('<tr id="no_task"><td colspan="4" class="text-center">No tasks found!</td></tr>');
                    }

                    // alert(result.message)

                } else if (result.status == 404) {
                    alert(result.message)

                } else {
                    alert('Something went wrong!')
                }
            }
        })
    }

    
</script>
</body>
</html>

