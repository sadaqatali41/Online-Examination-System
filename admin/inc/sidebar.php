<aside class="main-sidebar">
    <section class="sidebar">
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Master</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/center.php">
                            <i class="fa fa-dashboard"></i> <span>Center</span>
                        </a>
                    </li> 
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>mstr/mstr_gst.php">
                            <i class="fa fa-dollar"></i> <span>GST Master</span>
                        </a>
                    </li>
                </ul>
            </li>        
        </ul>
    </section>
</aside>