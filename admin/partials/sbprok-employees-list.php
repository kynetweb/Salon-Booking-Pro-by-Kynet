<?php
echo '<h2> Employees <span> <a href = "admin.php?page=sbprok_add_employee"><button class="sbprok-employee-form-button">Add New</button></a></span></h2>';
echo '<table id="sbprok_emp_table" class="display sbprok-employeelist">
        <thead>
            <tr>
            <th> Sr. No. </th>
                <th>Username</th>
                <th>Email</th>
                <th>Edit/Update</th>
            </tr>
        </thead>
        <tbody>';
        $sr_no = 1;
        foreach ( $users as $user ) {
            $user_link = get_edit_user_link($user->ID);
            echo '<tr>';
            echo '<td>' . $sr_no++ . '</td>';
            echo '<td>' . $user->display_name . '</td>';
            echo "<td>" . $user->user_email .'</a></td>';
            echo "<td> <a href = $user_link>".'<input class = "sbprok-employee-form-button" type="button" value="Edit Employee"></td> </tr>';
        }
        echo '</tbody>
    </table>';