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
            <!-- masters -->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder-open"></i>&nbsp;
                    <span>Master</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/center">
                            <i class="fa fa-circle-o"></i> <span>Centers</span>
                        </a>
                    </li> 
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/course_category">
                            <i class="fa fa-circle-o"></i> <span>Course Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/course">
                            <i class="fa fa-circle-o"></i> <span>Courses</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/question">
                            <i class="fa fa-circle-o"></i> <span>Questions</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/eligibility_criteria">
                            <i class="fa fa-circle-o"></i> <span>Eligibility Criteria</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/exam_schedule">
                            <i class="fa fa-circle-o"></i> <span>Exam Schedule</span>
                        </a>
                    </li>
                </ul>
            </li>        
            <!-- students -->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>&nbsp;
                    <span>Student</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/student">
                            <i class="fa fa-circle-o"></i> <span>Students</span>
                        </a>
                    </li>                    
                </ul>
            </li>
            <!-- settings -->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i>&nbsp;
                    <span>Settings</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/email_setting">
                            <i class="fa fa-circle-o"></i> <span>Email Setting</span>
                        </a>
                    </li>                    
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/logo_setting">
                            <i class="fa fa-circle-o"></i> <span>Logo Setting</span>
                        </a>
                    </li>                    
                </ul>
            </li>
            <!-- users -->
            <li>
                <a href="<?php print PROJECT_ROOT; ?>admin/users">
                    <i class="fa fa-user-plus"></i>
                    <span>Users</span>
                </a>
            </li>
        </ul>
    </section>
</aside>