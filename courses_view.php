***REMOVED***
	require_once 'includes/common.php';

	if (!isset($_SESSION['userid'])) {
		header("Location: .");
	}

    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        $courseDAO = new CourseDAO();
        $course = $courseDAO->retrieveByCode($code);
    
    
        $viewData['title'] = $course['course'] . " " . $course['title'];
        $viewData['styles'] = "
            <style>
                .course-code {
                    display: block;
                    margin-bottom: .2rem;
                    font-weight: 200;
                    font-size: 2.4rem;
                    /*color: #ff5138;*/
            ***REMOVED***

                .container{
                    display:flex;
                    overflow-y:auto;
                    align-items:center;
                    padding:3rem 0
            ***REMOVED***
                @media (min-width:576px){
                    .container{
                        justify-content:center
                ***REMOVED***
            ***REMOVED***

                .root .color-1{
                    margin:0;
                    font-size:1.1rem
            ***REMOVED***
                .root .color-1:before{
                    display:none
            ***REMOVED***
                .tree,.prereqTree{
                    position:relative;
                    list-style:none;
                    padding:0;
                    margin:.125rem 0;
                    text-align:center
            ***REMOVED***
                .node{
                    position:relative;
                    display:flex;
                    flex:0 1 5rem;
                    justify-content:center;
                    align-items:center;
                    padding:.125rem .25rem;
                    margin:0 0 1px .75rem;
                    border-radius:.25rem
            ***REMOVED***
                .node:before{
                    top:50%;
                    left:-.75rem;
                    width:.75rem;
                    height:1px
            ***REMOVED***
                .link{
                    display:block;
                    width:100%
            ***REMOVED***
                .link,.link:hover{
                    color:currentColor
            ***REMOVED***
                .branch:after,.branch:before,.conditional:after,.node:before{
                    content:\"\";
                    position:absolute;
                    background:#d3d3d3;
            ***REMOVED***
                .conditional{
                    flex:0 0 auto;
                    margin-right:.75rem;
                    border:0
            ***REMOVED***
                .conditional:after{
                    top:50%;
                    right:-.75rem;
                    width:.75rem;
                    height:1px
            ***REMOVED***
                .branch{
                    position:relative;
                    display:flex;
                    align-items:center
            ***REMOVED***
                .branch:after,.branch:before{
                    left:0;
                    width:1px;
                    height:50%
            ***REMOVED***
                .branch:before{
                    top:0
            ***REMOVED***
                .branch:after{
                    bottom:0
            ***REMOVED***
                .branch:first-child:before,.branch:last-child:after{
                    display:none
            ***REMOVED***
                .prereqBranch:after,.prereqBranch:before{
                    right:0;
                    left:auto
            ***REMOVED***
                .prereqNode{
                    margin:0 .75rem 1px 0
            ***REMOVED***
                .prereqNode:before{
                    right:-.75rem;
                    left:auto
            ***REMOVED***
                
            </style>";

        include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <span class="course-code">***REMOVED*** echo $course['course']; ?></span>
                ***REMOVED*** echo $course['title']; ?>
            </h1>
            <p class="pt-2">***REMOVED*** echo $course['school']; ?> • 1 Credit Unit</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="row pb-5">
                    <div class="col-md-8">
                        <p class="pt-2">
                            ***REMOVED*** echo $course['description']; ?>
                        </p>

                        ***REMOVED***
                            $prerequisites = $courseDAO->prerequisites($course['course']);

                            if ($prerequisites) {
                        ?>
                        <span><b>Prerequisite</b></span>
                            <section>
                                <div class="container">
                                    <!-- The current viewed course is a prerequisite of the following -->
                                    <ul class="tree">
                                        <li class="branch prereqBranch">
                                            <div class="node prereqNode">
                                                <div class="label label-info">Node1</div>
                                            </div>
                                        </li>
                                        <li class="branch prereqBranch">
                                            <div class="node prereqNode">
                                                <div class="label label-info">Node2</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="node conditional">needs</div>

                                    <!-- Root -->
                                    <ul class="prereqTree root">
                                        <li class="branch">
                                            <div class="node">
                                                <div class="label label-info">***REMOVED*** echo $course['course']; ?></div>
                                            </div>
                                        </li>
                                    </ul>

                                    <!-- Requires the following courses -->
                                    <div class="node conditional">all of</div>
                                    <ul class="tree">

                                        ***REMOVED*** 
                                            foreach ($prerequisites as $prerequisite) {
                                        ?>
                                        <li class="branch">
                                            <div class="node">
                                                <a href="courses_view?code=***REMOVED*** echo $prerequisite; ?>">***REMOVED*** echo $prerequisite; ?></a>
                                            </div>
                                        </li>
                                        ***REMOVED***
                                        ***REMOVED***
                                        ?>
                                    </ul>
                                </div>
                            </section>
                        </p>
                        ***REMOVED***
                        ***REMOVED***
                        ?>
                    </div>
                    <div class="col-md-4">
                        <section>
                            <span><b>Exam</b></span>
                            <p>***REMOVED*** echo $course['exam date']; ?> ***REMOVED*** echo $course['exam start']; ?> - ***REMOVED*** echo $course['exam end']; ?> • X hours</p>
                        </section>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </main>

***REMOVED***
***REMOVED***

	include 'includes/views/footer.php';
?>