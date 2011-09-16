<?php
class skin_home {
function MailErrorLogElement($errorLog){
global $bw;
$BWHTML = "";
$BWHTML .= <<<EOF
<tr>
    <td>{$errorLog['mlog_date']}</td>
    <td>
    	<p>From: {$errorLog['mlog_from']}</p>
        <p>To: {$errorLog['mlog_to']}</p>
    </td>
    <td>{$errorLog['mlog_code']}</td>
    <td>{$errorLog['mlog_msg']}</td>
</tr>
EOF;
return $BWHTML;
}

function MainHome($data=array()){
global $bw, $vsLang;
$admin = null;
$BWHTML = "";
$BWHTML .= <<<EOF
<script type="text/javascript">
$(function(){
	$(".ui-accordion").accordion();
});
</script>
<div class="left-cell ui-accordion">
    <h3><a href="#">Quick Action</a></h3>
    <div>Danh sách các Action ở đây</div>
</div>
<div class="right-cell ui-accordion">
	<h3><a href="#">Welcome</a></h3>
	<div>{$vsLang->getWords('vsf_welcome_message','')}</div>
    <h3><a href="#">{$vsLang->getWords('home_last_login',"View ACP Log In Logs")}</a></h3>
	<div>
    	<table cellpadding="0" cellspacing="1" width="100%">
        	<tr>
            	<th>{$vsLang->getWords('home_last_login_id',"Id")}</th>
                <th>{$vsLang->getWords('home_last_login_name',"Name")}</th>
                <th>{$vsLang->getWords('home_last_login_group',"Groups")}</th>
                <th>{$vsLang->getWords('home_last_login_time',"Time")}</th>
            </tr>
            <if="count($data['last_login'])">
            <foreach="$data['last_login'] as $admin">
            <tr>
            	<td>{$admin->getId()}</td>
                <td>{$admin->getName()}</td>
                <td>{$admin->getMainGroup()->getName()}</td>
                <td>{$admin->getLastLogin(false,"LONG")}</td>
			</tr>
            </foreach>
            </if>
        </table>
    </div>
    <h3><a href="#">Popular</a></h3>
	<div>{$vsLang->getWords('vsf_welcome_message','Welcome message here!')}</div>
    <h3><a href="#">Recent added Articles</a></h3>
	<div>{$vsLang->getWords('vsf_welcome_message','Welcome message here!')}</div>
    <h3><a href="#">eMail Error Logs</a></h3>
	<div>
        <table cellpadding="0" cellspacing="1" width="100%">
            <tr>
                <th>Log Date</th>
                <th>To</th>
                <th>Error Code</th>
                <th>Error Msg</th>
            </tr>
            <!--MAIL ERROR LOGS-->
        </table>
    </div>
    <h3><a href="#">VSF! Newsfeed</a></h3>
	<div>{$vsLang->getWords('vsf_welcome_message','Welcome message here!')}</div>
</div>
EOF;
return $BWHTML;
}
}
?>