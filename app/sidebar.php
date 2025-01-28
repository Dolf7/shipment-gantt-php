  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="./index.php?page=profile" class="brand-link mt-2 pb-3 mb-3"" style=" text-align:center; display:flex; flex-direction:column; justify-content:center; align-items:center">
          <!-- <img width="90%" height="auto" src="../assets/img/itron.png" alt="Logo" style="opacity:1"> -->
          <span class="brand-text font-weight-light">Shipment on Delivery</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
          <!-- SidebarSearch Form -->
          <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                  <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                      <button class="btn btn-sidebar">
                          <i class="fas fa-search fa-fw"></i>
                      </button>
                  </div>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                  <li class="nav-item <?php if ($page == 'profile' || $page == '') echo 'menu-open'; ?>"">
                      <a href=" #" class="nav-link <?php
                                                    if ($page == 'profile' || $page == '') echo 'active'; ?>">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>
                          Dashboard
                          <i class="right fas fa-angle-left"></i>
                      </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="./index.php?page=profile" class="nav-link <?php
                                                                                    if ($page == 'profile' || $page == '') echo 'active';
                                                                                    ?>">
                                  <i class="far fa-user nav-icon"></i>
                                  <p>Profile</p>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <!-- Schedules -->
                  <li class="nav-item <?php if ($page == 'schedule-templates') echo 'menu-open'; ?>"">
                      <a href=" #" class="nav-link <?php if ($page == 'schedule-templates') echo 'active'; ?>">
                      <i class="nav-icon fas fa-table"></i>
                      <p>
                          Shipment
                          <i class="right fas fa-angle-left"></i>
                      </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="./index.php?page=shipment" class="nav-link <?php
                                                                                    if ($page == 'shipment') echo 'active';
                                                                                    ?>">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Shipment</p>
                              </a>
                          </li>
                      </ul>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="./index.php?page=schedule-templates" class="nav-link <?php
                                                                                            if ($page == 'schedule-templates') echo 'active';
                                                                                            ?>">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Template</p>
                              </a>
                          </li>
                      </ul>
                  </li>


                  <!-- TABLE -->
                  <li class="nav-item <?php if ($page == 'data-table') echo 'menu-open'; ?>"">
                      <a href=" #" class="nav-link <?php if ($page == 'data-table') echo 'active'; ?>">
                      <i class="nav-icon fas fa-table"></i>
                      <p>
                          Tables
                          <i class="right fas fa-angle-left"></i>
                      </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="./index.php?page=data-table" class="nav-link <?php
                                                                                    if ($page == 'data-table') echo 'active';
                                                                                    ?>">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Data Table</p>
                              </a>
                          </li>
                      </ul>
                  </li>

                  <!-- FORMS -->
                  <li class="nav-item <?php if ($page == 'form-basic') echo 'menu-open'; ?>">
                      <a href=" #" class="nav-link <?php if ($page == 'form-basic') echo 'active'; ?>">
                          <i class="nav-icon fas fa-edit"></i>
                          <p>
                              Forms
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="./index.php?page=form-basic" class="nav-link <?php
                                                                                    if ($page == 'form-basic') echo 'active';;
                                                                                    ?>">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Form Basic</p>
                              </a>
                          </li>
                      </ul>
                  </li>

                  <!-- Chart -->
                  <li class="nav-item <?php if ($page == 'chart-basic' || $page == 'chart-inline') echo 'menu-open'; ?>">
                      <a href=" #" class="nav-link <?php if ($page == 'chart-basic') echo 'active'; ?>">
                          <i class="nav-icon fas fa-chart-pie"></i>
                          <p>
                              Chart
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="./index.php?page=chart-basic" class="nav-link <?php
                                                                                        if ($page == 'chart-basic') echo 'active';
                                                                                        ?>">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Chart Basic</p>
                              </a>
                          </li>
                      </ul>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="./index.php?page=chart-inline" class="nav-link <?php
                                                                                        if ($page == 'chart-inline') echo 'active';
                                                                                        ?>">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Chart Inline</p>
                              </a>
                          </li>
                      </ul>
                  </li>

              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>