 <!-- Home Summary page-->

      <div role="tabpanel" class="tab-pane active" id="home">
        <div class="container">
          <div class="row">
            <div class="col-md-5  toppad  pull-right col-md-offset-3 ">
            </div>
            <div class="toppad" >
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo $_SESSION['first'] . " " . $_SESSION['last'];?></h3>
                </div>
                <div class="panel-body">
                <div class="row">
                  <div class=" col-md-9 col-lg-9 ">
                    <table class="table table-user-information">
                      <tbody>
                        <!-- Name -->
                        <tr>
                          <td>Name</td>
                          <td><?php echo $_SESSION['first'] . " " . $_SESSION['last'];?></td>
                        </tr>
                        <!-- Email and phone -->
                        <?php
                          //retrieve basic information about the user
                          $sql = "SELECT * FROM administrators WHERE icnum = '$_SESSION[icnum]'";
                          $result = pg_query($db, $sql);// Query template
                          //show error
                          if (!$result) {
                            echo '<script language="javascript">';
                            echo 'alert("Oops, please try again!")';
                            echo '</script>';
                          } else {
                            //nothing
                          }
                          //display retrieved information
                          while ($row = pg_fetch_assoc($result)) {
                            $email = $row['email'];
                            echo "<tr>";
                            echo "<td>Email</td>";
                            echo "<td><a href=\"mailto:" + $email+ "\">"+ $email +"</></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>Phone</td>";
                            echo "<td>" . $row['phonenum'] . "</td>";
                            echo "</tr>";
                          }
                        ?>
                        <!-- Cars -->
                        <tr>
                          <td>Cars</td>
                          <td>
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Plate Number</th>
                                  <th>Model</th>
                                  <th>Seats</th>
                                </tr>
                              </thead>
                              <tbody>
                                  <?php
                                    $result = pg_query($db, "SELECT * FROM cars WHERE plateNum IN (SELECT plateNum FROM drive WHERE icnum = '$_SESSION[icnum]')");
                                    if (!$result) {
                                      echo "An error occurred.\n";
                                      exit;
                                    }
                                    $firstRow = pg_fetch_assoc($result);
                                    if (!$firstRow) {
                                      echo "<div align='center'>The administrator is not a driver</div>";
                                    }
                                    else {
                                      echo "<tr>";
                                      echo "<th>" . $firstRow['platenum'] . "</th>";
                                      echo "<th>" . $firstRow['models'] . "</th>";
                                      echo "<th>" . $firstRow['numseats'] . "</th>";
                                      echo "</tr>";
                                    }

                                    while ($row = pg_fetch_assoc($result)) {
                                      echo "<tr>";
                                      echo "<th>" . $row['platenum'] . "</th>";
                                      echo "<th>" . $row['models'] . "</th>";
                                      echo "<th>" . $row['numseats'] . "</th>";
                                      echo "</tr>";
                                    }
                                  ?>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        <!-- Advertisements -->
                        <tr>
                          <td>Advertisements</td>
                          <td>
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Ad ID</th>
                                  <th>Origin</th>
                                  <th>Destination</th>
                                  <th>Time</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  //retrieve ad posting information about the user
                                  $sql = "SELECT DISTINCT uaa.adid, uaa.origin, uaa.destination, uaa.doa
                                          FROM ((administrators u natural left join advertise a ) natural join advertisements) as uaa
                                          WHERE uaa.icnum = '$_SESSION[icnum]'";
                                  $result = pg_query($db, $sql);// Query template
                                  //show error
                                  if (!$result) {
                                   echo '<script language="javascript">';
                                   echo 'alert("Oops, please try again!")';
                                   echo '</script>';
                                  }

                                  //display retrieved ad posting information
                                  while ($row = pg_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<th>" . $row['adid'] . "</th>";
                                    echo "<th>" . $row['origin'] . "</th>";
                                    echo "<th>" . $row['destination'] . "</th>";
                                    echo "<th>" . $row['doa'] . "</th>";
                                    echo "</tr>";
                                  }
                                ?>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        <!-- Bids -->
                        <tr>
                          <td>My Bids</td>
                          <td>
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Origin</th>
                                  <th>Destination</th>
                                  <th>Time</th>
                                  <th>Points</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  //retrieve ad posting information about the user
                                  $sql = "SELECT origin, destination, doa, bidpoints, status
                                          FROM bid, advertisements a
                                          WHERE bid.adid = a.adid
                                          AND bid.icnum = '$_SESSION[icnum]'";
                                  $result = pg_query($db, $sql);// Query template
                                  //show error
                                  if (!$result) {
                                   echo '<script language="javascript">';
                                   echo 'alert("Oops, please try again!")';
                                   echo '</script>';
                                  }

                                  //display retrieved ad posting information
                                  while ($row = pg_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<th>" . $row['origin'] . "</th>";
                                    echo "<th>" . $row['destination'] . "</th>";
                                    echo "<th>" . $row['doa'] . "</th>";
                                    echo "<th>" . $row['bidpoints'] . "</th>";
                                    echo "<th>" . $row['status'] . "</th>";
                                    echo "</tr>";
                                  }
                                ?>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <a href="#" class="btn btn-primary">Back To Top</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>