<link rel="stylesheet" href="<?=$this->getStaticUrl('../applicatio')?>">
<div class="col-md-4 games-schedule">
    <div class="well">
        <div class="games-schedule-title">
            <div class="row">
                <div class="col-md-12">
                    <h5>Todays Games</h5>
                </div>
            </div>
        </div>                
        <div class="games-schedule-items">
            <div class="row games-team">
                <div class="col-md-5">
                    <img src="http://placehold.it/115x67" alt="Bangladesh">
                    <span>Bangladesh</span>
                </div>
                <div class="col-md-2">
                    <h4 class="img-circle">VS</h4>
                </div>
                <div class="col-md-5">
                    <img src="http://placehold.it/115x67" alt="Australia">
                    <span>Australia</span>
                </div>
            </div>
            <div class="row games-info">
                <div class="col-md-12">
                    <p><span class="glyphicon glyphicon-play-circle"></span> 19 March, 2014 (<small>15:30 local | 09:30 GMT</small>)</p>
                    <p class="games-dash"></p>
                    <p><small>Mirpur Internation Stadium, Dhaka</small></p>
                </div>
            </div>
        </div>  
        <div class="games-schedule-items">
            <div class="row games-team">
                <div class="col-md-5">
                    <img src="http://placehold.it/115x67" alt="Pakistan">
                    <span>Pakistan</span>
                </div>
                <div class="col-md-2">
                    <h4 class="img-circle">VS</h4>
                </div>
                <div class="col-md-5">
                    <img src="http://placehold.it/115x67" alt="India">
                    <span>India</span>
                </div>
            </div>
            <div class="row games-info">
                <div class="col-md-12">
                    <p><span class="glyphicon glyphicon-play-circle"></span> 19 March, 2014 (<small>19:30 local | 13:30 GMT</small>)</p>
                    <p class="games-dash"></p>
                    <p><small>Fahtullah Internation Stadium, Narayanganj</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.games-schedule .well {
    background-color: #fafbfc;
    border: 1px #e7e7e7 solid;
    border-top: 2px #1761a6 solid;
    border-radius: 0px;
    padding: 0 10px 0 10px;
}
.games-schedule .col-md-2 {
    padding: 5px;
}
.games-schedule-title {
    background-color: #1761a6;
    color: #ffffff;
    padding: 5px;
    margin-bottom: 10px;
}
.games-schedule-title h5 {
    font-size: 18px;
    text-align: center;
    margin: 3px 0 3px 0;
}
.games-schedule-items {
    padding: 5px;
    margin-bottom: 15px;
}
.last-item {
    margin-bottom: 0px;
}
.games-team {
    margin-bottom: 12px;
}
.games-team h4 {
    color: #59a1e8;
    text-align: center;
    margin-top: 20px;
    border: 1px #c8d8e7 dotted;
    padding: 5px;
    width: 100%;
}
.games-team img {
    border-bottom: 2px #6b9fd3 solid;
    margin-bottom: 3px;
}
.games-team span {
    color: #de6c10;
    font-size: 13px;
}
.games-dash {
    border: none;
    border-top: 1px #95b3d0 dotted;
}
.games-info p {
    margin: 0;
    padding: 0;
    color: #0a2948;
    line-height: 20px;
}
.games-schedule-footer {
    background-color: #1761a6;
    color: #ffffff;
    font-size: 10px;
    padding: 5px;
}
.games-schedule-footer p {
    margin: 0;
}
</style>
