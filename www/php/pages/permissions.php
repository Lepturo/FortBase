<link rel="stylesheet" href="../../css/permissions.css">
<?php 
    $userService = new TrainingService();

    $users = $userService->getAllUsers();
?>
<body>
    <div class="stream-wrapper">
        <form method="POST" action="#">
        <?php
        foreach($users as $user) {
            $userRole = $user->getGroup();
            $username = $user->getUsername();
            echo '<div class="row">
                <div class="row-username">' . $user->getUsername() .'</div>
                <div class="row-permission">
                <div class="btn-group" data-group-name="'. $username .'">
                <button type="button" class="btn '. ($userRole == "UNVERIFIED" ? "btn-selected" : "") .'">Unverified</button>
                <button type="button" class="btn '. ($userRole == "VERIFIED" ? "btn-selected" : "") .'">Verified</button>
                <button type="button" class="btn '. ($userRole == "ADMIN" ? "btn-selected" : "") .'">Admin</button>
                </div>
                </div>
            </diV>';
        }
        ?>
        <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <!-- <div class="radio-option">
                <input type="radio" name="permission_role'. $username .'" id="unverified" value="UNVERIFIED" '. ($userRole == "UNVERIFIED" ? "checked" : "") .'>
                <label for="unverified">Unverified</label>
                </div>
                <div class="radio-option">
                <input type="radio" name="permission_role'. $username .'" id="verified" value="VERIFIED" '. ($userRole == "VERIFIED" ? "checked" : "") .'>
                <label for="verified">Verified</label>
                </div>
                <div class="radio-option">
                <input type="radio" name="permission_role'. $username .'" id="admin" value="ADMIN" '. ($userRole == "ADMIN" ? "checked" : "") .'>
                <label for="admin">Admin</label>
                </div> -->
    <?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = htmlspecialchars($_POST["username"]);
        $newRole = htmlspecialchars($_POST["newRole"]);

        echo "Updating user role for $username to $newRole";
    }

    function updateUserRole($username, $newRole) {
        $trainingService = new TrainingService();

        $trainingService->updateUserRole($username, $newRole);
    }
    ?>
    <script>
        function changeUserRole($username, $newRole) {
            fetch("#", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "username=" + encodeURIComponent($username) + "&newRole=" + encodeURIComponent($newRole),
            })
            .then(response => response.text())
            .then(data => console.log("Response from PHP:", data))
            .catch(error => console.error("Error:", error));
        }
        
        // change highlighted button on click
        document.querySelectorAll(".btn-group").forEach(group => {
            group.addEventListener("click", (event) => {
                if(event.target.classList.contains("btn")) {
                    group.querySelectorAll(".btn").forEach(btn => {
                        btn.classList.remove("btn-selected");
                    });
                    event.target.classList.add("btn-selected");
                
                    $targetUsername = event.currentTarget.getAttribute("data-group-name");
                    $targetRole = event.target.textContent;    
                    changeUserRole($targetUsername, $targetRole);
                }
            });
        }); 

        //add hidden input with selected buttons
        document.querySelector("form").addEventListener("submit", (event) => {
            document.querySelectorAll(".btn-group").forEach(group => {
                const selectedBtn = group.querySelector(".btn-selected");
                if(selectedBtn) {
                    let inputName = group.getAttribute("data-group-name");
                    let hiddenInput = document.querySelector(`input[name="${inputName}"]`);
                
                    if(!hiddenInput) {
                        const hiddenInput = document.createElement("input");
                        hiddenInput.type = "hidden";
                        hiddenInput.name = inputName;
                        event.target.appendChild(hiddenInput);
                    }

                    hiddenInput.value = selectedBtn.textContent;

                }
            });
        });

    </script>
</body>