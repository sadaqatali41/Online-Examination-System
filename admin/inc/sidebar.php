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
                    <span>Master</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/center">
                            <i class="fa fa-circle"></i> <span>Centers</span>
                        </a>
                    </li> 
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/course_category">
                            <i class="fa fa-circle"></i> <span>Course Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/course">
                            <i class="fa fa-circle"></i> <span>Courses</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/question">
                            <i class="fa fa-circle"></i> <span>Questions</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/eligibility_criteria">
                            <i class="fa fa-circle"></i> <span>Eligibility Criteria</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/exam_schedule">
                            <i class="fa fa-circle"></i> <span>Exam Schedule</span>
                        </a>
                    </li>
                </ul>
            </li>        
            <!-- students -->
            <li class="treeview">
                <a href="#">
                    <span>Student</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?php print PROJECT_ROOT; ?>admin/student">
                            <i class="fa fa-circle"></i> <span>Students</span>
                        </a>
                    </li>                    
                </ul>
            </li>        
        </ul>
    </section>
</aside>