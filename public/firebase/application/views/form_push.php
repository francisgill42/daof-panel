<!DOCTYPE html>
<html lang="en">
<head>
    <title>FireBase Push Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <?php $message = $this->session->flashdata('message');?>
    <?php if($message){ ?>
        <div class="row">
            <div class="col-md-12">
                <?php $msg = $message; ?>
                <div class="alert <?php echo $msg['class'] ?> alert-dismissible mb-2 ">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $msg['message']; ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <form action="<?=current_url();?>" method="POST">
        <div class="form-group">
            <label for="Title">Title</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="form-group">
            <label for="Body">Body</label>
            <textarea class="form-control" rows="6" name="body" required></textarea>
        </div>
        <div class="form-group">
            <label for="Page Name">Page Name</label>
            <select name="page_name" class="form-control" required>
                <option value="" disabled selected>::Select Page::</option>
                <?php foreach (unserialize(PAGES) as $key =>$page){ ?>
                    <option value="<?=$key;?>"><?=$page;?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>

</body>
</html>