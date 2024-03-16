<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Upload Image in PHP and MySQL</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
        <script src="js/upload.js"></script>
    </head>
    <body>
        <?php
        include "cfg/dbconnect.php";
        $sql = "select * from users order by name";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        include "upload.php";
        ?>
        <div class="container">
            <h1 class="mb-5">Upload Image in PHP and MySQL</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input
                        type="text"
                        class="form-control"
                        name="name"
                        id="name"
                        aria-describedby="helpId"
                        placeholder="Enter a Name"
                    />
                    <div class="text-danger"><?= $name_err?></div>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Select A Photo</label>
                    <input
                        type="file"
                        class="form-control"
                        name="photo"
                        id="photo"
                        placeholder=""
                        aria-describedby="fileHelpId" onchange="previewImg(this)"
                    />
                    <div id="fileHelpId" class="form-text">Allowed file types: jpg, jpeg, png</div>
                    <div class="text-danger"><?=$photo_err?></div>
                </div>
                <div class="mb-5 image-preview"></div>
                <!-- to display image preview -->
                <button
                    type="submit"
                    name ="submit"
                    class="btn btn-primary">
                    Submit
                </button>
            </form>
            <div class="text-danger"><?= $err_msg ?></div>
            <div
                class="table-responsive">
                <h2>List of users</h2>
                <table class="mt-5 table table-bordered table-striped" >
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows>0){
                            $counter = 0;
                            foreach($result as $row){ 
                                $counter++;
                                ?>
                                <tr class="">
                                    <td scope="row"><?= $counter?></td>
                                    <td><?=$row['name']?></td>
                                    <td><a class="view" href="" data-bs-toggle="modal" data-bs-target="#modalId" data-id="<?=$row['photo']?>"><img class="photo" src = "uploads/<?=$row['photo']?>"></a></td>

                                    <td>
                                        <a href="index.php?id=<?=$row['id']?>" onclick="return confirm('Are you sure you want to delete the user?')"><button
                                            type="button"
                                            class="btn btn-danger" title="delete the user">
                                            Delete
                                        </button></a>
                                        
                                    </td>
                                </tr>


                            <?php }
                        }
                        else { ?>
                        <tr>
                            <td>No users to display</td>
                        </tr>
                        <?php }?>   
                    </tbody>
                </table>
            </div>
            
        </div>
   
    <!-- Modal -->
    <div
        class="modal fade"
        id="modalId"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Display Photo
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <!-- display photo here -->
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>