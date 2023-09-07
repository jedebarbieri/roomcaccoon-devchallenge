<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="js/lib/bootstrap-5.3.1-dist/css/bootstrap.min.css" rel="stylesheet"/>
    <title>Shopping List</title>
    <style>
        div.item .btn {
            display: none;
        }
        div.item:hover {
            background-color: #f5f5f5;
        }
        div.item:hover .btn {
            display: inline-block;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-5">Shopping List</h2>
        <form id="itemForm">
            <div class="row">
                <div class="col-10">
                    <input class="form-control" name="name" type="text"/>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary">+</button>
                </div>
            </div>
        </form>
        <div class="container my-4" id="itemList">
        </div>
    </div>
    <script src="js/lib/code.jquery.com_jquery-3.7.1.min.js"></script>
    <script src="js/lib/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js"></script>
	<script src="js/scripts.js"></script>
</body>
</html>