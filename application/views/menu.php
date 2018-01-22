<div id="mainMenu">
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>    
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a class="navbar-brand" href="main.php">{pagetitle}</a></li>
                <li class=""><a href="main.php">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Client <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/client/create">Add</a></li>
                        <li><a href="/client">Show</a></li>
                    </ul>
                </li>
                <li class=""><a href="/profile">Profile</a></li>                          
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/profile">Logged as: {user_email}</a></li>
                <li><a href="/login/logout"> Exit</a></li>    
            </ul>
        </div>
    </nav>
</div>